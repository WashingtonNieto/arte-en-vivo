<?php
require_once __DIR__ . '/../Models/Obra.php';
require_once __DIR__ . '/../Models/Galeria.php';
require_once __DIR__ . '/../helpers/auth_helper.php';

class ObrasController {
    private Obra $obraModel;
    private Galeria $galeriaModel;

    public function __construct() {
        requerirRol(['Artista', 'Administrador']);
        $this->obraModel = new Obra();
        $this->galeriaModel = new Galeria();
    }

    /**
     * READ: Listado de obras del artista autenticado
     */
    public function index() {
        $artistaId = $_SESSION['usuario_id'];
        $obras = $this->obraModel->obtenerPorArtista($artistaId);

        $data = [
            'titulo' => 'Mis Obras de Arte - Panel Artista',
            'obras' => $obras
        ];

        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/obras/index.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    /**
     * CREATE (Vista): Formulario de registro de obra
     */
    public function crear() {
        $artistaId = $_SESSION['usuario_id'];
        $data = [
            'titulo' => 'Publicar Nueva Obra',
            'categorias' => $this->obraModel->obtenerCategorias(),
            'galerias' => $this->galeriaModel->obtenerPorArtista($artistaId)
        ];

        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/obras/crear.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    /**
     * CREATE (Proceso): Procesa y guarda la nueva obra
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . URL_ROUTE . "obras");
            exit;
        }

        $artistaId = $_SESSION['usuario_id'];
        $titulo = trim(filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS));
        $descripcion = trim(filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_SPECIAL_CHARS));
        $precio = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
        $categoriaId = filter_input(INPUT_POST, 'categoria_id', FILTER_VALIDATE_INT);
        $galeriaId = filter_input(INPUT_POST, 'galeria_id', FILTER_VALIDATE_INT);
        $tecnica = trim(filter_input(INPUT_POST, 'tecnica', FILTER_SANITIZE_SPECIAL_CHARS));
        $dimensiones = trim(filter_input(INPUT_POST, 'dimensiones', FILTER_SANITIZE_SPECIAL_CHARS));

        $nombreImagen = $this->procesarSubidaImagen($_FILES['imagen_archivo'] ?? null);

        if (!$nombreImagen) {
            $_SESSION['error_obra'] = "Error al subir la imagen. Formato debe ser JPG/PNG/WEBP (máx 5MB).";
            header("Location: " . URL_ROUTE . "obras/crear");
            exit;
        }

        $exito = $this->obraModel->crear([
            'artista_id'     => $artistaId,
            'categoria_id'   => $categoriaId,
            'galeria_id'     => $galeriaId,
            'titulo'         => $titulo,
            'descripcion'    => $descripcion,
            'precio'         => $precio ?: 0.00,
            'imagen_archivo' => $nombreImagen,
            'tecnica'        => $tecnica,
            'dimensiones'    => $dimensiones,
            'estado'         => 'disponible'
        ]);

        if ($exito) {
            $_SESSION['mensaje_exito'] = "Obra publicada con éxito.";
            header("Location: " . URL_ROUTE . "obras");
        } else {
            $_SESSION['error_obra'] = "Error al guardar la obra en la base de datos.";
            header("Location: " . URL_ROUTE . "obras/crear");
        }
        exit;
    }

    /**
     * UPDATE (Vista): Formulario de edición
     */
    public function editar(int $id) {
        $artistaId = $_SESSION['usuario_id'];
        $obra = $this->obraModel->obtenerPorIdYArtista($id, $artistaId);

        if (!$obra) {
            header("Location: " . URL_ROUTE . "obras");
            exit;
        }

        $data = [
            'titulo' => 'Editar Obra: ' . $obra['titulo'],
            'obra' => $obra,
            'categorias' => $this->obraModel->obtenerCategorias(),
            'galerias' => $this->galeriaModel->obtenerPorArtista($artistaId)
        ];

        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/obras/editar.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    /**
     * UPDATE (Proceso): Procesa la actualización
     */
    public function actualizar(int $id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . URL_ROUTE . "obras");
            exit;
        }

        $artistaId = $_SESSION['usuario_id'];
        $obraActual = $this->obraModel->obtenerPorIdYArtista($id, $artistaId);

        if (!$obraActual) {
            header("Location: " . URL_ROUTE . "obras");
            exit;
        }

        $titulo = trim(filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS));
        $descripcion = trim(filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_SPECIAL_CHARS));
        $precio = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
        $categoriaId = filter_input(INPUT_POST, 'categoria_id', FILTER_VALIDATE_INT);
        $galeriaId = filter_input(INPUT_POST, 'galeria_id', FILTER_VALIDATE_INT);
        $tecnica = trim(filter_input(INPUT_POST, 'tecnica', FILTER_SANITIZE_SPECIAL_CHARS));
        $dimensiones = trim(filter_input(INPUT_POST, 'dimensiones', FILTER_SANITIZE_SPECIAL_CHARS));
        $estado = trim(filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_SPECIAL_CHARS));

        $nombreImagen = null;
        if (!empty($_FILES['imagen_archivo']['name'])) {
            $nombreImagen = $this->procesarSubidaImagen($_FILES['imagen_archivo']);
            // Borrar la imagen anterior si se sube una nueva
            if ($nombreImagen && !empty($obraActual['imagen_archivo'])) {
                $oldFile = __DIR__ . '/../../public/uploads/obras/' . $obraActual['imagen_archivo'];
                if (file_exists($oldFile)) unlink($oldFile);
            }
        }

        $exito = $this->obraModel->actualizar($id, $artistaId, [
            'categoria_id'   => $categoriaId,
            'galeria_id'     => $galeriaId,
            'titulo'         => $titulo,
            'descripcion'    => $descripcion,
            'precio'         => $precio ?: 0.00,
            'imagen_archivo' => $nombreImagen,
            'tecnica'        => $tecnica,
            'dimensiones'    => $dimensiones,
            'estado'         => $estado
        ]);

        if ($exito) {
            $_SESSION['mensaje_exito'] = "Obra actualizada correctamente.";
            header("Location: " . URL_ROUTE . "obras");
        } else {
            $_SESSION['error_obra'] = "No se pudieron guardar los cambios.";
            header("Location: " . URL_ROUTE . "obras/editar/" . $id);
        }
        exit;
    }

    /**
     * DELETE: Borra el registro y su archivo de imagen físico
     */
    public function eliminar(int $id) {
        $artistaId = $_SESSION['usuario_id'];
        $obra = $this->obraModel->obtenerPorIdYArtista($id, $artistaId);

        if ($obra) {
            $filePath = __DIR__ . '/../../public/uploads/obras/' . $obra['imagen_archivo'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $this->obraModel->eliminar($id, $artistaId);
            $_SESSION['mensaje_exito'] = "Obra eliminada con éxito.";
        }

        header("Location: " . URL_ROUTE . "obras");
        exit;
    }

    /**
     * Procesa y valida subidas de archivos
     */
    private function procesarSubidaImagen(?array $archivo): ?string {
        if (!$archivo || $archivo['error'] !== UPLOAD_ERR_OK) return null;

        $exts = ['jpg', 'jpeg', 'png', 'webp'];
        $mimes = ['image/jpeg', 'image/png', 'image/webp'];

        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        $tipoMime = mime_content_type($archivo['tmp_name']);

        if (!in_array($extension, $exts) || !in_array($tipoMime, $mimes) || $archivo['size'] > 5 * 1024 * 1024) {
            return null;
        }

        $nombreUnico = md5(uniqid(rand(), true)) . '.' . $extension;
        $destino = __DIR__ . '/../../public/uploads/obras/' . $nombreUnico;

        return move_uploaded_file($archivo['tmp_name'], $destino) ? $nombreUnico : null;
    }
}