<?php
require_once __DIR__ . '/../../config/Database.php';

class Usuario {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Busca un usuario por su correo electrónico.
     */
    public function obtenerPorEmail(string $email): ?array {
        $sql = "SELECT u.*, r.nombre AS rol_nombre 
                FROM usuarios u 
                INNER JOIN roles r ON u.rol_id = r.id 
                WHERE u.email = :email LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch();

        return $usuario ?: null;
    }

    /**
     * Registra un nuevo usuario en la base de datos con contraseña encriptada (BCRYPT).
     */
    public function registrar(array $datos): bool {
        try {
            $this->db->beginTransaction();

            // Hash de contraseña con BCRYPT
            $passwordHash = password_hash($datos['password'], PASSWORD_BCRYPT);

            $sqlUsuario = "INSERT INTO usuarios (rol_id, nombre, apellido, email, password, estado) 
                           VALUES (:rol_id, :nombre, :apellido, :email, :password, 'activo')";
            
            $stmtUser = $this->db->prepare($sqlUsuario);
            $stmtUser->execute([
                ':rol_id'   => $datos['rol_id'],
                ':nombre'   => $datos['nombre'],
                ':apellido' => $datos['apellido'],
                ':email'    => $datos['email'],
                ':password' => $passwordHash
            ]);

            $usuarioId = $this->db->lastInsertId();

            // Si el usuario se registra como Artista (rol_id = 2), se genera su perfil base
            if ((int)$datos['rol_id'] === 2) {
                $sqlPerfil = "INSERT INTO perfiles_artistas (usuario_id, especialidad) 
                              VALUES (:usuario_id, :especialidad)";
                $stmtPerfil = $this->db->prepare($sqlPerfil);
                $stmtPerfil->execute([
                    ':usuario_id'  => $usuarioId,
                    ':especialidad' => $datos['especialidad'] ?? 'Artista General'
                ]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error en Usuario::registrar -> " . $e->getMessage());
            return false;
        }
    }
}