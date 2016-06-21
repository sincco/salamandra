<?php

class ClientesController extends Sincco\Sfphp\Abstracts\Controller {
	public function index() {
		$this->helper( 'UsersAccount' )->checkLogin();
		$mdlClientes = $this->getModel('Catalogo\Clientes');
		if(defined('SESSION_USERNAME'))
			$data = $mdlClientes->getAll();
		else
			$data = $mdlClientes->getByVendedor(SESSION_USERNAME);
		$view = $this->newView('Catalogo\ClientesTabla');
		$view->clientes = $data;
		$view->menus = $this->helper( 'UsersAccount' )->createMenus();
		$view->render();
	}
}