<?php

class CotizacionesController extends Sincco\Sfphp\Abstracts\Controller {
	public function index() {
		$model = $this->getModel('Ventas\Cotizaciones');
		$view = $this->newView('Ventas\CotizacionesTabla');
		$view->cotizaciones = $model->getAll();
		$view->menus = $this->helper( 'UsersAccount' )->createMenus( $data );
		$view->render();
	}

	public function agregar() {
		$mdlProductos = $this->getModel( 'Catalogo\Productos' );
		$view = $this->newView('Ventas\CotizacionesAgregar');
		$view->menus = $this->helper( 'UsersAccount' )->createMenus( $data );
		$view->precios = $mdlProductos->getListaPrecios();
		$view->render();
	}
}