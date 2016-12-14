<?php

use \Sincco\Sfphp\Request;

/**
 * Control de acceso al sistema
 */
class UsuariosController extends Sincco\Sfphp\Abstracts\Controller {

	public function index () {
		$mdlUsuarios = $this->getModel('Catalogo\Usuarios');
		$data = $mdlUsuarios->getAll();
		$view = $this->newView('Catalogo\UsuariosTabla');
		$view->usuarios = $data;
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function editar () {
		$mdlUsuarios = $this->getModel('Catalogo\Usuarios');
		$view = $this->newView('Catalogo\UsuariosAlta');
		$view->usuarios = $mdlUsuarios->loadByUserName($this->getParams('userName'));
		$view->perfiles = $this->getModel('Catalogo\Perfiles')->getAll();
		$view->action = "upd";
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function agregar() {
		$view = $this->newView('Catalogo\UsuariosAlta');
		$view->perfiles = $this->getModel('Catalogo\Perfiles')->getAll();
		$view->action = "ins";
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function apiAgregar() {
		$user = $this->getParams('userData');
		if($user[ 'action' ] == 'ins') {
			if($userId = $this->helper('UsersAccount')->createUser($user)) {
				$mdlUsuarios = $this->getModel('Catalogo\Usuarios');
				$mdlUsuarios->usuarioAEmpresas($userId);
				$mdlUsuarios->insExtra($this->getParams('userExtra'));
			}
			echo json_encode([ 'respuesta'=>$userId ]);
		}
		else {

			echo json_encode(array('respuesta'=>$this->helper('UsersAccount')->editUser($user)));
		}
	}

}