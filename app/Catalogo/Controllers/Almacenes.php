<?php

use \Sincco\Tools\Debug;
use \Sincco\Sfphp\Response;

class AlmacenesController extends Sincco\Sfphp\Abstracts\Controller {

	public function index() {
		$mdlSalamandra = $this->getModel('Salamandra');
		$almacenes = $mdlSalamandra
					->table('almacenes')
					->where('status', 'Activo')
					->where('empresa', $_SESSION[ 'companiaClave' ])
					->getData();
		$this->helper('UsersAccount')->checkLogin();
		$view = $this->newView('Catalogo\AlmacenesTabla');
		$view->almacenes = $almacenes;
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function agregar() {
		$this->helper('UsersAccount')->checkLogin();
		$view = $this->newView('Catalogo\AlmacenesAlta');
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function apiAlta() {
		$id = $this->getModel('Catalogo\Almacenes')->insert($this->getParams());
		new Response('json', [ 'respuesta'=>$id ]);
	}

}