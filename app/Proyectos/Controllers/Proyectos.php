<?php

use \Sincco\Tools\Debug;
use \Sincco\Sfphp\Response;

class ProyectosController extends Sincco\Sfphp\Abstracts\Controller {

	public function index() {
		$this->helper('UsersAccount')->checkLogin();
		$view = $this->newView('Proyectos\ProyectosTabla');
		$view->almacenes = $this->getModel('Salamandra')
					->table('proyectos')
					->getData();
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