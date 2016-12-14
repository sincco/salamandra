<?php

use \Sincco\Tools\Debug;
use \Sincco\Sfphp\Response;

class RecetasController extends Sincco\Sfphp\Abstracts\Controller {

	public function index() {
		$mdlSalamandra = $this->getModel('Salamandra');
		$this->helper('UsersAccount')->checkLogin();
		$view = $this->newView('Produccion\RecetasTabla');
		$recetas = $mdlSalamandra
					->table('produccionRecetas')
					->where('status', 'Activo')
					->getData();
		$view->recetas = $recetas;
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function agregar() {
		$this->helper('UsersAccount')->checkLogin();
		$view = $this->newView('Produccion\RecetasAlta');
		$view->productos = $this->getModel('Catalogo\Productos')->getActivos();
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function apiAlta() {
		$id = $this->getModel('Produccion\Recetas')->insert($this->getParams());
		new Response('json', [ 'respuesta'=>$id ]);
	}

}