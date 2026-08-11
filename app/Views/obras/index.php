<div class="panel-header">
    <h2>Mis Obras de Arte</h2>
    <a href="<?= URL_ROUTE ?>obras/crear" class="btn btn-primary">+ Publicar Nueva Obra</a>
</div>

<?php if (!empty($_SESSION['mensaje_exito'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['mensaje_exito']) ?></div>
    <?php unset($_SESSION['mensaje_exito']); ?>
<?php endif; ?>

<div class="table-responsive">
    <table class="data-table">
        <thead>
            <tr>
                <th>Vista Previa</th>
                <th>Título</th>
                <th>Categoría</th>
                <th>Galería Asignada</th>
                <th>Precio</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data['obras'])): ?>
                <tr>
                    <td colspan="7" class="text-center">No has publicado obras aún.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($data['obras'] as $obra): ?>
                    <tr>
                        <td>
                            <img src="<?= URL_BASE ?>/uploads/obras/<?= htmlspecialchars($obra['imagen_archivo']) ?>" 
                                 alt="<?= htmlspecialchars($obra['titulo']) ?>" class="thumb-img">
                        </td>
                        <td><strong><?= htmlspecialchars($obra['titulo']) ?></strong></td>
                        <td><?= htmlspecialchars($obra['categoria_nombre']) ?></td>
                        <td><?= htmlspecialchars($obra['galeria_titulo'] ?? 'Sin asignar') ?></td>
                        <td>$<?= number_format($obra['precio'], 2) ?> USD</td>
                        <td><span class="badge badge-<?= $obra['estado'] ?>"><?= ucfirst($obra['estado']) ?></span></td>
                        <td>
                            <a href="<?= URL_ROUTE ?>obras/editar/<?= $obra['id'] ?>" class="btn-sm btn-secondary">Editar</a>
                            <a href="<?= URL_ROUTE ?>obras/eliminar/<?= $obra['id'] ?>" 
                               class="btn-sm btn-danger" 
                               onclick="return confirm('¿Confirmas que deseas eliminar esta obra?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>