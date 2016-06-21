<?php

class ClientesController extends Sincco\Sfphp\Abstracts\Controller {
	public function index() {
		$this->helper( 'UsersAccount' )->checkLogin();
		$mdlClientes = $this->getModel('Catalogo\Clientes');
		$user = unserialize( $_SESSION[ 'sincco\login\controller'] );
		if( intval( ( isset( $_SESSION[ 'extraFiltroClientes' ] ) ? $_SESSION[ 'extraFiltroClientes' ] : 0 ) == 1 ) )
			$data = $mdlClientes->getByVendedor( $user[ 'userName' ] );
		else
			$data = $mdlClientes->getAll();
		// echo "<pre>";var_dump($mdlClientes->getAll());echo "</pre>";die();
		$view = $this->newView('Catalogo\ClientesTabla');
		$view->clientes = $data;
		$view->menus = $this->helper( 'UsersAccount' )->createMenus();
		$view->render();
	}
}