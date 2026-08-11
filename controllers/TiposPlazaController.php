<?php

declare(strict_types=1);

require_once __DIR__ . '/ChecksPermisos.php';

class TiposPlazaController {

    use ChecksPermisos;

    public function index(PDO $pdo): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '?c=auth&a=login');
            exit;
        }
        $this->requierePermiso('TIPOS_PLAZA');

        $title = "Gestión de Tipos de Plaza";
        require_once __DIR__ . '/../views/tipos_plaza/index.php';
    }

    public function listar(PDO $pdo): void {
        $this->requierePermisoApi('TIPOS_PLAZA');

        require_once __DIR__ . '/../models/TipoPlaza.php';
        $tipoPlazaModel = new TipoPlaza($pdo);

        $estado = strtoupper(trim((string)($_GET['estado'] ?? 'ACTIVO')));
        $data = $estado === 'INACTIVO' ? $tipoPlazaModel->getAllInactive() : $tipoPlazaModel->getAll();

        echo json_encode(['success' => true, 'data' => $data]);
        exit;
    }

    public function obtener(PDO $pdo): void {
        $this->requierePermisoApi('TIPOS_PLAZA');

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Id no válido']);
            exit;
        }

        require_once __DIR__ . '/../models/TipoPlaza.php';
        $tipoPlazaModel = new TipoPlaza($pdo);

        $tipo = $tipoPlazaModel->getById($id);
        if ($tipo) {
            echo json_encode(['success' => true, 'data' => $tipo]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Tipo de plaza no encontrado']);
        }
        exit;
    }

    public function guardar(PDO $pdo): void {
        $this->requierePermisoApi('TIPOS_PLAZA');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }

        $id          = (int)($_POST['id'] ?? 0);
        $esEdicion   = !empty($_POST['es_edicion']) && $_POST['es_edicion'] === '1';

        $data = [
            'nombre'          => trim((string)($_POST['nombre'] ?? '')),
            'precio_hora'     => $_POST['precio_hora'] ?? '',
            'capacidad_total' => $_POST['capacidad_total'] ?? '',
        ];

        // 1. Campos obligatorios
        if ($data['nombre'] === '' || $data['precio_hora'] === '' || $data['capacidad_total'] === '') {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
            exit;
        }

        // 2. Nombre
        if (mb_strlen($data['nombre']) > 50) {
            echo json_encode(['success' => false, 'message' => 'El nombre no debe superar los 50 caracteres.']);
            exit;
        }
        $data['nombre'] = mb_strtoupper($data['nombre']);

        // 3. precio_hora debe ser numérico y mayor que 0
        if (!is_numeric($data['precio_hora']) || (float)$data['precio_hora'] <= 0) {
            echo json_encode(['success' => false, 'message' => 'El precio por hora debe ser un número mayor que 0.']);
            exit;
        }

        // 4. capacidad_total debe ser entero mayor que 0
        if (!ctype_digit((string)$data['capacidad_total']) || (int)$data['capacidad_total'] <= 0) {
            echo json_encode(['success' => false, 'message' => 'La capacidad total debe ser un número entero mayor que 0.']);
            exit;
        }

        require_once __DIR__ . '/../models/TipoPlaza.php';
        $tipoPlazaModel = new TipoPlaza($pdo);

        // Unicidad de nombre
        if ($tipoPlazaModel->existeNombre($data['nombre'], $esEdicion ? $id : null)) {
            echo json_encode(['success' => false, 'message' => "El tipo de plaza '{$data['nombre']}' ya existe."]);
            exit;
        }

        // No permitir reducir capacidad_total por debajo de espacios_ocupados
        if ($esEdicion) {
            $actual = $tipoPlazaModel->getById($id);
            if (!$actual) {
                echo json_encode(['success' => false, 'message' => 'Tipo de plaza no encontrado.']);
                exit;
            }
            if ((int)$data['capacidad_total'] < (int)$actual['espacios_ocupados']) {
                echo json_encode([
                    'success' => false,
                    'message' => "No puedes reducir la capacidad a {$data['capacidad_total']}: actualmente hay {$actual['espacios_ocupados']} espacio(s) ocupado(s)."
                ]);
                exit;
            }
        }

        try {
            if ($esEdicion) {
                if ($id <= 0) {
                    echo json_encode(['success' => false, 'message' => 'Id no válido']);
                    exit;
                }
                $resultado = $tipoPlazaModel->update($id, $data);
                $mensajeOk = 'Tipo de plaza actualizado correctamente';
                $mensajeError = 'No se pudo actualizar el tipo de plaza';
            } else {
                $resultado = $tipoPlazaModel->create($data);
                $mensajeOk = 'Tipo de plaza creado correctamente';
                $mensajeError = 'No se pudo crear el tipo de plaza';
            }

            echo json_encode(['success' => $resultado, 'message' => $resultado ? $mensajeOk : $mensajeError]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                echo json_encode(['success' => false, 'message' => 'Conflicto de duplicidad: el nombre ya existe.']);
            } else {
                error_log('Error en TiposPlazaController::guardar: ' . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Error interno en la base de datos']);
            }
        }
        exit;
    }

    public function eliminar(PDO $pdo): void {
        $this->requierePermisoApi('TIPOS_PLAZA');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Id no válido']);
            exit;
        }

        require_once __DIR__ . '/../models/TipoPlaza.php';
        $tipoPlazaModel = new TipoPlaza($pdo);

        $resultado = $tipoPlazaModel->delete($id);
        echo json_encode(['success' => $resultado, 'message' => $resultado ? 'Tipo de plaza desactivado correctamente' : 'No se pudo desactivar el tipo de plaza']);
        exit;
    }

    public function restaurar(PDO $pdo): void {
        $this->requierePermisoApi('TIPOS_PLAZA');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método no permitido']);
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Id no válido']);
            exit;
        }

        require_once __DIR__ . '/../models/TipoPlaza.php';
        $tipoPlazaModel = new TipoPlaza($pdo);

        $resultado = $tipoPlazaModel->restore($id);
        echo json_encode(['success' => $resultado, 'message' => $resultado ? 'Tipo de plaza restaurado correctamente' : 'No se pudo restaurar el tipo de plaza']);
        exit;
    }
}
