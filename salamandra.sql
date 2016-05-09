-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 08-05-2016 a las 20:40:14
-- Versión del servidor: 5.5.49-0ubuntu0.14.04.1
-- Versión de PHP: 5.5.9-1ubuntu4.16

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
-- Estructura de tabla para la tabla `__menus`
--

CREATE TABLE IF NOT EXISTS `__menus` (
  `menuId` int(11) NOT NULL AUTO_INCREMENT,
  `menuText` varchar(50) NOT NULL,
  `menuURL` varchar(150) DEFAULT NULL,
  `menuParent` int(11) NOT NULL,
  PRIMARY KEY (`menuId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='System menus' AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `__menus`
--

INSERT INTO `__menus` (`menuId`, `menuText`, `menuURL`, `menuParent`) VALUES
(1, 'Catálogos', NULL, 0),
(2, 'Clientes', 'catalogo/clientes', 1),
(3, 'Proveedores', 'catalogo/proveedores', 1),
(4, 'Productos', 'catalogo/productos', 1),
(5, 'Configuración', NULL, 0),
(6, 'Usuarios', 'usuarios', 5);

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
