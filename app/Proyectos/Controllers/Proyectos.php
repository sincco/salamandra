<?php

use \Sincco\Tools\Debug;
use \Sincco\Sfphp\Response;
use \Sincco\Tools\Gantt;

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

	public function gantt() {
		$data = array();
		$data[] = array(
		'label' => 'Project 1',
		'start' => '2012-04-20', 
		'end'   => '2012-05-12',
		'class' => ''
		);

		$data[] = array(
		'label' => 'Project 2',
		'start' => '2012-04-22', 
		'end'   => '2012-05-22', 
		'class' => 'important',
		);

		$data[] = array(
		'label' => 'Project 3',
		'start' => '2012-05-25', 
		'end'   => '2012-06-20',
		'class' => 'urgent',
		);

		$gantti = new Gantt($data, array(
		'title'      => 'Demo',
		'cellwidth'  => 25,
		'cellheight' => 35,
		'class' => ''
		));

		$this->helper('UsersAccount')->checkLogin();
		$view = $this->newView('Proyectos\ProyectoGantt');
		$view->gantt = $gantti->render();
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}
}