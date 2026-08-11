<div class="auth-card">
    <h2>Crear Cuenta en ArteEnVivo</h2>

    <?php if (!empty($data['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($data['error']) ?></div>
    <?php endif; ?>

    <!-- Usar URL_ROUTE en el store -->
    <form action="<?= URL_ROUTE ?>auth/store" method="POST" class="form-auth">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>

        <div class="form-group">
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required>
        </div>

        <div class="form-group">
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Contraseña (Mínimo 6 caracteres):</label>
            <input type="password" id="password" name="password" required minlength="6">
        </div>

        <div class="form-group">
            <label for="rol_id">Tipo de Usuario:</label>
            <select name="rol_id" id="rol_id" onchange="toggleEspecialidad(this.value)">
                <option value="3">Visitante / Comprador</option>
                <option value="2">Artista / Expositor</option>
            </select>
        </div>

        <div class="form-group" id="group-especialidad" style="display: none;">
            <label for="especialidad">Especialidad Artística:</label>
            <input type="text" id="especialidad" name="especialidad" placeholder="Ej: Pintor, Fotógrafo, Escultor">
        </div>

        <button type="submit" class="btn btn-primary btn-block">Registrarse</button>
    </form>

    <!-- Usar URL_ROUTE para ir a login -->
    <p class="auth-footer">
        ¿Ya tienes cuenta? <a href="<?= URL_ROUTE ?>auth/login">Inicia sesión</a>
    </p>
</div>

<script>
function toggleEspecialidad(val) {
    const group = document.getElementById('group-especialidad');
    group.style.display = (val === '2') ? 'block' : 'none';
}
</script>