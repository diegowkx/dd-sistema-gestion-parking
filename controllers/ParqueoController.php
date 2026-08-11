<?php

declare(strict_types=1);

require_once __DIR__ . '/ChecksPermisos.php';
require_once __DIR__ . '/../models/Parking.php';

class ParqueoController {

    use ChecksPermisos;

    public function index(PDO $pdo): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '?c=auth&a=login');
            exit;
        }
        $this->requierePermiso('PARQUEO');

        require_once __DIR__ . '/../models/TipoPlaza.php';
        $tipoPlazaModel = new TipoPlaza($pdo);
        $tiposDisponibles = $tipoPlazaModel->getActivosConEspacio();

        $title = "Control de Parqueo";
        require_once __DIR__ . '/../views/parqueo/index.php';
    }

    public function listar(PDO $pdo): void {
        $this->requierePermisoApi('PARQUEO');

        $parkingModel = new Parking($pdo);
        $data = $parkingModel->getActivos();

        echo json_encode(['success' => true, 'data' => $data]);
        exit;
    }

    /**
     * Devuelve los tipos de plaza activos con espacio libre, para el select del modal de ingreso.
     */
    public function tiposDisponibles(PDO $pdo): void {
        $this->requierePermisoApi('PARQUEO');

        require_once __DIR__ . '/../models/TipoPlaza.php';
        $tipoPlazaModel = new TipoPlaza($pdo);

        echo json_encode(['success' => true, 'data' => $tipoPlazaModel->getActivosConEspacio()]);
        exit;
    }

    /**
     * Busca un ticket ACTIVO por código o placa (usado por "Ticket perdido" y por el
     * buscador del modal de salida).
     */
    public function buscar(PDO $pdo): void {
        $this->requierePermisoApi('PARQUEO');

        $codigo = trim((string)($_GET['codigo'] ?? ''));
        $placa = trim((string)($_GET['placa'] ?? ''));

        if ($codigo === '' && $placa === '') {
            echo json_encode(['success' => false, 'message' => 'Ingresa un código o una placa para buscar.']);
            exit;
        }

        $parkingModel = new Parking($pdo);

        $ticket = $codigo !== ''
            ? $parkingModel->getActivoByCodigo(strtoupper($codigo))
            : $parkingModel->getActivoByPlaca(strtoupper($placa));

        if ($ticket) {
            echo json_encode(['success' => true, 'data' => $ticket]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró ningún ticket activo con ese dato.']);
        }
        exit;
    }

    public function ingresar(PDO $pdo): void {
        $this->requierePermisoApi('PARQUEO');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }

        $placa = strtoupper(trim((string)($_POST['placa'] ?? '')));
        $tipoPlazaNombre = strtoupper(trim((string)($_POST['tipo_plaza_nombre'] ?? '')));

        if ($placa === '' || $tipoPlazaNombre === '') {
            echo json_encode(['success' => false, 'message' => 'La placa y el tipo de plaza son obligatorios.']);
            exit;
        }

        if (!preg_match('/^[A-Z0-9-]{5,15}$/', $placa)) {
            echo json_encode(['success' => false, 'message' => 'La placa ingresada no es válida.']);
            exit;
        }

        $usuarioUsername = (string)($_SESSION['username'] ?? '');
        if ($usuarioUsername === '') {
            echo json_encode(['success' => false, 'message' => 'Sesión no válida, vuelve a iniciar sesión.']);
            exit;
        }

        $parkingModel = new Parking($pdo);

        try {
            $ticket = $parkingModel->ingresar($placa, $tipoPlazaNombre, $usuarioUsername);
            echo json_encode(['success' => true, 'message' => 'Ingreso registrado correctamente', 'data' => $ticket]);
        } catch (ParkingBusinessException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                echo json_encode(['success' => false, 'message' => 'Conflicto al generar el código del ticket, intenta nuevamente.']);
            } else {
                error_log('Error en ParqueoController::ingresar: ' . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Error interno en la base de datos']);
            }
        }
        exit;
    }

    /**
     * Calcula (sin registrar nada) el detalle de tarifa de un ticket activo, para
     * mostrarlo al operador antes de confirmar el cobro en el modal de salida.
     */
    public function calcularTarifa(PDO $pdo): void {
        $this->requierePermisoApi('PARQUEO');

        $codigo = strtoupper(trim((string)($_GET['codigo'] ?? '')));
        if ($codigo === '') {
            echo json_encode(['success' => false, 'message' => 'Código no válido']);
            exit;
        }

        $parkingModel = new Parking($pdo);

        try {
            $detalle = $parkingModel->calcularTarifaVista($codigo);
            echo json_encode(['success' => true, 'data' => $detalle]);
        } catch (ParkingBusinessException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function finalizar(PDO $pdo): void {
        $this->requierePermisoApi('PARQUEO');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }

        $codigo = strtoupper(trim((string)($_POST['codigo'] ?? '')));
        if ($codigo === '') {
            echo json_encode(['success' => false, 'message' => 'Código no válido']);
            exit;
        }

        $usuarioUsername = (string)($_SESSION['username'] ?? '');
        if ($usuarioUsername === '') {
            echo json_encode(['success' => false, 'message' => 'Sesión no válida, vuelve a iniciar sesión.']);
            exit;
        }

        $parkingModel = new Parking($pdo);

        try {
            $resultado = $parkingModel->finalizar($codigo, $usuarioUsername);
            echo json_encode(['success' => true, 'message' => 'Salida y cobro registrados correctamente', 'data' => $resultado]);
        } catch (ParkingBusinessException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        } catch (PDOException $e) {
            error_log('Error en ParqueoController::finalizar: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error interno en la base de datos']);
        }
        exit;
    }
}
