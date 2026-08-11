<?php
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/Database.php';

// Enrutador básico
$url = isset($_GET['url']) && !empty(trim($_GET['url'])) ? rtrim($_GET['url'], '/') : 'home';
$urlParams = explode('/', filter_var($url, FILTER_SANITIZE_URL));

// Definir Nombre del Controlador
$controllerName = ucfirst($urlParams[0]) . 'Controller';
$methodName = $urlParams[1] ?? 'index';

// Ruta al archivo del controlador
$controllerPath = __DIR__ . '/../app/Controllers/' . $controllerName . '.php';

if (file_exists($controllerPath)) {
    require_once $controllerPath;
    $controller = new $controllerName();

    if (method_exists($controller, $methodName)) {
        $params = array_slice($urlParams, 2);
        call_user_func_array([$controller, $methodName], $params);
    } else {
        header("HTTP/1.0 404 Not Found");
        require_once __DIR__ . '/../app/Views/errors/404.php';
    }
} else {
    header("HTTP/1.0 404 Not Found");
    require_once __DIR__ . '/../app/Views/errors/404.php';
}