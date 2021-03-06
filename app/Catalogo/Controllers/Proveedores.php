<?php

class ProveedoresController extends Sincco\Sfphp\Abstracts\Controller {

	public function index() {
		$this->helper('UsersAccount')->checkLogin();
		$mdlProveedores = $this->getModel('Catalogo\Proveedores');
		$data = $mdlProveedores->getAll();
		$view = $this->newView('Catalogo\ProveedoresTabla');
		$view->proveedores = $data;
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}
}