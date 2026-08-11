<section class="hero">
    <h1><?= htmlspecialchars($data['titulo']) ?></h1>
    <p><?= htmlspecialchars($data['descripcion']) ?></p>
    <div class="hero-actions">
        <a href="<?= URL_ROUTE ?>galerias" class="btn btn-primary">Recorrer Galerías 3D</a>
        <a href="<?= URL_ROUTE ?>auth/registro" class="btn btn-secondary">Exponer mis Obras</a>
    </div>
</section>