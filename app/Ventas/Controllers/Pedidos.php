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

	public function porAprobar() {
		$view = $this->newView('Ventas\PedidosPorAprobar');
		$view->pedidos = $this->getModel('Salamandra')->pedidosEstatus()->where('estatus', 'Pendiente')->getData();
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function aprobados() {
		$view = $this->newView('Ventas\PedidosTabla');
		$aprobados = $this->getModel('Salamandra')->pedidosEstatus()->where('estatus', 'Autorizado')->getData();
		$pedidos = [];
		foreach ($aprobados as $_pedido) {
			$pedidos[] = $_pedido;
		}
		$view->pedidos = $this->getModel('Ventas\Pedidos')->getIn($pedidos);
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