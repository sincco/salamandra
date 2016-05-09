<?php

/**
 * Captura de petición al home
 */
class RecetasController extends Sincco\Sfphp\Abstracts\Controller {
	/**
	 * Validar si el usuario está loggeado para accesar al dashboard
	 * @return none
	 */
	public function agregar() {
		$mdlProductos = $this->getModel('Catalogo\Productos');
		$view = $this->newView( 'Produccion\RecetasAlta' );
		$view->productos = $mdlProductos->getAll();
		$view->menus = $this->helper( 'UsersAccount' )->createMenus();
		echo $view->render();
	}
}