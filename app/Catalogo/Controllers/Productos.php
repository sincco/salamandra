<?php

class ProductosController extends Sincco\Sfphp\Abstracts\Controller {

	public function index() {
		$mdlProductos = $this->getModel('Catalogo\Productos');
		$data = $mdlProductos->getAll();
		$view = $this->newView('Catalogo\ProductosTabla');
		$view->productos = $data;
		$view->menus = $this->helper( 'UsersAccount' )->createMenus( $data );
		echo $view->render();
	}
}