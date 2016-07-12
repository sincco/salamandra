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
-- Estructura de tabla para la tabla `almacenes`
--

CREATE TABLE IF NOT EXISTS `almacenes` (
  `empresa` char(3) NOT NULL,
  `almacen` char(3) NOT NULL,
  `descripcion` varchar(75) NOT NULL,
  `status` enum('Activo','Bloqueado') NOT NULL,
  UNIQUE KEY `empresa_almacen` (`empresa`,`almacen`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Almacenes para procesos de produccion';

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

CREATE TABLE IF NOT EXISTS `perfiles` (
  `perfil` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(75) NOT NULL,
  PRIMARY KEY (`perfil`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Grupos de usuarios' AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `perfiles`
--

INSERT INTO `perfiles` (`perfil`, `descripcion`) VALUES
(1, 'Administrador'),
(2, 'Vendedor');

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
  `perfil` int(11) NOT NULL,
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
-- Estructura de tabla para la tabla `__menus`
--

CREATE TABLE IF NOT EXISTS `__menus` (
  `menuId` int(11) NOT NULL AUTO_INCREMENT,
  `menuText` varchar(50) NOT NULL,
  `menuURL` varchar(150) DEFAULT NULL,
  `menuParent` int(11) NOT NULL,
  PRIMARY KEY (`menuId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='System menus' AUTO_INCREMENT=20 ;

--
-- Volcado de datos para la tabla `__menus`
--

INSERT INTO `__menus` (`menuId`, `menuText`, `menuURL`, `menuParent`) VALUES
(1, 'Catálogos', NULL, 0),
(2, 'Clientes', 'catalogo/clientes', 1),
(3, 'Proveedores', 'catalogo/proveedores', 1),
(4, 'Productos', 'catalogo/productos', 1),
(5, 'Configuración', NULL, 0),
(6, 'Usuarios', 'usuarios', 5),
(7, 'Producción', NULL, 0),
(8, 'Recetas', 'produccion/recetas', 7),
(9, 'Ventas', NULL, 0),
(10, 'Cotizaciones', 'ventas/cotizaciones', 9),
(11, 'Seleccionar Compañía', 'selcia', 5),
(12, 'Pedidos', 'ventas/pedidos', 9),
(13, 'Cuentas por Cobrar', NULL, 0),
(14, 'Adeudos', 'cxc/adeudos', 13),
(15, 'Reportes', NULL, 0),
(16, 'Consultar', 'reportes/consultar', 15),
(17, 'Seguridad 2 Pasos', 'authqr/config', 5),
(18, 'Almacenes', 'catalogo/almacenes', 1),
(19, 'Procesos', 'produccion/procesos', 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `__usersControl`
--

CREATE TABLE IF NOT EXISTS `__usersControl` (
  `userId` int(11) NOT NULL,
  `userName` varchar(150) NOT NULL,
  `userEmail` varchar(150) DEFAULT NULL,
  `userPassword` varchar(60) DEFAULT NULL,
  `userStatus` char(1) DEFAULT NULL,
  PRIMARY KEY (`userName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
