<?php

declare(strict_types=1);

class TipoPlaza {

    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAll(): array {
        $stmt = $this->pdo->query("
            SELECT id, nombre, precio_hora, capacidad_total, espacios_ocupados, activo, created_at, updated_at
            FROM tipo_plaza WHERE activo = 1 ORDER BY nombre ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllInactive(): array {
        $stmt = $this->pdo->query("
            SELECT id, nombre, precio_hora, capacidad_total, espacios_ocupados, activo, created_at, updated_at
            FROM tipo_plaza WHERE activo = 0 ORDER BY nombre ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Usado por el módulo PARQUEO para poblar el select de tipos disponibles para nuevos ingresos.
     * Solo tipos activos con al menos un espacio libre.
     */
    public function getActivosConEspacio(): array {
        $stmt = $this->pdo->query("
            SELECT id, nombre, precio_hora, capacidad_total, espacios_ocupados,
                   (capacidad_total - espacios_ocupados) AS espacios_libres
            FROM tipo_plaza
            WHERE activo = 1 AND espacios_ocupados < capacidad_total
            ORDER BY nombre ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): array|false {
        $stmt = $this->pdo->prepare("
            SELECT id, nombre, precio_hora, capacidad_total, espacios_ocupados, activo, created_at, updated_at
            FROM tipo_plaza WHERE id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByNombre(string $nombre): array|false {
        $stmt = $this->pdo->prepare("
            SELECT id, nombre, precio_hora, capacidad_total, espacios_ocupados, activo
            FROM tipo_plaza WHERE nombre = ?
        ");
        $stmt->execute([$nombre]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * espacios_ocupados NUNCA se recibe del usuario: siempre inicia en 0 al crear.
     */
    public function create(array $data): bool {
        $stmt = $this->pdo->prepare("
            INSERT INTO tipo_plaza (nombre, precio_hora, capacidad_total, espacios_ocupados)
            VALUES (:nombre, :precio_hora, :capacidad_total, 0)
        ");
        return $stmt->execute([
            ':nombre'          => trim((string)$data['nombre']),
            ':precio_hora'     => (float)$data['precio_hora'],
            ':capacidad_total' => (int)$data['capacidad_total'],
        ]);
    }

    /**
     * espacios_ocupados nunca se modifica aquí; solo lo tocan ingresar()/finalizar() de Parking.
     */
    public function update(int $id, array $data): bool {
        $stmt = $this->pdo->prepare("
            UPDATE tipo_plaza
            SET nombre = :nombre, precio_hora = :precio_hora, capacidad_total = :capacidad_total
            WHERE id = :id
        ");
        return $stmt->execute([
            ':nombre'          => trim((string)$data['nombre']),
            ':precio_hora'     => (float)$data['precio_hora'],
            ':capacidad_total' => (int)$data['capacidad_total'],
            ':id'              => $id,
        ]);
    }

    /**
     * Los tipos usados históricamente no se eliminan físicamente; se desactivan.
     */
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("UPDATE tipo_plaza SET activo = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function restore(int $id): bool {
        $stmt = $this->pdo->prepare("UPDATE tipo_plaza SET activo = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function existeNombre(string $nombre, ?int $excludeId = null): bool {
        if ($excludeId !== null) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM tipo_plaza WHERE nombre = ? AND id != ?");
            $stmt->execute([$nombre, $excludeId]);
        } else {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM tipo_plaza WHERE nombre = ?");
            $stmt->execute([$nombre]);
        }
        return (int)$stmt->fetchColumn() > 0;
    }
}
