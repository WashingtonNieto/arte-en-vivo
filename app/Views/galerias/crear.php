<div class="form-container">
    <h2>Crear Nueva Galería Virtual 3D</h2>

    <form action="<?= URL_ROUTE ?>galerias/store" method="POST" class="form-grid">
        <div class="form-group span-2">
            <label for="titulo">Título de la Galería *</label>
            <input type="text" id="titulo" name="titulo" required placeholder="Ej: Exposición Expresionista 2026">
        </div>

        <div class="form-group span-2">
            <label for="plantilla_3d">Ambiente / Recinto 3D</label>
            <select name="plantilla_3d" id="plantilla_3d">
                <option value="galeria_clasica_3d">Sala Clásica Rectangular</option>
                <option value="galeria_moderna_3d">Galería Abierta Minimalista</option>
            </select>
        </div>

        <div class="form-group span-2">
            <label for="descripcion">Descripción de la Exposición</label>
            <textarea id="descripcion" name="descripcion" rows="4"></textarea>
        </div>

        <div class="form-group span-2">
            <button type="submit" class="btn btn-primary">Crear y Publicar Galería</button>
            <a href="<?= URL_ROUTE ?>galerias" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>