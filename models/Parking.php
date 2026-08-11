<?php

declare(strict_types=1);

/**
 * Excepción de negocio controlada: su mensaje es seguro para mostrar al operador.
 */
class ParkingBusinessException extends RuntimeException {}

class Parking {

    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // ==========================================
    // CONSULTAS
    // ==========================================

    public function getActivos(): array {
        $stmt = $this->pdo->query("
            SELECT p.id, p.codigo, p.placa, p.tipo_plaza_nombre, p.fecha_hora_ingreso, p.estado,
                   tp.precio_hora
            FROM parking p
            JOIN tipo_plaza tp ON tp.nombre = p.tipo_plaza_nombre
            WHERE p.estado = 'ACTIVO'
            ORDER BY p.fecha_hora_ingreso ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActivoByCodigo(string $codigo): array|false {
        $stmt = $this->pdo->prepare("
            SELECT p.id, p.codigo, p.placa, p.tipo_plaza_nombre, p.fecha_hora_ingreso, p.estado,
                   tp.precio_hora
            FROM parking p
            JOIN tipo_plaza tp ON tp.nombre = p.tipo_plaza_nombre
            WHERE p.codigo = ? AND p.estado = 'ACTIVO'
        ");
        $stmt->execute([$codigo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getActivoByPlaca(string $placa): array|false {
        $stmt = $this->pdo->prepare("
            SELECT p.id, p.codigo, p.placa, p.tipo_plaza_nombre, p.fecha_hora_ingreso, p.estado,
                   tp.precio_hora
            FROM parking p
            JOIN tipo_plaza tp ON tp.nombre = p.tipo_plaza_nombre
            WHERE p.placa = ? AND p.estado = 'ACTIVO'
            LIMIT 1
        ");
        $stmt->execute([$placa]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function existeCodigo(string $codigo): bool {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM parking WHERE codigo = ?");
        $stmt->execute([$codigo]);
        return (int)$stmt->fetchColumn() > 0;
    }

    /**
     * Genera un código corto tipo PRK-XXXXXXX (letras/números), comprueba existencia
     * y reintenta si ya existe. El UNIQUE de BD es la protección final.
     */
    private function generarCodigoUnico(): string {
        $alfabeto = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // sin O/0/I/1 para evitar confusión visual
        $intentos = 0;

        do {
            $sufijo = '';
            for ($i = 0; $i < 6; $i++) {
                $sufijo .= $alfabeto[random_int(0, strlen($alfabeto) - 1)];
            }
            $codigo = 'PRK-' . $sufijo;
            $intentos++;
        } while ($this->existeCodigo($codigo) && $intentos < 20);

        if ($intentos >= 20 && $this->existeCodigo($codigo)) {
            throw new ParkingBusinessException('No se pudo generar un código único, intenta nuevamente.');
        }

        return $codigo;
    }

    /**
     * Calcula minutos/horas/tarifa para un ticket ACTIVO sin modificar nada (usado para
     * mostrar el detalle en el modal de salida antes de confirmar el cobro).
     */
    public function calcularTarifaVista(string $codigo): array {
        $ticket = $this->getActivoByCodigo($codigo);
        if (!$ticket) {
            throw new ParkingBusinessException('El ticket no existe o ya fue finalizado.');
        }

        $ingreso = new DateTime($ticket['fecha_hora_ingreso']);
        $ahora = new DateTime();
        $minutos = max(0, (int)floor(($ahora->getTimestamp() - $ingreso->getTimestamp()) / 60));

        $horas = max(1, (int)ceil($minutos / 60));
        $precioHora = (float)$ticket['precio_hora'];
        $subtotal = round($horas * $precioHora, 2);

        return [
            'codigo' => $ticket['codigo'],
            'placa' => $ticket['placa'],
            'tipo_plaza_nombre' => $ticket['tipo_plaza_nombre'],
            'fecha_hora_ingreso' => $ticket['fecha_hora_ingreso'],
            'minutos_totales' => $minutos,
            'horas_cobradas' => $horas,
            'precio_hora' => $precioHora,
            'subtotal' => $subtotal,
            'monto_total' => $subtotal,
        ];
    }

    // ==========================================
    // OPERACIONES TRANSACCIONALES
    // ==========================================

    /**
     * Ingreso de vehículo. Lanza ParkingBusinessException con mensaje seguro para el operador.
     */
    public function ingresar(string $placa, string $tipoPlazaNombre, string $usuarioUsername): array {
        $enTransaccionPropia = !$this->pdo->inTransaction();
        if ($enTransaccionPropia) {
            $this->pdo->beginTransaction();
        }

        try {
            // Bloquea la fila del tipo de plaza para evitar condiciones de carrera sobre espacios_ocupados
            $stmt = $this->pdo->prepare("SELECT nombre, activo, capacidad_total, espacios_ocupados FROM tipo_plaza WHERE nombre = ? FOR UPDATE");
            $stmt->execute([$tipoPlazaNombre]);
            $tipo = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$tipo) {
                throw new ParkingBusinessException('El tipo de plaza seleccionado no existe.');
            }
            if ((int)$tipo['activo'] !== 1) {
                throw new ParkingBusinessException('El tipo de plaza seleccionado está inactivo.');
            }
            if ((int)$tipo['espacios_ocupados'] >= (int)$tipo['capacidad_total']) {
                throw new ParkingBusinessException('No hay espacios disponibles para este tipo de plaza.');
            }

            // Verifica que la placa no tenga ya un ticket activo (bloquea filas de parking de esa placa)
            $stmt = $this->pdo->prepare("SELECT id FROM parking WHERE placa = ? AND estado = 'ACTIVO' FOR UPDATE");
            $stmt->execute([$placa]);
            if ($stmt->fetch()) {
                throw new ParkingBusinessException("La placa '{$placa}' ya tiene un ticket activo en el parqueo.");
            }

            $codigo = $this->generarCodigoUnico();

            $stmt = $this->pdo->prepare("
                INSERT INTO parking (codigo, placa, tipo_plaza_nombre, usuario_ingreso_username, fecha_hora_ingreso, estado)
                VALUES (:codigo, :placa, :tipo_plaza_nombre, :usuario_username, NOW(), 'ACTIVO')
            ");
            $stmt->execute([
                ':codigo' => $codigo,
                ':placa' => $placa,
                ':tipo_plaza_nombre' => $tipoPlazaNombre,
                ':usuario_username' => $usuarioUsername,
            ]);

            $stmt = $this->pdo->prepare("UPDATE tipo_plaza SET espacios_ocupados = espacios_ocupados + 1 WHERE nombre = ?");
            $stmt->execute([$tipoPlazaNombre]);

            $ticket = $this->getActivoByCodigo($codigo);

            if ($enTransaccionPropia) {
                $this->pdo->commit();
            }

            return $ticket;
        } catch (Throwable $e) {
            if ($enTransaccionPropia && $this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }

    /**
     * Finaliza un ticket: calcula tarifa, registra el cobro (descuento = 0 por defecto,
     * se ajusta luego desde el módulo TARIFAS), marca FINALIZADO y libera el espacio.
     * Todo en una única transacción; si algo falla, ROLLBACK.
     */
    public function finalizar(string $codigo, string $usuarioUsername): array {
        $enTransaccionPropia = !$this->pdo->inTransaction();
        if ($enTransaccionPropia) {
            $this->pdo->beginTransaction();
        }

        try {
            // Bloquea la fila del ticket: si otro operador ya lo está finalizando, esta
            // consulta espera hasta que termine y luego ve el estado ya actualizado.
            $stmt = $this->pdo->prepare("SELECT * FROM parking WHERE codigo = ? FOR UPDATE");
            $stmt->execute([$codigo]);
            $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$ticket) {
                throw new ParkingBusinessException('El ticket no existe.');
            }
            if ($ticket['estado'] !== 'ACTIVO') {
                throw new ParkingBusinessException('Este ticket ya fue finalizado anteriormente.');
            }

            $stmt = $this->pdo->prepare("SELECT precio_hora FROM tipo_plaza WHERE nombre = ? FOR UPDATE");
            $stmt->execute([$ticket['tipo_plaza_nombre']]);
            $tipo = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$tipo) {
                throw new ParkingBusinessException('El tipo de plaza asociado ya no existe.');
            }

            $ingreso = new DateTime($ticket['fecha_hora_ingreso']);
            $ahora = new DateTime();
            $minutos = max(0, (int)floor(($ahora->getTimestamp() - $ingreso->getTimestamp()) / 60));
            $horas = max(1, (int)ceil($minutos / 60));
            $precioHora = (float)$tipo['precio_hora'];
            $subtotal = round($horas * $precioHora, 2);
            $descuento = 0.00;
            $montoTotal = $subtotal - $descuento;

            $stmt = $this->pdo->prepare("
                INSERT INTO tarifas_cobros
                    (parking_codigo, usuario_cobro_username, minutos_totales, subtotal, descuento, monto_total, fecha_hora_pago)
                VALUES
                    (:parking_codigo, :usuario_username, :minutos, :subtotal, :descuento, :monto_total, NOW())
            ");
            $stmt->execute([
                ':parking_codigo' => $codigo,
                ':usuario_username' => $usuarioUsername,
                ':minutos' => $minutos,
                ':subtotal' => $subtotal,
                ':descuento' => $descuento,
                ':monto_total' => $montoTotal,
            ]);

            $stmt = $this->pdo->prepare("
                UPDATE parking SET fecha_hora_salida = NOW(), estado = 'FINALIZADO'
                WHERE codigo = ? AND estado = 'ACTIVO'
            ");
            $stmt->execute([$codigo]);
            if ($stmt->rowCount() !== 1) {
                // Otro proceso lo finalizó entre el SELECT ... FOR UPDATE y aquí (no debería
                // ocurrir gracias al lock, pero se valida por seguridad).
                throw new ParkingBusinessException('Este ticket ya fue finalizado anteriormente.');
            }

            $stmt = $this->pdo->prepare("
                UPDATE tipo_plaza SET espacios_ocupados = espacios_ocupados - 1
                WHERE nombre = ? AND espacios_ocupados > 0
            ");
            $stmt->execute([$ticket['tipo_plaza_nombre']]);

            if ($enTransaccionPropia) {
                $this->pdo->commit();
            }

            return [
                'codigo' => $codigo,
                'placa' => $ticket['placa'],
                'minutos_totales' => $minutos,
                'horas_cobradas' => $horas,
                'subtotal' => $subtotal,
                'descuento' => $descuento,
                'monto_total' => $montoTotal,
            ];
        } catch (Throwable $e) {
            if ($enTransaccionPropia && $this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }
}
