-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfiles`
--

DROP TABLE IF EXISTS `perfiles`;
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
-- Estructura de tabla para la tabla `usuariosExtra`
--

DROP TABLE IF EXISTS `usuariosExtra`;
CREATE TABLE IF NOT EXISTS `usuariosExtra` (
  `userId` int(11) NOT NULL,
  `perfil` int(11) NOT NULL,
  `filtroClientes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Configuración adicional para usuarios';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuariosMenus`
--

DROP TABLE IF EXISTS `usuariosMenus`;
CREATE TABLE IF NOT EXISTS `usuariosMenus` (
  `userId` int(11) NOT NULL,
  `menuId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Excepciones de acceso a usuarios';
