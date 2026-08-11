<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['titulo'] ?></title>
    <style>
        body { margin: 0; overflow: hidden; background-color: #050505; font-family: sans-serif; color: #fff; }
        #canvas-container { width: 100vw; height: 100vh; display: block; }
        
        /* Controles e Interfaz superpuesta (HUD) */
        #hud {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 10;
            background: rgba(15, 23, 42, 0.85);
            padding: 15px 25px;
            border-radius: 8px;
            border: 1px solid #334155;
            backdrop-filter: blur(5px);
        }
        #hud h2 { margin: 0 0 5px 0; font-size: 1.2rem; color: #38bdf8; }
        #hud p { margin: 0; font-size: 0.85rem; color: #94a3b8; }
        
        .controls-info {
            position: absolute;
            bottom: 20px;
            left: 20px;
            background: rgba(0,0,0,0.7);
            padding: 10px 15px;
            border-radius: 6px;
            font-size: 0.8rem;
        }

        .btn-back {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 10;
            background: #0284c7;
            color: #fff;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        /* Modal para ver detalles y comprar */
        #modal-obra {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 100;
            background: #1e293b;
            padding: 25px;
            border-radius: 10px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 10px 25px rgba(0,0,0,0.8);
            border: 1px solid #38bdf8;
        }
        #modal-obra h3 { margin-top: 0; color: #38bdf8; }
        .modal-close { float: right; cursor: pointer; font-size: 1.2rem; color: #94a3b8; }
        .btn-compra { background: #10b981; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; width: 100%; font-weight: bold; margin-top: 10px; }
    </style>
    <!-- Cargamos Three.js y OrbitControls vía CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
</head>
<body>

    <div id="hud">
        <h2><?= htmlspecialchars($data['galeria']['titulo']) ?></h2>
        <p>Exposición de: <?= htmlspecialchars($data['galeria']['artista_nombre'] . ' ' . $data['galeria']['artista_apellido']) ?></p>
    </div>



    <!-- Cambia esto:
    <a href="<?= URL_BASE ?>/galerias" class="btn-back">⬅️ Salir de la Galería</a> -->

    <!-- Por esto: -->
    <a href="<?= URL_ROUTE ?>galerias" class="btn-back">⬅️ Salir de la Galería</a>


    <div class="controls-info">
        🖱️ <strong>Instrucciones:</strong> Clic izquierdo + Arrastrar para rotar cámara | Rueda para Zoom | Haz clic sobre un cuadro para examinarlo.
    </div>

    <!-- Modal interactivo al hacer clic en una obra -->
    <div id="modal-obra">
        <span class="modal-close" onclick="cerrarModal()">&times;</span>
        <h3 id="modal-titulo">Título Obra</h3>
        <p id="modal-tecnica" style="font-size: 0.85rem; color: #cbd5e1;"></p>
        <p id="modal-desc"></p>
        <p><strong style="color: #10b981; font-size: 1.2rem;" id="modal-precio">$0.00 USD</strong></p>

        <form action="<?= URL_BASE ?>/galerias/solicitarCompra" method="POST">
            <input type="hidden" name="obra_id" id="modal-obra-id">
            <textarea name="mensaje" placeholder="Escribe un mensaje o propuesta para el artista..." style="width: 100%; background: #0f172a; color: white; border: 1px solid #334155; padding: 8px; border-radius: 4px; box-sizing: border-box;" rows="3"></textarea>
            <button type="submit" class="btn-compra">Solicitar / Contactar Artista</button>
        </form>
    </div>

    <div id="canvas-container"></div>

    <script>
        // 1. Datos e inicialización de variables
        const obrasDatos = <?= json_encode($data['obras']) ?>;
        const urlBase = "<?= URL_BASE ?>";

        const container = document.getElementById('canvas-container');
        const scene = new THREE.Scene();
        scene.background = new THREE.Color(0x0a0a0c);

        const camera = new THREE.PerspectiveCamera(60, window.innerWidth / window.innerHeight, 0.1, 1000);
        camera.position.set(0, 2, 8);

        const renderer = new THREE.WebGLRenderer({ antialias: true });
        renderer.setSize(window.innerWidth, window.innerHeight);
        renderer.shadowMap.enabled = true;
        container.appendChild(renderer.domElement);

        const controls = new THREE.OrbitControls(camera, renderer.domElement);
        controls.enableDamping = true;
        controls.dampingFactor = 0.05;
        controls.maxPolarAngle = Math.PI / 2 - 0.01;

        // 2. Iluminación
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
        scene.add(ambientLight);

        const mainLight = new THREE.PointLight(0xffffff, 0.8, 20);
        mainLight.position.set(0, 5, 0);
        scene.add(mainLight);

        // 3. Estructura del Museo (Suelo y Paredes)
        const floorGeo = new THREE.PlaneGeometry(20, 20);
        const floorMat = new THREE.MeshStandardMaterial({ color: 0x1e1e24, roughness: 0.3 });
        const floor = new THREE.Mesh(floorGeo, floorMat);
        floor.rotation.x = -Math.PI / 2;
        scene.add(floor);

        const wallGeo = new THREE.PlaneGeometry(20, 6);
        const wallMat = new THREE.MeshStandardMaterial({ color: 0x2d3748, roughness: 0.8 });

        const backWall = new THREE.Mesh(wallGeo, wallMat);
        backWall.position.set(0, 3, -10);
        scene.add(backWall);

        const leftWall = new THREE.Mesh(wallGeo, wallMat);
        leftWall.position.set(-10, 3, 0);
        leftWall.rotation.y = Math.PI / 2;
        scene.add(leftWall);

        const rightWall = new THREE.Mesh(wallGeo, wallMat);
        rightWall.position.set(10, 3, 0);
        rightWall.rotation.y = -Math.PI / 2;
        scene.add(rightWall);

        // 4. Instancia ÚNICA de TextureLoader
        const textureLoader = new THREE.TextureLoader();
        const raycasterObjects = [];

        // Carga de Obras
        obrasDatos.forEach((obra, index) => {
            const imgPath = urlBase + '/uploads/obras/' + obra.imagen_archivo;

            textureLoader.load(imgPath, (texture) => {
                const aspect = texture.image.width / texture.image.height || 1;
                const canvasWidth = 2.5;
                const canvasHeight = canvasWidth / aspect;

                const artworkGeo = new THREE.BoxGeometry(canvasWidth, canvasHeight, 0.08);
                const artworkMat = new THREE.MeshStandardMaterial({ map: texture });
                const mesh = new THREE.Mesh(artworkGeo, artworkMat);

                let xPos = 0, yPos = 2.8, zPos = -9.8;
                let rotY = 0;

                if (index === 1) xPos = -4.5;
                else if (index === 2) xPos = 4.5;
                else if (index === 3) { xPos = -9.8; zPos = 0; rotY = Math.PI / 2; }
                else if (index === 4) { xPos = 9.8; zPos = 0; rotY = -Math.PI / 2; }

                mesh.position.set(xPos, yPos, zPos);
                mesh.rotation.y = rotY;

                mesh.userData = obra;
                scene.add(mesh);
                raycasterObjects.push(mesh);

                const spot = new THREE.SpotLight(0xffffff, 1.5, 12, Math.PI / 6, 0.5);
                spot.position.set(xPos, 5, zPos + (rotY === 0 ? 2 : 0));
                spot.target = mesh;
                scene.add(spot);
            });
        });

        // 5. Interacción con Raycaster
        const raycaster = new THREE.Raycaster();
        const mouse = new THREE.Vector2();

        window.addEventListener('click', (event) => {
            mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
            mouse.y = -(event.clientY / window.innerHeight) * 2 + 1;

            raycaster.setFromCamera(mouse, camera);
            const intersects = raycaster.intersectObjects(raycasterObjects);

            if (intersects.length > 0) {
                const obra = intersects[0].object.userData;
                document.getElementById('modal-titulo').innerText = obra.titulo;
                document.getElementById('modal-tecnica').innerText = "Técnica: " + (obra.tecnica || 'N/A') + " | Dim: " + (obra.dimensiones || 'N/A');
                document.getElementById('modal-desc').innerText = obra.descripcion || '';
                document.getElementById('modal-precio').innerText = '$' + parseFloat(obra.precio).toFixed(2) + ' USD';
                document.getElementById('modal-obra-id').value = obra.id;
                document.getElementById('modal-obra').style.display = 'block';
            }
        });

        function cerrarModal() {
            document.getElementById('modal-obra').style.display = 'none';
        }

        // Loop de renderizado
        function animate() {
            requestAnimationFrame(animate);
            controls.update();
            renderer.render(scene, camera);
        }
        animate();

        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });
    </script>   


</body>
</html>