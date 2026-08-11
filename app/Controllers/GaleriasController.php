<?php
require_once __DIR__ . '/../Models/Galeria.php';
require_once __DIR__ . '/../Models/Obra.php';

class GaleriasController {
    private Galeria $galeriaModel;
    private Obra $obraModel;

    public function __construct() {
        $this->galeriaModel = new Galeria();
        $this->obraModel = new Obra();
    }

    /**
     * Catálogo público de galerías virtuales publicadas.
     */
    public function index() {
        $db = Database::getConnection();
        $sql = "SELECT g.*, u.nombre AS artista_nombre, u.apellido AS artista_apellido, 
                       p.foto_perfil, COUNT(o.id) AS total_obras
                FROM galerias g
                INNER JOIN usuarios u ON g.artista_id = u.id
                LEFT JOIN perfiles_artistas p ON u.id = p.usuario_id
                LEFT JOIN obras o ON g.id = o.galeria_id
                WHERE g.estado = 'publicada'
                GROUP BY g.id
                ORDER BY g.creado_en DESC";
        
        $galerias = $db->query($sql)->fetchAll();

        $data = [
            'titulo' => 'Explorar Galerías Virtuales 3D - ArteEnVivo',
            'galerias' => $galerias
        ];

        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/galerias/catalogo.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    /**
     * Carga el entorno 3D interactivo para recorrer una galería específica.
     */
    public function ver(int $id) {
        $db = Database::getConnection();
        
        // Consultar la galería
        $stmtG = $db->prepare("SELECT g.*, u.nombre AS artista_nombre, u.apellido AS artista_apellido 
                               FROM galerias g 
                               INNER JOIN usuarios u ON g.artista_id = u.id 
                               WHERE g.id = :id AND g.estado = 'publicada' LIMIT 1");
        $stmtG->execute([':id' => $id]);
        $galeria = $stmtG->fetch();

        if (!$galeria) {
            header("HTTP/1.0 404 Not Found");
            require_once __DIR__ . '/../Views/errors/404.php';
            exit;
        }

        // Consultar obras asociadas a esta galería
        $stmtO = $db->prepare("SELECT * FROM obras WHERE galeria_id = :galeria_id AND estado = 'disponible'");
        $stmtO->execute([':galeria_id' => $id]);
        $obras = $stmtO->fetchAll();

        $data = [
            'titulo' => htmlspecialchars($galeria['titulo']) . ' - Exposición 3D',
            'galeria' => $galeria,
            'obras' => $obras
        ];

        // Cargar vista dedicada 3D (Renderiza pantalla completa)
        require_once __DIR__ . '/../Views/galerias/sala3d.php';
    }

    /**
     * Procesa la intención de compra/contacto de un visitante por una obra.
     */
    public function solicitarCompra() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . URL_BASE . "/galerias");
            exit;
        }

        require_once __DIR__ . '/../helpers/auth_helper.php';
        requerirAutenticacion();

        $obraId = filter_input(INPUT_POST, 'obra_id', FILTER_VALIDATE_INT);
        $mensaje = trim(filter_input(INPUT_POST, 'mensaje', FILTER_SANITIZE_SPECIAL_CHARS));
        $compradorId = $_SESSION['usuario_id'];

        if ($obraId) {
            $db = Database::getConnection();
            $sql = "INSERT INTO solicitudes_compra (obra_id, comprador_id, mensaje, estado) 
                    VALUES (:obra_id, :comprador_id, :mensaje, 'pendiente')";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':obra_id' => $obraId,
                ':comprador_id' => $compradorId,
                ':mensaje' => $mensaje
            ]);

            $_SESSION['mensaje_exito'] = "Tu solicitud ha sido enviada al artista. Se pondrá en contacto contigo pronto.";
        }

        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }



    // En app/Controllers/GaleriasController.php

    public function crear() {
        require_once __DIR__ . '/../helpers/auth_helper.php';
        requerirRol(['Artista', 'Administrador']);

        $data = ['titulo' => 'Crear Nueva Galería Virtual'];

        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/galerias/crear.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    public function store() {
        require_once __DIR__ . '/../helpers/auth_helper.php';
        requerirRol(['Artista', 'Administrador']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = trim(filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS));
            $descripcion = trim(filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_SPECIAL_CHARS));
            $plantilla = $_POST['plantilla_3d'] ?? 'galeria_clasica_3d';

            // Aquí se asigna el artista autenticado en la sesión
            $artistaId = $_SESSION['usuario_id'];

            $sql = "INSERT INTO galerias (artista_id, titulo, descripcion, plantilla_3d, estado) 
                    VALUES (:artista_id, :titulo, :descripcion, :plantilla_3d, 'publicada')";
            
            $db = Database::getConnection();
            $stmt = $db->prepare($sql);
            $exito = $stmt->execute([
                ':artista_id'   => $artistaId,
                ':titulo'       => $titulo,
                ':descripcion'  => $descripcion,
                ':plantilla_3d' => $plantilla
            ]);

            if ($exito) {
                $_SESSION['mensaje_exito'] = "Galería creada y asignada a tu perfil con éxito.";
                header("Location: " . URL_ROUTE . "galerias");
                exit;
            }
        }
    }

    
}