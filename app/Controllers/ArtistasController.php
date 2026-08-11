<?php
require_once __DIR__ . '/../../config/Database.php';

class ArtistasController {
    public function index() {
        $db = Database::getConnection();
        $sql = "SELECT u.nombre, u.apellido, u.email, p.biografia, p.especialidad, p.foto_perfil 
                FROM usuarios u 
                LEFT JOIN perfiles_artistas p ON u.id = p.usuario_id 
                WHERE u.rol_id = 2 AND u.estado = 'activo'";
        
        $artistas = $db->query($sql)->fetchAll();

        $data = [
            'titulo' => 'Directorio de Artistas - ArteEnVivo',
            'artistas' => $artistas
        ];

        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/artistas/index.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }
}