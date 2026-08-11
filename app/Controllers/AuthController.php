<?php
require_once __DIR__ . '/../Models/Usuario.php';

class AuthController {
    private Usuario $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function login() {
        if (isset($_SESSION['usuario_id'])) {
            header("Location: " . URL_ROUTE);
            exit;
        }

        $error = $_SESSION['error_auth'] ?? null;
        unset($_SESSION['error_auth']);

        $data = ['titulo' => 'Iniciar Sesión - ArteEnVivo', 'error' => $error];

        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/auth/login.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . URL_ROUTE . "auth/login");
            exit;
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (!$email || empty($password)) {
            $_SESSION['error_auth'] = "Por favor ingrese un correo y contraseña válidos.";
            header("Location: " . URL_ROUTE . "auth/login");
            exit;
        }

        $usuario = $this->usuarioModel->obtenerPorEmail($email);

        if ($usuario && password_verify($password, $usuario['password'])) {
            if ($usuario['estado'] !== 'activo') {
                $_SESSION['error_auth'] = "Tu cuenta se encuentra inactiva o bloqueada.";
                header("Location: " . URL_ROUTE . "auth/login");
                exit;
            }

            session_regenerate_id(true);

            $_SESSION['usuario_id']     = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'] . ' ' . $usuario['apellido'];
            $_SESSION['usuario_email']  = $usuario['email'];
            $_SESSION['rol_id']         = $usuario['rol_id'];
            $_SESSION['rol_nombre']     = $usuario['rol_nombre'];

            header("Location: " . URL_ROUTE);
            exit;
        } else {
            $_SESSION['error_auth'] = "Credenciales incorrectas. Verifique correo y contraseña.";
            header("Location: " . URL_ROUTE . "auth/login");
            exit;
        }
    }

    public function registro() {
        if (isset($_SESSION['usuario_id'])) {
            header("Location: " . URL_ROUTE);
            exit;
        }

        $error = $_SESSION['error_auth'] ?? null;
        unset($_SESSION['error_auth']);

        $data = ['titulo' => 'Registro de Usuario - ArteEnVivo', 'error' => $error];

        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/auth/registro.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . URL_ROUTE . "auth/registro");
            exit;
        }

        $nombre    = trim(filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_SPECIAL_CHARS));
        $apellido  = trim(filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_SPECIAL_CHARS));
        $email     = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password  = $_POST['password'] ?? '';
        $rol_id    = (int)($_POST['rol_id'] ?? 3);
        $especialidad = trim(filter_input(INPUT_POST, 'especialidad', FILTER_SANITIZE_SPECIAL_CHARS));

        if (!$nombre || !$apellido || !$email || strlen($password) < 6) {
            $_SESSION['error_auth'] = "Todos los campos son obligatorios. La contraseña debe tener mínimo 6 caracteres.";
            header("Location: " . URL_ROUTE . "auth/registro");
            exit;
        }

        if ($this->usuarioModel->obtenerPorEmail($email)) {
            $_SESSION['error_auth'] = "El correo electrónico ya está registrado.";
            header("Location: " . URL_ROUTE . "auth/registro");
            exit;
        }

        $exito = $this->usuarioModel->registrar([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'password' => $password,
            'rol_id' => in_array($rol_id, [2, 3]) ? $rol_id : 3,
            'especialidad' => $especialidad
        ]);

        if ($exito) {
            $_SESSION['mensaje_exito'] = "Cuenta creada correctamente. Ya puedes iniciar sesión.";
            header("Location: " . URL_ROUTE . "auth/login");
            exit;
        } else {
            $_SESSION['error_auth'] = "Ocurrió un error al procesar el registro. Inténtelo más tarde.";
            header("Location: " . URL_ROUTE . "auth/registro");
            exit;
        }
    }

    public function logout() {
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
        header("Location: " . URL_ROUTE . "auth/login");
        exit;
    }
}