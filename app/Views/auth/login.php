<div class="auth-card">
    <h2>Iniciar Sesión</h2>
    
    <?php if (!empty($data['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($data['error']) ?></div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['mensaje_exito'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['mensaje_exito']) ?></div>
        <?php unset($_SESSION['mensaje_exito']); ?>
    <?php endif; ?>

    <!-- Usar URL_ROUTE en la acción del formulario -->
    <form action="<?= URL_ROUTE ?>auth/authenticate" method="POST" class="form-auth">
        <div class="form-group">
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required placeholder="tu@email.com">
        </div>

        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required placeholder="••••••••">
        </div>

        <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
    </form>

    <!-- Usar URL_ROUTE para ir a registro -->
    <p class="auth-footer">
        ¿No tienes una cuenta? <a href="<?= URL_ROUTE ?>auth/registro">Regístrate aquí</a>
    </p>
</div>