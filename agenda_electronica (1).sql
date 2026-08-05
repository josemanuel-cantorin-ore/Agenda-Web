-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-08-2026 a las 00:32:49
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `agenda_electronica`
--
CREATE DATABASE IF NOT EXISTS `agenda_electronica` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `agenda_electronica`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

DROP TABLE IF EXISTS `eventos`;
CREATE TABLE `eventos` (
  `id_evento` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `titulo_evento` varchar(120) NOT NULL,
  `contenido_evento` varchar(300) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_finalizacion` datetime DEFAULT NULL,
  `ubicacion` varchar(300) DEFAULT NULL,
  `repeticion` enum('diario','semanal','mensual') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`id_evento`, `id_usuario`, `titulo_evento`, `contenido_evento`, `fecha`, `fecha_finalizacion`, `ubicacion`, `repeticion`) VALUES
(1, 1, 'Reunión de Sincronización', 'Alineación de objetivos del equipo de desarrollo', '2026-06-05 14:00:00', '2026-06-05 10:30:00', 'Sala de Juntas A', NULL),
(2, 2, 'Cita Médica General', 'Chequeo anual preventivo', '2026-06-06 20:00:00', NULL, 'Clínica Central, Consultorio 302', NULL),
(3, 3, 'Clase de Gimnasio', 'Rutina de pierna y cardio de alta intensidad', '2026-06-05 00:00:00', '2026-06-04 20:30:00', 'FitPlaza Gym', 'diario'),
(4, 4, 'Revisión de Proyecto Web', 'Presentación de avances de la agenda electrónica', '2026-06-08 16:00:00', '2026-06-08 12:00:00', 'Videollamada por Teams', NULL),
(5, 5, 'Almuerzo Familiar', 'Celebración del cumpleaños de mamá', '2026-06-07 18:30:00', '2026-06-07 16:00:00', 'Restaurante El Leñador', NULL),
(6, 6, 'Pago de Servicios', 'Fecha límite para pagar luz, agua e internet', '2026-06-10 13:00:00', NULL, 'Banca por Internet', 'mensual'),
(7, 7, 'Sesión de Mentoría', 'Asesoría sobre patrones de diseño de software', '2026-06-12 21:00:00', '2026-06-12 17:30:00', 'Google Meet', 'semanal'),
(8, 8, 'Mantenimiento del Auto', 'Cambio de aceite, filtros y revisión de frenos', '2026-06-15 13:30:00', '2026-06-15 11:00:00', 'Taller Mecánico Automotriz', NULL),
(9, 9, 'Concierto de Rock', 'Presentación de la banda favorita en vivo', '2026-06-21 02:00:00', '2026-06-21 01:00:00', 'Estadio Nacional', NULL),
(10, 10, 'Planificación Semanal', 'Organizar las metas y entregas de la semana', '2026-06-08 12:00:00', '2026-06-08 08:00:00', 'Oficina en Casa', 'semanal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas_pendientes`
--

DROP TABLE IF EXISTS `tareas_pendientes`;
CREATE TABLE `tareas_pendientes` (
  `id_tp` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre_tarea` varchar(100) NOT NULL,
  `contenido_tarea` varchar(300) DEFAULT NULL,
  `estado_tarea` enum('pendiente','en proceso','completada') DEFAULT 'pendiente',
  `prioridad` enum('alta','media','baja') DEFAULT NULL,
  `fecha_limite` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tareas_pendientes`
--

INSERT INTO `tareas_pendientes` (`id_tp`, `id_usuario`, `nombre_tarea`, `contenido_tarea`, `estado_tarea`, `prioridad`, `fecha_limite`) VALUES
(1, 1, 'Terminar CRUD de Usuarios', 'Escribir las funciones de insertar, editar y eliminar en PHP', 'en proceso', 'alta', '2026-06-06 23:59:59'),
(2, 2, 'Comprar víveres', 'Frutas, verduras, pollo y productos de limpieza', 'pendiente', 'media', '2026-06-05 20:00:00'),
(3, 3, 'Estudiar para examen', 'Repasar conceptos de bases de datos relacionales', 'pendiente', 'alta', '2026-06-09 14:00:00'),
(4, 4, 'Subir proyecto a GitHub', 'Crear el repositorio público y hacer el primer commit', 'completada', 'baja', '2026-06-04 18:00:00'),
(5, 5, 'Enviar informe mensual', 'Redactar el resumen de métricas del mes anterior', 'en proceso', 'alta', '2026-06-05 12:00:00'),
(6, 6, 'Lavar ropa', 'Separar ropa blanca de color y colgarla', 'completada', 'baja', NULL),
(7, 7, 'Actualizar sistema operativo', 'Hacer respaldo y aplicar parches de seguridad obligatorios', 'pendiente', 'media', '2026-06-11 22:00:00'),
(8, 8, 'Llamar al casero', 'Consultar sobre el mantenimiento de la tubería de gas', 'pendiente', 'alta', '2026-06-05 10:00:00'),
(9, 9, 'Leer capítulo 4', 'Terminar la lectura asignada de ingeniería de requerimientos', 'completada', 'media', '2026-06-03 23:59:59'),
(10, 10, 'Diseñar interfaz de Login', 'Crear los estilos CSS y la estructura HTML responsive', 'en proceso', 'alta', '2026-06-07 18:00:00'),
(11, 1, 'Presentacion tarea', 'presentar tarea de agenda', 'pendiente', 'alta', '2026-06-04 18:15:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(100) NOT NULL,
  `apellidos` varchar(280) NOT NULL,
  `numero_telefono` varchar(15) NOT NULL,
  `correo` varchar(200) NOT NULL,
  `contrasena` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_usuario`, `apellidos`, `numero_telefono`, `correo`, `contrasena`) VALUES
(1, 'Carlos', 'Mendoza Ruiz', '987654321', 'carlos@email.com', '1234'),
(2, 'Ana', 'Gomez Peralta', '912345678', 'ana.gomez@email.com', 'Password123'),
(3, 'Luis', 'Torres Silva', '923456781', 'luis.torres@email.com', 'Password123'),
(4, 'Sofia', 'Castro Villalba', '934567812', 'sofia.castro@email.com', 'Password123'),
(5, 'Diego', 'Salazar Herrera', '945678123', 'diego.salazar@email.com', 'Password123'),
(6, 'Maria', 'Espinoza Delgado', '956781234', 'maria.es@email.com', 'Password123'),
(7, 'Javier', 'Benitez Vargas', '967812345', 'javier.b@email.com', 'Password123'),
(8, 'Lucia', 'Morales Flores', '978123456', 'lucia.m@email.com', 'Password123'),
(9, 'Andres', 'Rojas Palacios', '989012345', 'andres.rojas@email.com', 'Password123'),
(10, 'Elena', 'Campos Paredes', '990123456', 'elena.c@email.com', 'Password123');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id_evento`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `tareas_pendientes`
--
ALTER TABLE `tareas_pendientes`
  ADD PRIMARY KEY (`id_tp`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `tareas_pendientes`
--
ALTER TABLE `tareas_pendientes`
  MODIFY `id_tp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `eventos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tareas_pendientes`
--
ALTER TABLE `tareas_pendientes`
  ADD CONSTRAINT `tareas_pendientes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
