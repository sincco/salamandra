<?php

use \Sincco\Sfphp\Response;

class PedidosController extends Sincco\Sfphp\Abstracts\Controller {
	public function index() {
		$model = $this->getModel('Ventas\Pedidos');
		$view = $this->newView('Ventas\PedidosTabla');
		$view->pedidos = $model->getAll();
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function remisiones() {
		$model = $this->getModel('Ventas\Pedidos');
		$view = $this->newView('Ventas\RemisionesTabla');
		$view->remisiones = $model->getRemisiones();
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}
}