<?php

declare(strict_types=1);

require_once __DIR__ . '/ChecksPermisos.php';
require_once __DIR__ . '/../models/TarifasCobros.php';

class TarifasController {

    use ChecksPermisos;

    public function index(PDO $pdo): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '?c=auth&a=login');
            exit;
        }
        $this->requierePermiso('TARIFAS');

        $title = "Tarifas y Cobros";
        require_once __DIR__ . '/../views/tarifas/index.php';
    }

    public function listar(PDO $pdo): void {
        $this->requierePermisoApi('TARIFAS');

        $tarifasModel = new TarifasCobros($pdo);
        echo json_encode(['success' => true, 'data' => $tarifasModel->getAll()]);
        exit;
    }

    public function obtener(PDO $pdo): void {
        $this->requierePermisoApi('TARIFAS');

        $codigo = strtoupper(trim((string)($_GET['parking_codigo'] ?? '')));
        if ($codigo === '') {
            echo json_encode(['success' => false, 'message' => 'Código no válido']);
            exit;
        }

        $tarifasModel = new TarifasCobros($pdo);
        $cobro = $tarifasModel->getByCodigo($codigo);

        if ($cobro) {
            echo json_encode(['success' => true, 'data' => $cobro]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró el cobro']);
        }
        exit;
    }

    /**
     * Aplica un descuento/tolerancia sobre un cobro ya registrado. Requiere permiso TARIFAS,
     * separado y más restringido que el permiso PARQUEO que registra la salida normal.
     */
    public function aplicarDescuento(PDO $pdo): void {
        $this->requierePermisoApi('TARIFAS');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }

        $codigo = strtoupper(trim((string)($_POST['parking_codigo'] ?? '')));
        $descuentoRaw = $_POST['descuento'] ?? '';

        if ($codigo === '') {
            echo json_encode(['success' => false, 'message' => 'Código no válido']);
            exit;
        }

        if (!is_numeric($descuentoRaw)) {
            echo json_encode(['success' => false, 'message' => 'El descuento debe ser un número.']);
            exit;
        }

        $descuento = (float)$descuentoRaw;

        $tarifasModel = new TarifasCobros($pdo);

        try {
            $resultado = $tarifasModel->aplicarDescuento($codigo, $descuento);
            echo json_encode(['success' => $resultado, 'message' => $resultado ? 'Descuento aplicado correctamente' : 'No se pudo aplicar el descuento']);
        } catch (RuntimeException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } catch (PDOException $e) {
            error_log('Error en TarifasController::aplicarDescuento: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error interno en la base de datos']);
        }
        exit;
    }
}
