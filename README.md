Actúa como un Arquitecto de Software y Desarrollador Full-Stack Senior experto en PHP (nativo / POO), MySQL, HTML5 y CSS3 (sin frameworks externos salvo Bootstrap/Tailwind si es necesario).

Tu objetivo es guiarme paso a paso en el desarrollo de un sistema web bajo el patrón Arquitectura Modelo-Vista-Controlador (MVC) en PHP para la siguiente idea de negocio:

---
[DATOS DEL PROYECTO]
• Nombre: ArteEnVivo
• Descripción: Plataforma web que permite a artistas independientes (pintores, fotógrafos, escultores, ilustradores, músicos) crear galerías virtuales e interactuar con visitantes para promocionar y vender sus obras.
• Justificación: Superar las barreras económicas de las galerías físicas y la falta de experiencia inmersiva en las redes sociales tradicionales.
---

Requiero que estructures la entrega del proyecto mediante un PLAN POR FASES PROGRESIVAS. Para cada fase, debes proporcionar explicaciones claras, estructura de archivos y el código fuente completo, funcional y comentado.

Por favor, divide el desarrollo en las siguientes fases (espera mi confirmación para pasar de una fase a otra o genera la Fase 1 primero):

FASE 1: DISEÑO Y SCRIPT DE LA BASE DE DATOS (MySQL)
1. Modelo Entidad-Relación estructurado y normalizado (Tablas sugeridas: usuarios, roles, artistas, obras, galerías_virtuales, categorias, transacciones/ventas).
2. Script SQL completo con:
   - CREACIÓN de la BD `arte_en_vivo_db`.
   - Definición de tablas con Llaves Primarias (PK), Llaves Foráneas (FK) e Índices.
   - Restricciones (`ON DELETE CASCADE` / `SET NULL` según aplique).
   - Inserción de datos de prueba (`INSERT INTO`) para roles, usuarios demo y categorías.

FASE 2: ARQUITECTURA MVC Y CONFIGURACIÓN INICIAL
1. Estructura de carpetas limpia y estándar para MVC en PHP nativo (ejemplo: `/config`, `/app/controllers`, `/app/models`, `/app/views`, `/public`).
2. Archivo de conexión a la BD mediante PDO (`Database.php`) con manejo de excepciones.
3. Enrutador básico o configuración del controlador frontal (`index.php` + `.htaccess`).
4. Plantilla base (Layout) en HTML5 + CSS3 receptivo para las Vistas.

FASE 3: MÓDULO DE AUTENTICACIÓN Y SEGURIDAD
1. Proceso de Login y Registro diferenciado (Visitante vs. Artista vs. Administrador).
2. Manejo de sesiones seguras (`session_start()`, verificación de roles).
3. Hash de contraseñas con `password_hash()` y `password_verify()`.

FASE 4: MÓDULOS CRUD PRINCIPALES
1. CRUD de Usuarios / Perfiles de Artista.
2. CRUD de Obras de Arte (subida de archivos/imágenes, categorías, precios, estado).
3. CRUD de Galerías Virtuales (asociación de obras a una galería).

FASE 5: MÓDULO PÚBLICO E INTEGRACIÓN DE EXPERIENCIA VIRTUAL
1. Catálogo/Explore público de galerías y obras.
2. Integración o plantilla base HTML/JS para la visualización 3D / inmersiva (por ejemplo, canvas HTML5, Three.js básico o visores incrustados).
3. Flujo interactivo para contacto/compra de obras.

REGLAS DE DESARROLLO Y CALIDAD:
- Usa PHP Orientado a Objetos (POO) y PDO con Sentencias Preparadas (`prepare`, `execute`) para prevenir Inyección SQL.
- Valida y desinfecta todas las entradas de datos en el servidor.
- Mantén una separación estricta de responsabilidades: el Modelo maneja datos, el Controlador la lógica de negocio y la Vista solo el despliegue HTML.
- Incluye estilos CSS limpios, modernos y adaptables a dispositivos móviles.

Para comenzar, por favor entrega únicamente la FASE 1 completa. Espera mi retroalimentación antes de proceder con la Fase 2.

arte-en-vivo/
│
├── app/
│   ├── Controllers/
│   │   ├── HomeController.php
│   │   └── AuthController.php
│   ├── Models/
│   │   └── Usuario.php
│   └── Views/
│       ├── layouts/
│       │   ├── header.php
│       │   └── footer.php
│       ├── home/
│       │   └── index.php
│       └── errors/
│           └── 404.php
│
├── config/
│   ├── config.php
│   └── Database.php
│
├── public/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── main.js
│   ├── uploads/
│   │   ├── obras/
│   │   └── perfiles/
│   └── index.php           <-- Controlador Frontal (Front Controller)
│
├── .htaccess               <-- Reescritura de URLs para la raíz
└── README.md