<section class="catalogo-header">
    <h1>Exposiciones Virtuales 3D</h1>
    <p>Recorre las salas de arte desde tu navegador e interactúa con obras de artistas independientes.</p>
</section>

<?php if (!empty($_SESSION['mensaje_exito'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['mensaje_exito']) ?></div>
    <?php unset($_SESSION['mensaje_exito']); ?>
<?php endif; ?>

<div class="galerias-grid">
    <?php if (empty($data['galerias'])): ?>
        <p class="text-center span-full">No hay galerías publicadas disponibles en este momento.</p>
    <?php else: ?>
        <?php foreach ($data['galerias'] as $galeria): ?>
            <div class="card-galeria">
                <div class="card-header-3d">
                    <span class="badge-3d">🏛️ Entorno 3D</span>
                </div>
                <div class="card-body">
                    <h3><?= htmlspecialchars($galeria['titulo']) ?></h3>
                    <p class="artista-tag">Por: <strong><?= htmlspecialchars($galeria['artista_nombre'] . ' ' . $galeria['artista_apellido']) ?></strong></p>
                    <p class="desc"><?= htmlspecialchars(substr($galeria['descripcion'] ?? '', 0, 110)) ?>...</p>
                    <div class="card-footer">
                        <span class="obras-count">🖼️ <?= $galeria['total_obras'] ?> Obras expuestas</span>
                        <!-- Cambia esto: -->
                        <!-- <a href="<?= URL_BASE ?>/galerias/ver/<?= $galeria['id'] ?>" class="btn btn-primary">Entrar a la Galería</a> -->

                        <!-- Por esto: -->
                        <a href="<?= URL_ROUTE ?>galerias/ver/<?= $galeria['id'] ?>" class="btn btn-primary">Entrar a la Galería</a>


                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

