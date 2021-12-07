CREATE DATABASE hector_lab;

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(100) NOT NULL,
  `password` text NOT NULL,
  `rol` enum('bioanalista','secretaria') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `pacientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `cedula` varchar(12) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pacientes_UN` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `examen_tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `examen_tipos_UN` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `examenes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `estado` enum('creado','completado') NOT NULL DEFAULT 'creado',
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `realizado_por` int(11) DEFAULT NULL,
  `resultados` varchar(1500) DEFAULT NULL,
  `enviado` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `examenes_FK` (`tipo_id`),
  KEY `examenes_FK_1` (`paciente_id`),
  KEY `examenes_FK_2` (`realizado_por`),
  CONSTRAINT `examenes_FK` FOREIGN KEY (`tipo_id`) REFERENCES `examen_tipos` (`id`),
  CONSTRAINT `examenes_FK_1` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`),
  CONSTRAINT `examenes_FK_2` FOREIGN KEY (`realizado_por`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;