<?php

use \Sincco\Sfphp\Response;

class PedidosController extends Sincco\Sfphp\Abstracts\Controller {
	public function index() {
		$model = $this->getModel( 'Ventas\Pedidos' );
		$view = $this->newView( 'Ventas\PedidosTabla' );
		$view->pedidos = $model->getAll();
		$view->menus = $this->helper( 'UsersAccount' )->createMenus();
		$view->render();
	}
}