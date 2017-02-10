
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--
DROP TABLE IF EXISTS `gzlzProyectos`;
CREATE TABLE `gzlzProyectos` (
  `idProyecto` int(11) NOT NULL AUTO_INCREMENT,
  `clave` char(10) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `cliente` varchar(30) NOT NULL,
  `contacto` varchar(45) NOT NULL,
  `planta` varchar(30) NOT NULL,
  `projectManager` varchar(30) NOT NULL,
  `lugarEntrega` varchar(45) NOT NULL,
  `cantidadLh` int(4) NOT NULL,
  `cantidadRh` int(4) NOT NULL,
  `resumen` mediumtext,
  `estatus` enum('En proceso','Cancelado','Pendiente','Terminado','Pausado','Cotizado','Aprobado') NOT NULL,
  `fechaAlta` DATE NULL,
  PRIMARY KEY (`idProyecto`));

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectosTareas`
--
DROP TABLE IF EXISTS `gzlzProyectosTareas`;
CREATE TABLE `gzlzProyectosTareas` (
  `idTarea` INT NOT NULL AUTO_INCREMENT,
  `idProyecto` INT NOT NULL COMMENT 'Control de tareas asignadas al proyecto',
  `titulo` VARCHAR(150) NOT NULL,
  `estatus` ENUM('En proceso', 'Pendiente', 'Terminado') NOT NULL COMMENT 'Control de tareas asignadas a un proyecto',
  PRIMARY KEY (`idTarea`));

-- --------------------------------------------------------

--
DROP TABLE IF EXISTS `gzlzProyectosProductos`;
CREATE TABLE IF NOT EXISTS `gzlzProyectosProductos` (
  `idProyectoProducto` int(11) NOT NULL AUTO_INCREMENT,
  `idProyecto` int(11) NOT NULL,
  `producto` varchar(16) NOT NULL,
  `descripcion` varchar(150) NOT NULL,
  `precio` float NOT NULL,
  `hoja` varchar(150) NOT NULL,
  `det` varchar(150) NOT NULL,
  `subDet` varchar(150) NOT NULL,
  `cutFit` float NOT NULL,
  `ncMach` float NOT NULL,
  `conventionalMach` float NOT NULL,
  `welding` float NOT NULL,
  `finishing` float NOT NULL,
  `assyPacking` float NOT NULL,
  `laserService` float NOT NULL,
  `installation` float NOT NULL,
  PRIMARY KEY (`idProyectoProducto`),
  UNIQUE KEY `gzlz_productos_tareas` (`idProyecto`, `descripcion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Proyeccion de materiales en proyectos';

-- --------------------------------------------------------

--
INSERT INTO `__menus` VALUES (900,'Procesos Especiales',NULL,0),(901,'Proyectos','gonzalez/proyectos',900);