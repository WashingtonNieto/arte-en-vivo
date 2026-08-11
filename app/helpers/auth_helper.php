<?php

/**
 * Comprueba si la sesión del usuario está activa.
 */
function estaAutenticado(): bool {
    return isset($_SESSION['usuario_id']);
}

/**
 * Restringe el acceso solo a usuarios logueados.
 */
function requerirAutenticacion(): void {
    if (!estaAutenticado()) {
        $_SESSION['error_auth'] = "Debes iniciar sesión para acceder a esta sección.";
        header("Location: " . URL_BASE . "/auth/login");
        exit;
    }
}

/**
 * Restringe el acceso por roles específicos.
 * Ejemplo: requerirRol(['Administrador', 'Artista']);
 */
function requerirRol(array $rolesPermitidos): void {
    requerirAutenticacion();

    if (!in_array($_SESSION['rol_nombre'], $rolesPermitidos)) {
        header("HTTP/1.1 403 Forbidden");
        die("Acceso Denegado: No tienes permisos suficientes para acceder a este recurso.");
    }
}