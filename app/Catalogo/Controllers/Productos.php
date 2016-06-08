<?php

use \Sincco\Sfphp\Response;

class ProductosController extends Sincco\Sfphp\Abstracts\Controller {

	public function index() {
		$this->helper( 'UsersAccount' )->checkLogin();
		$mdlProductos = $this->getModel( 'Catalogo\Productos' );
		$data = $mdlProductos->getAll();
		$view = $this->newView( 'Catalogo\ProductosTabla' );
		$view->productos = $data;
		$view->menus = $this->helper( 'UsersAccount' )->createMenus();
		$view->render();
	}

	public function apiClave() {
		$mdlProductos = $this->getModel( 'Catalogo\Productos' );
		$data = $mdlProductos->getByClave( $this->getParams( 'data' ), $this->getParams( 'listaPrecio' ) );
		new Response( 'json', $data );
	}

	public function apiCatalogo() {
		$mdlProductos = $this->getModel( 'Catalogo\Productos' );
		$data = $mdlProductos->getActivos();
		new Response( 'json', $data );
	}
}