-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS sistema_registro CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Usar la base de datos
USE sistema_registro;

-- Crear la tabla para almacenar los datos de los miembros
CREATE TABLE IF NOT EXISTS miembros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ci VARCHAR(20) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    lugar VARCHAR(100) NOT NULL,
    profesion VARCHAR(100) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    telefono VARCHAR(50) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    barrio VARCHAR(100) NOT NULL,
    esposa VARCHAR(100),
    hijos TEXT,
    madre VARCHAR(100),
    padre VARCHAR(100),
    
    -- Datos laborales
    direccion_laboral VARCHAR(255) NOT NULL,
    empresa VARCHAR(100),
    
    -- Datos institucionales
    institucion_actual VARCHAR(100) NOT NULL,
    nivel_actual VARCHAR(50) NOT NULL,
    nivel_superior VARCHAR(50),
    fecha_ingreso DATE NOT NULL,
    institucion_ingreso VARCHAR(100) NOT NULL,
    documentos VARCHAR(255),
    
    -- Datos médicos
    grupo_sanguineo VARCHAR(5) NOT NULL,
    enfermedades_base TEXT,
    seguro_privado ENUM('Si', 'No') DEFAULT 'No',
    ips ENUM('Si', 'No') DEFAULT 'No',
    alergias TEXT,
    numero_emergencia VARCHAR(50) NOT NULL,
    contacto_emergencia VARCHAR(100) NOT NULL,
    
    -- Metadatos
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultima_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Índices
    UNIQUE INDEX (ci)
);

-- Crear tabla para usuarios administradores
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    nombre_completo VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    rol ENUM('admin', 'editor', 'visualizador') DEFAULT 'visualizador',
    activo BOOLEAN DEFAULT TRUE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultima_conexion TIMESTAMP NULL
);

-- Insertar usuario administrador por defecto (contraseña: Admin123)
INSERT INTO usuarios (nombre_usuario, password_hash, nombre_completo, email, rol) 
VALUES ('admin', '$2y$10$YIaU1BVH6P8LUQvY9xSJx.TqCYaJK94hDLkx1GyVZuuFa5rZUGz8S', 'Administrador', 'admin@example.com', 'admin');