<?php

class HomeController {
    public function index() {
        $data = [
            'titulo' => 'Bienvenido a ArteEnVivo',
            'descripcion' => 'Explora galerías virtuales 3D de artistas independientes de todo el mundo.'
        ];

        // Cargar vistas
        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/home/index.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }
}