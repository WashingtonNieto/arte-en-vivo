<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['titulo'] ?? APP_NAME ?></title>
    <!-- Carga del CSS con URL_BASE -->
    <link rel="stylesheet" href="<?= URL_BASE ?>/css/style.css">
</head>
<body>
    <header class="navbar">
        <div class="logo">
            <a href="<?= URL_ROUTE ?>">🎨 ArteEnVivo</a>
        </div>


        <nav>
            <ul>
                <li><a href="<?= URL_ROUTE ?>">Inicio</a></li>
                <li><a href="<?= URL_ROUTE ?>galerias">Galerías 3D</a></li>
                <li><a href="<?= URL_ROUTE ?>artistas">Artistas</a></li>
                
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <?php if (in_array($_SESSION['rol_nombre'], ['Artista', 'Administrador'])): ?>
                        <li><a href="<?= URL_ROUTE ?>obras">Mis Obras</a></li>
                    <?php endif; ?>
                    <li><a href="<?= URL_ROUTE ?>auth/logout" class="btn-secondary">Salir (<?= htmlspecialchars($_SESSION['usuario_nombre']) ?>)</a></li>
                <?php else: ?>
                    <li><a href="<?= URL_ROUTE ?>auth/login" class="btn-login">Ingresar</a></li>
                <?php endif; ?>
            </ul>
        </nav>


    </header>
    <main class="container">