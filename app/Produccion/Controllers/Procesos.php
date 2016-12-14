<?php

use \Sincco\Tools\Debug;
use \Sincco\Sfphp\Response;

class ProcesosController extends Sincco\Sfphp\Abstracts\Controller {

	public function index() {
		$mdlSalamandra = $this->getModel('Salamandra');
		$recetas = $mdlSalamandra
					->table('produccionRecetas')
					->where('status', 'Activo')
					->getData();
		$this->helper('UsersAccount')->checkLogin();
		$view = $this->newView('Produccion\ProcesosControl');
		$view->recetas = $recetas;
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