<?php

use \Sincco\Sfphp\Response;
use \Sincco\Tools\Debug;

class EntregasController extends Sincco\Sfphp\Abstracts\Controller {
	public function index()
	{
		$this->helper('UsersAccount')->checkLogin();
		$entregas = $this->getModel('Salamandra')->entregas()
			->join('unidades uni', 'uni.idUnidad = maintable.idUnidad')
			->join('operadores ope', 'ope.idOperador = maintable.idOperador')
			->where('fechaEntrega',date('Y-m-d'))
			->getData();
		$view = $this->newView('Transporte\EntregasDia');
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->entregas = $entregas;
		$view->render();
	}

	public function visita()
	{
		$this->helper('UsersAccount')->checkLogin();
		$view = $this->newView('Transporte\EntregasVisita');
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function entregados()
	{
		$this->helper('UsersAccount')->checkLogin();
		$view = $this->newView('Transporte\Entregados');
		$entregas = $this->getModel('Salamandra')->entregas()
			->fields('pedido')
			->fields('fechaEntrega')
			->join('unidades uni', 'uni.idUnidad = maintable.idUnidad')
			->join('operadores ope', 'ope.idOperador = maintable.idOperador')
			->where('fechaEntrega',date('Y-m-d'))
			->where('entregado',1)
			->getData();
		$entregas = array_map("unserialize", array_unique(array_map("serialize", $entregas)));
		$view->entregas = $entregas;
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}
}