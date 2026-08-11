<?php
require_once __DIR__ . '/../../config/Database.php';

class Galeria {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Obtiene todas las galerías de un artista específico.
     */
    public function obtenerPorArtista(int $artistaId): array {
        $sql = "SELECT g.*, COUNT(o.id) AS total_obras 
                FROM galerias g 
                LEFT JOIN obras o ON g.id = o.galeria_id 
                WHERE g.artista_id = :artista_id 
                GROUP BY g.id 
                ORDER BY g.creado_en DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':artista_id' => $artistaId]);
        return $stmt->fetchAll();
    }

    /**
     * Obtiene una galería por su ID comprobando la propiedad del artista.
     */
    public function obtenerPorIdYArtista(int $id, int $artistaId): ?array {
        $sql = "SELECT * FROM galerias WHERE id = :id AND artista_id = :artista_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id, ':artista_id' => $artistaId]);
        $galeria = $stmt->fetch();
        return $galeria ?: null;
    }

    /**
     * Crea una nueva galería virtual.
     */
    public function crear(array $datos): bool {
        $sql = "INSERT INTO galerias (artista_id, titulo, descripcion, plantilla_3d, estado) 
                VALUES (:artista_id, :titulo, :descripcion, :plantilla_3d, :estado)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':artista_id'   => $datos['artista_id'],
            ':titulo'       => $datos['titulo'],
            ':descripcion'  => $datos['descripcion'],
            ':plantilla_3d' => $datos['plantilla_3d'] ?? 'galeria_clasica_3d',
            ':estado'       => $datos['estado'] ?? 'borrador'
        ]);
    }

    /**
     * Actualiza una galería existente.
     */
    public function actualizar(int $id, int $artistaId, array $datos): bool {
        $sql = "UPDATE galerias 
                SET titulo = :titulo, descripcion = :descripcion, plantilla_3d = :plantilla_3d, estado = :estado 
                WHERE id = :id AND artista_id = :artista_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':titulo'       => $datos['titulo'],
            ':descripcion'  => $datos['descripcion'],
            ':plantilla_3d' => $datos['plantilla_3d'],
            ':estado'       => $datos['estado'],
            ':id'           => $id,
            ':artista_id'   => $artistaId
        ]);
    }

    /**
     * Elimina una galería virtual.
     */
    public function eliminar(int $id, int $artistaId): bool {
        $sql = "DELETE FROM galerias WHERE id = :id AND artista_id = :artista_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':artista_id' => $artistaId]);
    }


    public function actualizarPersonalizacion(int $id, int $artistaId, array $datos): bool {
        $sql = "UPDATE galerias 
                SET color_paredes = :color_paredes, 
                    color_suelo = :color_suelo, 
                    tipo_iluminacion = :tipo_iluminacion, 
                    textura_suelo = :textura_suelo 
                WHERE id = :id AND artista_id = :artista_id";
                
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':color_paredes'     => $datos['color_paredes'],
            ':color_suelo'       => $datos['color_suelo'],
            ':tipo_iluminacion' => $datos['tipo_iluminacion'],
            ':textura_suelo'     => $datos['textura_suelo'],
            ':id'                => $id,
            ':artista_id'        => $artistaId
        ]);
    }


}