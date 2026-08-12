<?php

declare(strict_types=1);

class Dashboard {

    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }


    /**
     * Registra el acceso de un usuario con su IP en la tabla iphys.
     */
    public function registrarAccesoIp(string $username, string $rol, string $ip): bool {
        $stmt = $this->pdo->prepare("
            INSERT INTO iphys (usuario_username, rol_name, ip_address, fecha_acceso) 
            VALUES (:username, :rol, :ip, NOW())
        ");

        return $stmt->execute([
            ':username' => $username,
            ':rol'      => $rol,
            ':ip'       => $ip
        ]);
    }

    /**
     * Obtiene el historial completo de IPs registradas con el nombre del usuario.
     */
    public function getHistorialIps(): array {
        $stmt = $this->pdo->query("
            SELECT 
                i.id,
                i.usuario_username,
                CONCAT(u.nombres, ' ', u.apellidos) AS usuario_nombre_completo,
                i.rol_name,
                i.ip_address,
                i.fecha_acceso
            FROM iphys i
            INNER JOIN usuarios u ON i.usuario_username = u.username
            ORDER BY i.fecha_acceso DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene el detalle específico de un registro de IP.
     */
    public function getIpDetalleById(int $id): array|false {
        $stmt = $this->pdo->prepare("
            SELECT 
                i.id,
                i.usuario_username,
                CONCAT(u.nombres, ' ', u.apellidos) AS usuario_nombre_completo,
                u.correo,
                u.telefono,
                i.rol_name,
                i.ip_address,
                i.fecha_acceso
            FROM iphys i
            INNER JOIN usuarios u ON i.usuario_username = u.username
            WHERE i.id = ?
        ");

        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ==========================================
    // KPIs DEL PANEL (basados en tipo_plaza, parking, tarifas_cobros, usuarios)
    // ==========================================

    /**
     * Tarjetas resumen: ocupación actual, ingresos de hoy/mes, tickets del día, usuarios activos.
     */
    public function getResumenGeneral(): array {
        $stmt = $this->pdo->query("
            SELECT
                COALESCE(SUM(capacidad_total), 0)   AS capacidad_total,
                COALESCE(SUM(espacios_ocupados), 0) AS espacios_ocupados
            FROM tipo_plaza
            WHERE activo = 1
        ");
        $ocupacion = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $this->pdo->query("SELECT COUNT(*) FROM parking WHERE estado = 'ACTIVO'");
        $vehiculosActivos = (int)$stmt->fetchColumn();

        $stmt = $this->pdo->query("
            SELECT COALESCE(SUM(monto_total), 0) FROM tarifas_cobros
            WHERE DATE(fecha_hora_pago) = CURDATE()
        ");
        $ingresosHoy = (float)$stmt->fetchColumn();

        $stmt = $this->pdo->query("
            SELECT COALESCE(SUM(monto_total), 0) FROM tarifas_cobros
            WHERE YEAR(fecha_hora_pago) = YEAR(CURDATE()) AND MONTH(fecha_hora_pago) = MONTH(CURDATE())
        ");
        $ingresosMes = (float)$stmt->fetchColumn();

        $stmt = $this->pdo->query("
            SELECT COUNT(*) FROM tarifas_cobros WHERE DATE(fecha_hora_pago) = CURDATE()
        ");
        $ticketsFinalizadosHoy = (int)$stmt->fetchColumn();

        $stmt = $this->pdo->query("SELECT COUNT(*) FROM usuarios WHERE activo = 1");
        $usuariosActivos = (int)$stmt->fetchColumn();

        $capacidadTotal = (int)$ocupacion['capacidad_total'];
        $espaciosOcupados = (int)$ocupacion['espacios_ocupados'];

        return [
            'vehiculos_activos'        => $vehiculosActivos,
            'capacidad_total'          => $capacidadTotal,
            'espacios_ocupados'        => $espaciosOcupados,
            'espacios_disponibles'     => max(0, $capacidadTotal - $espaciosOcupados),
            'porcentaje_ocupacion'     => $capacidadTotal > 0 ? round(($espaciosOcupados / $capacidadTotal) * 100, 1) : 0.0,
            'ingresos_hoy'             => round($ingresosHoy, 2),
            'ingresos_mes'             => round($ingresosMes, 2),
            'tickets_finalizados_hoy'  => $ticketsFinalizadosHoy,
            'usuarios_activos'         => $usuariosActivos,
        ];
    }

    /**
     * Ocupación por cada tipo de plaza activo, para barras de progreso.
     */
    public function getOcupacionPorTipo(): array {
        $stmt = $this->pdo->query("
            SELECT nombre, precio_hora, capacidad_total, espacios_ocupados
            FROM tipo_plaza
            WHERE activo = 1
            ORDER BY nombre ASC
        ");
        $tipos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tipos as &$tipo) {
            $capacidad = (int)$tipo['capacidad_total'];
            $ocupados = (int)$tipo['espacios_ocupados'];
            $tipo['porcentaje'] = $capacidad > 0 ? round(($ocupados / $capacidad) * 100, 1) : 0.0;
        }

        return $tipos;
    }

    /**
     * Ingresos cobrados por día durante los últimos 7 días (incluye hoy).
     * Devuelve solo los días con datos; el controller completa los días vacíos con 0.
     */
    public function getIngresosUltimos7Dias(): array {
        $stmt = $this->pdo->query("
            SELECT DATE(fecha_hora_pago) AS fecha, SUM(monto_total) AS total
            FROM tarifas_cobros
            WHERE fecha_hora_pago >= (CURDATE() - INTERVAL 6 DAY)
            GROUP BY DATE(fecha_hora_pago)
            ORDER BY fecha ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}