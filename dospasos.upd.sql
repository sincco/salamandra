INSERT INTO  `__menus` (
`menuId` ,
`menuText` ,
`menuURL` ,
`menuParent`
)
VALUES (
NULL ,  'Seguridad 2 Pasos',  'authqr/config',  '5'
);

ALTER TABLE  `usuariosExtra` ADD UNIQUE  `usuarios_extra` (  `userId` ) COMMENT  '';