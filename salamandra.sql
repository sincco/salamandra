-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 07-06-2016 a las 14:32:36
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

--
-- Volcado de datos para la tabla `cotizaciones`
--

INSERT INTO `cotizaciones` (`cotizacion`, `fecha`, `cliente`, `razonSocial`, `email`, `estatus`, `userId`) VALUES
(1, '2016-05-19', '1', 'ACERLAN, S.A. DE C.V.', 'pa.ivan.miranda@gmail.com', 'Enviada', 1),
(3, '2016-05-24', '34', 'SIEMENS, S.A. DE C.V.', 'pa.ivan.miranda@gmail.com', 'Nueva', 1);

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

--
-- Volcado de datos para la tabla `cotizacionesDetalle`
--

INSERT INTO `cotizacionesDetalle` (`cotizacion`, `producto`, `descripcion`, `unidad`, `cantidad`, `precio`) VALUES
(1, '11108305', 'RECTIFICADOR USC 9R 1/4&#34;  (11', 'pieza', 558, 1),
(1, '11108406', 'RECTIFICADOR USC 25R 110V OBSOLETO (11', 'pieza', 432.54, 1),
(1, '11109707', 'ESMERILADOR ANGULAR UWF 10 110V (11', 'pieza', 297, 1),
(1, '5727501', 'INTERRUPTOR POS 111 S - C (11.1', 'pieza', 14.9, 1),
(3, '5726702', 'CARBON POS 462 SERIE C   (PZA)  (11.1', 'pieza', 6.1, 1);

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

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`empresa`, `razonSocial`, `estatus`) VALUES
('01', 'Tricorp', 'Activa'),
('02', 'Otra empresa', 'Activa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccionRecetas`
--

CREATE TABLE IF NOT EXISTS `produccionRecetas` (
  `receta` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(75) NOT NULL,
  `status` enum('Activo','Bloqueado') NOT NULL,
  PRIMARY KEY (`receta`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Cabecera de recetas' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccionRecetasDetalle`
--

CREATE TABLE IF NOT EXISTS `produccionRecetasDetalle` (
  `receta` int(11) NOT NULL,
  `producto` varchar(16) NOT NULL,
  `cantidad` float NOT NULL,
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

--
-- Volcado de datos para la tabla `usuariosEmpresas`
--

INSERT INTO `usuariosEmpresas` (`userId`, `empresa`) VALUES
(1, '01'),
(2, '01');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='System menus' AUTO_INCREMENT=15 ;

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
(14, 'Adeudos', 'cxc/adeudos', 13);

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

--
-- Volcado de datos para la tabla `__usersControl`
--

INSERT INTO `__usersControl` (`userId`, `userName`, `userEmail`, `userPassword`, `userStatus`) VALUES
(1, 'ivan', 'ivan', '$2y$12$Z9QaOgLYR0eXqRZ72.lmkeLzXOox6rx8/LsuGzFlRXwiQY/HjAoPK', NULL),
(2, 'rivero', 'rivero', '$2y$12$tTt9n9bdjk6uQtQieD5flebDm.rP235iazkYz7NgYZoOXbjMPpBEy', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
