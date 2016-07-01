<?php

use \Sincco\Tools\Debug;
use \Sincco\Sfphp\Response;

class RecetasController extends Sincco\Sfphp\Abstracts\Controller {

	public function agregar() {
		$this->helper( 'UsersAccount' )->checkLogin();
		$view = $this->newView( 'Produccion\RecetasAlta' );
		$view->productos = $this->getModel('Catalogo\Productos')->getActivos();
		$view->menus = $this->helper( 'UsersAccount' )->createMenus();
		$view->render();
	}

	// $mdlProductos->table('Inve' . $_SESSION[ 'companiaClave' ])->where('STATUS','A')->getData()
}