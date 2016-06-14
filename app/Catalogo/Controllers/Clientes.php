<?php

class ClientesController extends Sincco\Sfphp\Abstracts\Controller {
	public function index() {
		$this->helper( 'UsersAccount' )->checkLogin();
		$mdlClientes = $this->getModel('Catalogo\Clientes');
		$data = $mdlClientes->getAll();
		var_dump($data);die();
		$view = $this->newView('Catalogo\ClientesTabla');
		$view->clientes = $data;
		$view->menus = $this->helper( 'UsersAccount' )->createMenus();
		$view->render();
	}
}