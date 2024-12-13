

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;



-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `telefono` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `nombre`, `telefono`, `email`, `direccion`) VALUES
(1, 'SISTEMA DE BANCA AMISOL', '69803449', 'joe@gamil.com', 'El Alto');

-- --------------------------------------------------------

INSERT INTO `permisos` (`id`, `nombre`) VALUES
(1, 'configuración'),
(2, 'usuarios'),
(3, 'estudiantes'),
(4, 'clases'),
(5, 'categoriasactividad'),
(6, 'participaciones'),
(6, 'actividades');


CREATE TABLE `detalle_permisos` (
  `id` int(11) NOT NULL,
  `id_permiso` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar datos en detalle_permisos
INSERT INTO detalle_permisos (id, id_permiso, id_usuario) VALUES
(1, 3, 1),
(2, 4, 1),
(3, 5, 1);



CREATE TABLE `permisos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO permisos (id, nombre) VALUES
(1, 'configuración'),
(2, 'usuarios'),
(3, 'clientes'),
(4, 'evaluaciones de crédito'),
(5, 'tasas de interés');





CREATE TABLE `usuario` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `correo` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `usuario` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `clave` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusuario`, `nombre`, `correo`, `usuario`, `clave`, `estado`) VALUES
(1, 'JOE', 'joe@gmail.com', 'admin', '21232f297a57a5a743894a0e4a801fc3', 1),



ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `detalle_permisos`
  ADD PRIMARY KEY (`id`);



ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusuario`);


ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;


ALTER TABLE `detalle_permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;



ALTER TABLE `permisos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;


ALTER TABLE `usuario`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;


-- Tabla de clientes relacionada con usuario
CREATE TABLE clientes (
  id_cliente INT AUTO_INCREMENT PRIMARY KEY,
  nombre_cliente VARCHAR(100),
  email_cliente VARCHAR(100) UNIQUE,
  direccion VARCHAR(255),
  telefono VARCHAR(15),
  id_usuario INT,
  FOREIGN KEY (id_usuario) REFERENCES usuario(idusuario) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- Insertar datos en clientes
INSERT INTO clientes (id_cliente, nombre_cliente, email_cliente, direccion, telefono, id_usuario) VALUES
(1, 'Cliente Prueba', 'cliente@correo.com', 'Calle Falsa 123', '123456789', 1);



-- Tabla de buro de crédito
CREATE TABLE buro_de_credito (
  id_buro_credito INT AUTO_INCREMENT PRIMARY KEY,
  nombre_buro VARCHAR(100),
  historial_crediticio TEXT,
  calificacion_buro VARCHAR(50)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabla de modelos de scoring
CREATE TABLE modelos_de_scoring (
  id_modelo_scoring INT AUTO_INCREMENT PRIMARY KEY,
  nombre_modelo VARCHAR(100),
  descripcion TEXT,
  version VARCHAR(10)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabla de tasas de interés
CREATE TABLE tasas_de_interes (
  id_tasa_interes INT AUTO_INCREMENT PRIMARY KEY,
  tipo_credito VARCHAR(50),
  tasa DECIMAL(5, 2),
  fecha_aplicacion DATE,
  id_modelo_scoring INT,
  FOREIGN KEY (id_modelo_scoring) REFERENCES modelos_de_scoring(id_modelo_scoring) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabla de scoring relacionada con clientes y buro de crédito
CREATE TABLE scoring (
  id_scoring INT AUTO_INCREMENT PRIMARY KEY,
  id_cliente INT,
  puntaje_scoring DECIMAL(5, 2),
  nivel_riesgo VARCHAR(50),
  id_buro_credito INT,
  id_modelo_scoring INT,
  fecha_calculo DATE,
  FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente) ON DELETE CASCADE,
  FOREIGN KEY (id_buro_credito) REFERENCES buro_de_credito(id_buro_credito) ON DELETE CASCADE,
  FOREIGN KEY (id_modelo_scoring) REFERENCES modelos_de_scoring(id_modelo_scoring) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE garantias (
  id_garantia INT AUTO_INCREMENT PRIMARY KEY,
  id_cliente INT,
  descripcion_garantia VARCHAR(255),
  valor_garantia DECIMAL(10, 2),
  estado_garantia VARCHAR(50),
  FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Tabla de planes de pago relacionados con clientes y tasas de interés
CREATE TABLE planes_de_pago (
  id_plan_pago INT AUTO_INCREMENT PRIMARY KEY,
  id_cliente INT,
  monto_total DECIMAL(10, 2),
  plazo INT,
  tasa_interes DECIMAL(5, 2),
  fecha_inicio DATE,
  fecha_fin DATE,
  id_tasa_interes INT,
  FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente) ON DELETE CASCADE,
  FOREIGN KEY (id_tasa_interes) REFERENCES tasas_de_interes(id_tasa_interes) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabla de garantías relacionadas con clientes


-- Tabla de analistas de crédito relacionados con usuarios
CREATE TABLE analistas_de_credito (
  id_analista INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT,
  nombre_analista VARCHAR(100),
  apellido_analista VARCHAR(100),
  departamento VARCHAR(100),
  FOREIGN KEY (id_usuario) REFERENCES usuario(idusuario) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabla de evaluación de créditos relacionada con clientes, garantías, scoring y analistas
CREATE TABLE evaluacion_de_creditos (
  id_evaluacion INT AUTO_INCREMENT PRIMARY KEY,
  id_cliente INT,
  id_garantia INT,
  estado_credito VARCHAR(50),
  fecha_evaluacion DATE,
  id_scoring INT,
  id_analista INT,
  FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente) ON DELETE CASCADE,
  FOREIGN KEY (id_garantia) REFERENCES garantias(id_garantia) ON DELETE CASCADE,
  FOREIGN KEY (id_scoring) REFERENCES scoring(id_scoring) ON DELETE CASCADE,
  FOREIGN KEY (id_analista) REFERENCES analistas_de_credito(id_analista) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabla de gerentes de crédito relacionados con usuarios
CREATE TABLE gerentes_de_credito (
  id_gerente INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT,
  nombre_gerente VARCHAR(100),
  apellido_gerente VARCHAR(100),
  departamento VARCHAR(100),
  FOREIGN KEY (id_usuario) REFERENCES usuario(idusuario) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Insertar datos en las tablas relevantes para pruebas
INSERT INTO buro_de_credito (nombre_buro, historial_crediticio, calificacion_buro) VALUES
('Buro Nacional', 'Historial positivo', 'A');

INSERT INTO modelos_de_scoring (nombre_modelo, descripcion, version) VALUES
('Modelo Básico', 'Modelo estándar de evaluación', '1.0');

INSERT INTO tasas_de_interes (tipo_credito, tasa, fecha_aplicacion, id_modelo_scoring) VALUES
('Consumo', 5.50, '2024-01-01', 1);

INSERT INTO analistas_de_credito (id_usuario, nombre_analista, apellido_analista, departamento) VALUES
(1, 'Ana', 'Pérez', 'Créditos');

INSERT INTO scoring (id_cliente, puntaje_scoring, nivel_riesgo, id_buro_credito, id_modelo_scoring, fecha_calculo) VALUES
(1, 750, 'Bajo', 1, 1, '2024-12-10');


