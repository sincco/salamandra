<?php

use \Sincco\Sfphp\Response;

class PerfilesController extends Sincco\Sfphp\Abstracts\Controller {
	
	public function index() {
		$view = $this->newView('Catalogo\PerfilesTabla');
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->perfiles = $this->getModel('Catalogo\Perfiles')->getAll();
		$view->render();
	}

	public function crear() {
		$view = $this->newView('Catalogo\CrearPerfil');
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->opciones = json_encode($this->helper('UsersAccount')->menuTree());
		$view->render();
	}

	public function apiGuardar() {
		$data = $this->getParams('data');
		$data['opcionesBloqueadas'] = json_encode($data['opcionesBloqueadas']);
		new Response('json', $this->getModel('Catalogo\Perfiles')->insert($data));
	}
}