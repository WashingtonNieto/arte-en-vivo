<div class="form-container">
    <h2>Editar Obra de Arte</h2>

    <?php if (!empty($_SESSION['error_obra'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error_obra']) ?></div>
        <?php unset($_SESSION['error_obra']); ?>
    <?php endif; ?>

    <form action="<?= URL_ROUTE ?>obras/actualizar/<?= $data['obra']['id'] ?>" method="POST" enctype="multipart/form-data" class="form-grid">
        <div class="form-group span-2">
            <label for="titulo">Título *</label>
            <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($data['obra']['titulo']) ?>" required>
        </div>

        <div class="form-group">
            <label for="categoria_id">Categoría *</label>
            <select name="categoria_id" id="categoria_id" required>
                <?php foreach ($data['categorias'] as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $data['obra']['categoria_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="galeria_id">Galería 3D Asignada</label>
            <select name="galeria_id" id="galeria_id">
                <option value="">-- Sin Galería / Colección Privada --</option>
                <?php foreach ($data['galerias'] as $gal): ?>
                    <option value="<?= $gal['id'] ?>" <?= $gal['id'] == $data['obra']['galeria_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($gal['titulo']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="precio">Precio (USD) *</label>
            <input type="number" step="0.01" id="precio" name="precio" value="<?= $data['obra']['precio'] ?>" required>
        </div>

        <div class="form-group">
            <label for="estado">Estado de la Obra</label>
            <select name="estado" id="estado">
                <option value="disponible" <?= $data['obra']['estado'] == 'disponible' ? 'selected' : '' ?>>Disponible</option>
                <option value="reservada" <?= $data['obra']['estado'] == 'reservada' ? 'selected' : '' ?>>Reservada</option>
                <option value="vendida" <?= $data['obra']['estado'] == 'vendida' ? 'selected' : '' ?>>Vendida</option>
                <option value="inactiva" <?= $data['obra']['estado'] == 'inactiva' ? 'selected' : '' ?>>Inactiva</option>
            </select>
        </div>

        <div class="form-group">
            <label for="tecnica">Técnica</label>
            <input type="text" id="tecnica" name="tecnica" value="<?= htmlspecialchars($data['obra']['tecnica']) ?>">
        </div>

        <div class="form-group">
            <label for="dimensiones">Dimensiones</label>
            <input type="text" id="dimensiones" name="dimensiones" value="<?= htmlspecialchars($data['obra']['dimensiones']) ?>">
        </div>

        <div class="form-group span-2">
            <label>Vista Actual:</label><br>
            <img src="<?= URL_BASE ?>/uploads/obras/<?= htmlspecialchars($data['obra']['imagen_archivo']) ?>" class="thumb-img" style="width:100px; height:100px;">
        </div>

        <div class="form-group span-2">
            <label for="imagen_archivo">Reemplazar Imagen (Opcional)</label>
            <input type="file" id="imagen_archivo" name="imagen_archivo" accept="image/*">
        </div>

        <div class="form-group span-2">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" rows="4"><?= htmlspecialchars($data['obra']['descripcion']) ?></textarea>
        </div>

        <div class="form-group span-2">
            <button type="submit" class="btn btn-primary">Actualizar Obra</button>
            <a href="<?= URL_ROUTE ?>obras" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>