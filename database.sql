-- ============================================================
-- BASE DE DATOS: agenda_electronica
-- Archivo: database.sql
-- Descripción: Creación de tablas para la Agenda Electrónica
-- ============================================================

CREATE DATABASE IF NOT EXISTS agenda_electronica
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE agenda_electronica;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100)        NOT NULL,
    email       VARCHAR(150)        NOT NULL UNIQUE,
    usuario     VARCHAR(50)         NOT NULL UNIQUE,
    password    VARCHAR(255)        NOT NULL,
    rol         ENUM('admin','user') DEFAULT 'user',
    activo      TINYINT(1)          DEFAULT 1,
    creado_en   DATETIME            DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabla de actividades/eventos
CREATE TABLE IF NOT EXISTS actividades (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id   INT             NOT NULL,
    titulo       VARCHAR(200)    NOT NULL,
    descripcion  TEXT,
    fecha        DATE            NOT NULL,
    hora         TIME,
    prioridad    ENUM('baja','media','alta') DEFAULT 'media',
    estado       ENUM('pendiente','en_progreso','completada') DEFAULT 'pendiente',
    creado_en    DATETIME        DEFAULT CURRENT_TIMESTAMP,
    actualizado  DATETIME        DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Usuario administrador por defecto (contraseña: Admin123!)
INSERT INTO usuarios (nombre, email, usuario, password, rol)
VALUES ('Administrador', 'admin@agenda.local', 'admin',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
