<?php
require_once __DIR__ . '/../../config/Database.php';

class Obra {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Obtiene todas las obras de un artista específico con nombres de categoría y galería.
     */
    public function obtenerPorArtista(int $artistaId): array {
        $sql = "SELECT o.*, c.nombre AS categoria_nombre, g.titulo AS galeria_titulo 
                FROM obras o 
                INNER JOIN categorias c ON o.categoria_id = c.id 
                LEFT JOIN galerias g ON o.galeria_id = g.id 
                WHERE o.artista_id = :artista_id 
                ORDER BY o.creado_en DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':artista_id' => $artistaId]);
        return $stmt->fetchAll();
    }

    /**
     * Obtiene una obra por ID verificando propiedad.
     */
    public function obtenerPorIdYArtista(int $id, int $artistaId): ?array {
        $sql = "SELECT * FROM obras WHERE id = :id AND artista_id = :artista_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id, ':artista_id' => $artistaId]);
        $obra = $stmt->fetch();
        return $obra ?: null;
    }

    /**
     * Registra una nueva obra de arte.
     */
    public function crear(array $datos): bool {
        $sql = "INSERT INTO obras (artista_id, categoria_id, galeria_id, titulo, descripcion, precio, imagen_archivo, tecnica, dimensiones, estado) 
                VALUES (:artista_id, :categoria_id, :galeria_id, :titulo, :descripcion, :precio, :imagen_archivo, :tecnica, :dimensiones, :estado)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':artista_id'     => $datos['artista_id'],
            ':categoria_id'   => $datos['categoria_id'],
            ':galeria_id'     => $datos['galeria_id'] ?: null,
            ':titulo'         => $datos['titulo'],
            ':descripcion'    => $datos['descripcion'],
            ':precio'         => $datos['precio'],
            ':imagen_archivo' => $datos['imagen_archivo'],
            ':tecnica'        => $datos['tecnica'],
            ':dimensiones'    => $datos['dimensiones'],
            ':estado'         => $datos['estado'] ?? 'disponible'
        ]);
    }

    /**
     * Actualiza la información de una obra existente.
     */
    public function actualizar(int $id, int $artistaId, array $datos): bool {
        $sql = "UPDATE obras 
                SET categoria_id = :categoria_id, 
                    galeria_id = :galeria_id, 
                    titulo = :titulo, 
                    descripcion = :descripcion, 
                    precio = :precio, 
                    tecnica = :tecnica, 
                    dimensiones = :dimensiones, 
                    estado = :estado";

        if (!empty($datos['imagen_archivo'])) {
            $sql .= ", imagen_archivo = :imagen_archivo";
        }

        $sql .= " WHERE id = :id AND artista_id = :artista_id";
        
        $params = [
            ':categoria_id' => $datos['categoria_id'],
            ':galeria_id'   => $datos['galeria_id'] ?: null,
            ':titulo'       => $datos['titulo'],
            ':descripcion'  => $datos['descripcion'],
            ':precio'       => $datos['precio'],
            ':tecnica'      => $datos['tecnica'],
            ':dimensiones'  => $datos['dimensiones'],
            ':estado'       => $datos['estado'],
            ':id'           => $id,
            ':artista_id'   => $artistaId
        ];

        if (!empty($datos['imagen_archivo'])) {
            $params[':imagen_archivo'] = $datos['imagen_archivo'];
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Elimina una obra de la base de datos.
     */
    public function eliminar(int $id, int $artistaId): bool {
        $sql = "DELETE FROM obras WHERE id = :id AND artista_id = :artista_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':artista_id' => $artistaId]);
    }

    /**
     * Obtiene el listado de categorías para selects.
     */
    public function obtenerCategorias(): array {
        $sql = "SELECT * FROM categorias ORDER BY nombre ASC";
        return $this->db->query($sql)->fetchAll();
    }
}