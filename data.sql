-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 11-07-2016 a las 18:14:51
-- Versión del servidor: 5.6.30-0ubuntu0.14.04.1
-- Versión de PHP: 5.5.9-1ubuntu4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `salamandra`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizaciones`
--

CREATE TABLE IF NOT EXISTS `cotizaciones` (
  `cotizacion` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `cliente` varchar(10) NOT NULL,
  `razonSocial` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `estatus` enum('Nueva','Enviada','Cancelada') NOT NULL,
  `userId` int(11) NOT NULL,
  PRIMARY KEY (`cotizacion`),
  UNIQUE KEY `cotizacion_cliente` (`cliente`,`fecha`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Cotizaciones a clientes' AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacionesDetalle`
--

CREATE TABLE IF NOT EXISTS `cotizacionesDetalle` (
  `cotizacion` int(11) NOT NULL,
  `producto` varchar(16) NOT NULL,
  `descripcion` varchar(150) NOT NULL,
  `unidad` varchar(10) NOT NULL,
  `cantidad` double NOT NULL,
  `precio` double NOT NULL,
  UNIQUE KEY `cotizacion_producto` (`cotizacion`,`producto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Detalle de cotizacion';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE IF NOT EXISTS `empresas` (
  `empresa` char(3) NOT NULL,
  `razonSocial` varchar(150) NOT NULL,
  `estatus` enum('Activa','Bloqueada') NOT NULL,
  UNIQUE KEY `empresa` (`empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Empresas registradas en SAE';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfiles`
--

CREATE TABLE `perfiles` (
  `descripcion` CHAR(10) NOT NULL,
  `opcionesBloqueadas` MEDIUMTEXT NOT NULL,
  `idPerfil` INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`idPerfil`));

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `procesos`
--

CREATE TABLE IF NOT EXISTS `procesos` (
  `proceso` int(11) NOT NULL AUTO_INCREMENT,
  `empresa` char(3) NOT NULL,
  `almacenEntrada` char(3) NOT NULL,
  `almacenSalida` char(3) NOT NULL,
  `receta` int(11) NOT NULL,
  `cantidad` float NOT NULL,
  `status` enum('En produccion','Terminado') NOT NULL,
  PRIMARY KEY (`proceso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Control de procesos' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccionRecetas`
--

CREATE TABLE IF NOT EXISTS `produccionRecetas` (
  `receta` int(11) NOT NULL AUTO_INCREMENT,
  `producto` varchar(16) NOT NULL,
  `descripcion` varchar(75) NOT NULL,
  `unidad` char(3) NOT NULL,
  `status` enum('Activo','Bloqueado') NOT NULL,
  PRIMARY KEY (`receta`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Cabecera de recetas' AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccionRecetasDetalle`
--

CREATE TABLE IF NOT EXISTS `produccionRecetasDetalle` (
  `receta` int(11) NOT NULL,
  `producto` varchar(16) NOT NULL,
  `descripcion` varchar(75) NOT NULL,
  `cantidad` float NOT NULL,
  `costo` float NOT NULL,
  UNIQUE KEY `recetas_detalle` (`receta`,`producto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Ingredientes de recetas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuariosEmpresas`
--

CREATE TABLE IF NOT EXISTS `usuariosEmpresas` (
  `userId` int(11) NOT NULL,
  `empresa` char(3) NOT NULL,
  UNIQUE KEY `usuario_empresa` (`userId`,`empresa`) COMMENT 'usuario_empresa'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Empresas permitidas por usuario';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuariosExtra`
--

CREATE TABLE IF NOT EXISTS `usuariosExtra` (
  `userId` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `idPerfil` int(11) NOT NULL,
  `filtroClientes` int(11) NOT NULL,
  UNIQUE KEY `usuarios_extra` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Configuración adicional para usuarios';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuariosMenus`
--

CREATE TABLE IF NOT EXISTS `usuariosMenus` (
  `userId` int(11) NOT NULL,
  `menuId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Excepciones de acceso a usuarios';

-- --------------------------------------------------------

--
-- Table structure for table `__menus`
--

DROP TABLE IF EXISTS `__menus`;
CREATE TABLE `__menus` (
  `menuId` int(11) NOT NULL AUTO_INCREMENT,
  `menuText` varchar(50) NOT NULL,
  `menuURL` varchar(150) DEFAULT NULL,
  `menuParent` int(11) NOT NULL,
  PRIMARY KEY (`menuId`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1 COMMENT='System menus';

--
-- Dumping data for table `__menus`
--

INSERT INTO `__menus` VALUES (1,'Catálogos',NULL,0),(2,'Clientes','catalogo/clientes',1),(3,'Proveedores','catalogo/proveedores',1),(4,'Productos','catalogo/productos',1),(5,'Configuración',NULL,1),(6,'Usuarios','catalogo/usuarios',5),(7,'Producción',NULL,0),(8,'Recetas','produccion/recetas',7),(9,'Ventas',NULL,0),(10,'Cotizaciones','ventas/cotizaciones',9),(11,'Seleccionar Compañía','selcia',5),(12,'Pedidos','ventas/pedidos',9),(13,'Cuentas por Cobrar',NULL,0),(14,'Adeudos','cxc/adeudos',13),(15,'Reportes',NULL,0),(16,'Consultar','reportes/consultar',15),(17,'Seguridad 2 Pasos','authqr/config',5),(18,'Almacenes','catalogo/almacenes',1),(19,'Procesos','produccion/procesos',7),(20,'Remisiones','ventas/pedidos/remisiones',7),(21,'Envíos','transporte/envios',9),(22,'Transporte',NULL,1),(23,'Operadores','catalogo/operadores',22),(24,'Unidades','catalogo/unidades',22),(25,'Perfiles','catalogo/perfiles',5),(26,'Control de Proyectos','',0),(27,'Proyectos','proyectos/proyectos',26);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `__usersControl`
--

CREATE TABLE IF NOT EXISTS `__usersControl` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(150) NOT NULL,
  `userEmail` varchar(150) DEFAULT NULL,
  `userPassword` varchar(60) DEFAULT NULL,
  `userStatus` char(1) DEFAULT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operadores`
--

CREATE TABLE `operadores` (
  `idOperador` int(11) NOT NULL AUTO_INCREMENT,
  `clave` char(8) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `estatus` enum('Activo','Inactivo') NOT NULL,
  PRIMARY KEY (`idOperador`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Operadores de camiones';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidades`
--

CREATE TABLE `unidades` (
  `idUnidad` int(11) NOT NULL AUTO_INCREMENT,
  `noEco` char(8) NOT NULL,
  `estatus` enum('Activo','Inactivo') NOT NULL,
  PRIMARY KEY (`idUnidad`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Unidades';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entregas`
--

CREATE TABLE `entregas` (
  `idEntrega` INT NOT NULL AUTO_INCREMENT,
  `pedido` CHAR(8) NULL,
  `producto` CHAR(16) NULL,
  `fechaEntrega` DATE NULL,
  `cantidad` FLOAT NULL,
  `idUnidad` INT NULL,
  `idOperador` INT NULL,
  `entregado` BIT NULL,
  `tarimasPorRecoger` INT NULL COMMENT 'Control de entregas',
  PRIMARY KEY (`idEntrega`));

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--

CREATE TABLE `proyectos` (
  `idProyecto` INT NOT NULL AUTO_INCREMENT,
  `clave` CHAR(10) NOT NULL,
  `titulo` VARCHAR(150) NOT NULL,
  `resumen` MEDIUMTEXT NULL,
  `estatus` ENUM('En proceso', 'Cancelado', 'Pendiente', 'Terminado', 'Pausado', 'Cotizado', 'Aprobado') NOT NULL,
  PRIMARY KEY (`idProyecto`));

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectosTareas`
--

CREATE TABLE `proyectosTareas` (
  `idTarea` INT NOT NULL AUTO_INCREMENT,
  `idProyecto` INT NOT NULL COMMENT 'Control de tareas asignadas al proyecto',
  `titulo` VARCHAR(150) NOT NULL,
  `resumen` MEDIUMTEXT NULL,
  `fechaInicioProyectado` DATE NULL,
  `fechaInicioReal` DATE NULL,
  `fechaFinProyectado` DATE NULL,
  `fechaFinReal` DATE NULL,
  `estatus` ENUM('En proceso', 'Pendiente', 'Terminado') NOT NULL COMMENT 'Control de tareas asignadas a un proyecto',
  PRIMARY KEY (`idTarea`));

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectosTareas`
--
DROP TABLE IF EXISTS `proyectosTareasTiempos`;
CREATE TABLE `proyectosTareasTiempos` (
  `idLoggeo` INT NOT NULL AUTO_INCREMENT,
  `idTarea` INT NOT NULL,
  `idUsuario` INT NOT NULL,
  `inicio` DATETIME NOT NULL,
  `fin` DATETIME NULL COMMENT 'Track de tiempos por usuario en tareas',
  PRIMARY KEY (`idLoggeo`));

-- --------------------------------------------------------

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
