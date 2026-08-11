<section class="catalogo-header">
    <h1>Directorio de Artistas</h1>
    <p>Conoce a los creadores e independientes que exponen sus obras en ArteEnVivo.</p>
</section>

<div class="galerias-grid">
    <?php if (empty($data['artistas'])): ?>
        <p class="text-center span-full">No hay artistas registrados aún.</p>
    <?php else: ?>
        <?php foreach ($data['artistas'] as $artista): ?>
            <div class="card-galeria">
                <div class="card-body">
                    <h3><?= htmlspecialchars($artista['nombre'] . ' ' . $artista['apellido']) ?></h3>
                    <p class="artista-tag">🎨 <?= htmlspecialchars($artista['especialidad'] ?? 'Artista Plástico') ?></p>
                    <p class="desc"><?= htmlspecialchars($artista['biografia'] ?? 'Sin biografía disponible.') ?></p>
                    <div class="card-footer">
                        <a href="mailto:<?= htmlspecialchars($artista['email']) ?>" class="btn btn-secondary">Contactar</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>