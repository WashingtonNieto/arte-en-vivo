<?php
// Configuración de la Base de Datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'arte_en_vivo_db');
define('DB_CHARSET', 'utf8mb4');

// Ruta base para recursos estáticos (CSS, JS, imágenes)
define('URL_BASE', 'http://localhost/arte-en-vivo/public');

// Ruta base para el enrutador de enlaces
define('URL_ROUTE', 'http://localhost/arte-en-vivo/public/index.php?url=');

define('APP_NAME', 'ArteEnVivo - Galerías Virtuales');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);