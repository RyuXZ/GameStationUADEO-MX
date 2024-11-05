CREATE DATABASE gamestationuadeo;
USE gamestationuadeo;

-- Crear tabla de usuarios que almacena información básica de cada usuario registrado en la plataforma
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- ID único para cada usuario, se incrementa automáticamente
    tipo_usuario INT (1) NOT NULL, -- Tipo de usuario (normal, moderador, admin)
    username VARCHAR(50) NOT NULL UNIQUE,  -- Nombre de usuario, debe ser único y no nulo
    email VARCHAR(100) NOT NULL UNIQUE,  -- Correo electrónico del usuario, debe ser único y no nulo
    password VARCHAR(255) NOT NULL,  -- Contraseña del usuario, se almacena en formato encriptado
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP  -- Fecha de registro del usuario, se establece automáticamente con la fecha y hora actual
);

-- Log para verificar la creación de la tabla de usuarios
SELECT 'Tabla usuarios creada exitosamente' AS log;

-- Crear tabla de temas del foro que contiene los temas o posts principales
CREATE TABLE temas (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- ID único para cada tema del foro, se incrementa automáticamente
    titulo VARCHAR(100) NOT NULL,  -- Título del tema, no nulo
    descripcion TEXT NOT NULL,  -- Descripción detallada del tema, no nulo
    categoria VARCHAR(50),  -- Categoría del tema para clasificación
    user_id INT,  -- ID del usuario que creó el tema, referencia a la tabla de usuarios
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,  -- Fecha de creación del tema, se establece automáticamente
    FOREIGN KEY (user_id) REFERENCES usuarios(id)  -- Llave foránea que vincula el tema con el usuario que lo creó
);

-- Log para verificar la creación de la tabla de temas
SELECT 'Tabla temas creada exitosamente' AS log;

-- Crear tabla de comentarios que almacena los comentarios hechos sobre los temas del foro
CREATE TABLE comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- ID único para cada comentario, se incrementa automáticamente
    comentario TEXT NOT NULL,  -- Texto del comentario, no nulo
    user_id INT,  -- ID del usuario que hizo el comentario, referencia a la tabla de usuarios
    tema_id INT,  -- ID del tema en el cual se hizo el comentario, referencia a la tabla de temas
    fecha_comentario DATETIME DEFAULT CURRENT_TIMESTAMP,  -- Fecha de creación del comentario, se establece automáticamente
    FOREIGN KEY (user_id) REFERENCES usuarios(id),  -- Llave foránea que vincula el comentario con el usuario que lo hizo
    FOREIGN KEY (tema_id) REFERENCES temas(id)  -- Llave foránea que vincula el comentario con el tema correspondiente
);

-- Log para verificar la creación de la tabla de comentarios
SELECT 'Tabla comentarios creada exitosamente' AS log;

-- Crear tabla de imágenes que permite almacenar imágenes asociadas a los posts
CREATE TABLE imagenes (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- ID único para cada imagen, se incrementa automáticamente
    ruta VARCHAR(255) NOT NULL,  -- Ruta o URL donde se almacena la imagen, no nulo
    post_id INT,  -- ID del post asociado a la imagen, referencia a la tabla de temas
    FOREIGN KEY (post_id) REFERENCES temas(id)  -- Llave foránea que vincula la imagen con el tema correspondiente
);

-- Log para verificar la creación de la tabla de imágenes
SELECT 'Tabla imagenes creada exitosamente' AS log;

-- Crear tabla de likes que almacena los likes dados por los usuarios a los posts
CREATE TABLE likes (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- ID único para cada like, se incrementa automáticamente
    user_id INT,  -- ID del usuario que dio el like, referencia a la tabla de usuarios
    tema_id INT,  -- ID del tema al que se le dio el like, referencia a la tabla de temas
    fecha_like DATETIME DEFAULT CURRENT_TIMESTAMP,  -- Fecha en que se dio el like, se establece automáticamente
    FOREIGN KEY (user_id) REFERENCES usuarios(id),  -- Llave foránea que vincula el like con el usuario que lo hizo
    FOREIGN KEY (tema_id) REFERENCES temas(id)  -- Llave foránea que vincula el like con el tema correspondiente
);

-- Log para verificar la creación de la tabla de likes
SELECT 'Tabla likes creada exitosamente' AS log;
