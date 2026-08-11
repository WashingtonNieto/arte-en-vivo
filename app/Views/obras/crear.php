<div class="form-container">
    <h2>Publicar Nueva Obra</h2>

    <?php if (!empty($_SESSION['error_obra'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error_obra']) ?></div>
        <?php unset($_SESSION['error_obra']); ?>
    <?php endif; ?>

    <form action="<?= URL_ROUTE ?>obras/store" method="POST" enctype="multipart/form-data" class="form-grid">
        <div class="form-group span-2">
            <label for="titulo">Título de la Obra *</label>
            <input type="text" id="titulo" name="titulo" required>
        </div>

        <div class="form-group">
            <label for="categoria_id">Categoría *</label>
            <select name="categoria_id" id="categoria_id" required>
                <?php foreach ($data['categorias'] as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="galeria_id">Exponer en Galería 3D (Opcional)</label>
            <select name="galeria_id" id="galeria_id">
                <option value="">-- Sin Galería / Colección Privada --</option>
                <?php foreach ($data['galerias'] as $gal): ?>
                    <option value="<?= $gal['id'] ?>"><?= htmlspecialchars($gal['titulo']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="precio">Precio (USD) *</label>
            <input type="number" step="0.01" id="precio" name="precio" required placeholder="0.00">
        </div>

        <div class="form-group">
            <label for="tecnica">Técnica de Arte</label>
            <input type="text" id="tecnica" name="tecnica" placeholder="Ej: Óleo sobre lienzo, Render 3D">
        </div>

        <div class="form-group">
            <label for="dimensiones">Dimensiones / Formato</label>
            <input type="text" id="dimensiones" name="dimensiones" placeholder="Ej: 100x80cm, 4K Digital">
        </div>

        <div class="form-group">
            <label for="imagen_archivo">Imagen/Lienzo de la Obra (JPG, PNG, WEBP máx 5MB) *</label>
            <input type="file" id="imagen_archivo" name="imagen_archivo" accept="image/*" required>
        </div>

        <div class="form-group span-2">
            <label for="descripcion">Descripción de la Obra</label>
            <textarea id="descripcion" name="descripcion" rows="4"></textarea>
        </div>

        <div class="form-group span-2">
            <button type="submit" class="btn btn-primary">Guardar y Publicar</button>
            <a href="<?= URL_ROUTE ?>obras" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>