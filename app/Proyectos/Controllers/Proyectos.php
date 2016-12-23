<?php

use \Sincco\Tools\Debug;
use \Sincco\Sfphp\Response;

class ProyectosController extends Sincco\Sfphp\Abstracts\Controller {

	public function index() {
		$this->helper('UsersAccount')->checkLogin();
		$view = $this->newView('Proyectos\ProyectosTabla');
		$view->proyectos = $this->getModel('Salamandra')
					->table('proyectos')
					->getData();
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function nuevo() {
		$this->helper('UsersAccount')->checkLogin();
		$view = $this->newView('Proyectos\ProyectoNuevo');
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function tareas() {
		$this->helper('UsersAccount')->checkLogin();
		$view = $this->newView('Proyectos\ProyectoTareas');
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->proyectos = $this->getModel('Proyectos\Proyectos')->getById($this->getParams('idProyecto'));
		$view->tareas = $this->getModel('Proyectos\Tareas')->getByIdProyecto($this->getParams('idProyecto'));
		$view->idProyecto = $this->getParams('idProyecto');
		$view->render();
	}

}