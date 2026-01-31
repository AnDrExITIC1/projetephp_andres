DROP DATABASE IF EXISTS overwatch_manager;
CREATE DATABASE overwatch_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE overwatch_manager;

-- Tabla roles
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

-- Tabla usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
);

-- Tabla héroes
CREATE TABLE heroes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    rol ENUM('Tanque','Daño','Apoyo') NOT NULL,
    descripcion TEXT,
    imagen VARCHAR(255),
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabla habilidades
CREATE TABLE habilidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    heroe_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    tipo ENUM('Principal','Secundaria','Habilidad','Definitiva') NOT NULL,
    descripcion TEXT,
    enfriamiento DECIMAL(5,2),
    FOREIGN KEY (heroe_id) REFERENCES heroes(id) ON DELETE CASCADE
);

-- Tabla mapas
CREATE TABLE mapas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tipo ENUM('Control','Escolta','Híbrido','Empuje','Punto múltiple') NOT NULL,
    ubicacion VARCHAR(100),
    descripcion TEXT
);

-- Tabla valoraciones de héroes
CREATE TABLE valoraciones_heroes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    heroe_id INT NOT NULL,
    usuario_id INT NOT NULL,
    puntuacion INT NOT NULL CHECK (puntuacion BETWEEN 1 AND 5),
    comentario TEXT,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (heroe_id) REFERENCES heroes(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Datos iniciales roles
INSERT INTO roles (nombre) VALUES
('admin'),
('jugador');

-- Usuarios de prueba (hash de "password")
INSERT INTO usuarios (nombre, email, contrasena, rol_id) VALUES
('Administrador Overwatch', 'admin@overwatch.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
('Jugador 1', 'jugador1@overwatch.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2);

-- Héroes de ejemplo
INSERT INTO heroes (nombre, rol, descripcion, imagen) VALUES
('Reinhardt', 'Tanque', 'Tanque con un enorme escudo y martillo a dos manos.', 'reinhardt.jpg'),
('Tracer', 'Daño', 'Heroína muy móvil que puede retroceder en el tiempo.', 'tracer.jpg'),
('Mercy', 'Apoyo', 'Sanadora con capacidad de resurrección y aumento de daño.', 'mercy.jpg'),
('Winston', 'Tanque', 'Tanque saltarín con barrera de burbuja.', 'winston.jpg'),
('Genji', 'Daño', 'Ninja cibernético que puede desviar proyectiles.', 'genji.jpg');

-- Habilidades de ejemplo
INSERT INTO habilidades (heroe_id, nombre, tipo, descripcion, enfriamiento) VALUES
(1, 'Campo de barrera', 'Habilidad', 'Proyecta un gran escudo frontal para proteger al equipo.', 0),
(1, 'Carga', 'Habilidad', 'Carga hacia adelante y estampa a los enemigos contra la pared.', 10),
(2, 'Traslación', 'Habilidad', 'Se teletransporta una corta distancia hacia adelante.', 3),
(2, 'Regresión', 'Habilidad', 'Vuelve a una posición anterior en el tiempo.', 12),
(3, 'Bastón Caduceo', 'Principal', 'Cura o aumenta el daño de un aliado.', 0),
(3, 'Resurrección', 'Definitiva', 'Resucita a un aliado caído.', 30),
(4, 'Salto de impulso', 'Habilidad', 'Salta hacia una posición lejana.', 6),
(4, 'Proyector de barrera', 'Habilidad', 'Crea una barrera de burbuja protectora.', 13),
(5, 'Desviar', 'Habilidad', 'Devuelve los proyectiles al enemigo.', 8),
(5, 'Espada dragón', 'Definitiva', 'Desenvaina una espada letal de energía.', 0);

-- Mapas de ejemplo
INSERT INTO mapas (nombre, tipo, ubicacion, descripcion) VALUES
('King\'s Row', 'Híbrido', 'Londres', 'Mapa híbrido de ataque y escolta.'),
('Ilios', 'Control', 'Grecia', 'Mapa de control con pozos mortales.'),
('Ruta 66', 'Escolta', 'Estados Unidos', 'Mapa de escolta en el desierto.'),
('Nueva Junk City', 'Empuje', 'Australia', 'Mapa de empuje con robots.'),
('Suravasa', 'Punto múltiple', 'India', 'Mapa de puntos múltiples.');

-- Valoraciones de ejemplo
INSERT INTO valoraciones_heroes (heroe_id, usuario_id, puntuacion, comentario) VALUES
(1, 2, 5, 'Reinhardt es mi main, lo amo.'),
(2, 2, 4, 'Tracer es divertida pero difícil.'),
(3, 2, 5, 'Mercy salva partidas.'),
(4, 2, 3, 'Winston está bien para dive.'),
(5, 2, 5, 'Genji necesita curas.');
