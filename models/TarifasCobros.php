<?php

declare(strict_types=1);

class TarifasCobros {

    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAll(): array {
        $stmt = $this->pdo->query("
            SELECT tc.id, tc.parking_codigo, tc.usuario_cobro_username, tc.minutos_totales,
                   tc.subtotal, tc.descuento, tc.monto_total, tc.fecha_hora_pago,
                   p.placa, p.tipo_plaza_nombre
            FROM tarifas_cobros tc
            JOIN parking p ON p.codigo = tc.parking_codigo
            ORDER BY tc.fecha_hora_pago DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCodigo(string $parkingCodigo): array|false {
        $stmt = $this->pdo->prepare("
            SELECT tc.id, tc.parking_codigo, tc.usuario_cobro_username, tc.minutos_totales,
                   tc.subtotal, tc.descuento, tc.monto_total, tc.fecha_hora_pago,
                   p.placa, p.tipo_plaza_nombre
            FROM tarifas_cobros tc
            JOIN parking p ON p.codigo = tc.parking_codigo
            WHERE tc.parking_codigo = ?
        ");
        $stmt->execute([$parkingCodigo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Aplica un descuento/tolerancia sobre un cobro ya registrado.
     * Regla: descuento no puede ser negativo ni superar el subtotal.
     * NUNCA modifica minutos_totales ni la hora real de salida.
     */
    public function aplicarDescuento(string $parkingCodigo, float $descuento): bool {
        $cobro = $this->getByCodigo($parkingCodigo);
        if (!$cobro) {
            throw new RuntimeException('No se encontró el cobro asociado a ese ticket.');
        }

        if ($descuento < 0) {
            throw new RuntimeException('El descuento no puede ser negativo.');
        }

        $subtotal = (float)$cobro['subtotal'];
        if ($descuento > $subtotal) {
            throw new RuntimeException('El descuento no puede superar el subtotal (S/ ' . number_format($subtotal, 2) . ').');
        }

        $montoTotal = round($subtotal - $descuento, 2);

        $stmt = $this->pdo->prepare("
            UPDATE tarifas_cobros SET descuento = :descuento, monto_total = :monto_total
            WHERE parking_codigo = :parking_codigo
        ");
        return $stmt->execute([
            ':descuento' => round($descuento, 2),
            ':monto_total' => $montoTotal,
            ':parking_codigo' => $parkingCodigo,
        ]);
    }
}
