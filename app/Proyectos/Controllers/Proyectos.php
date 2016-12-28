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
		$data = [];
		$tareas = $this->getModel('Proyectos\Tareas')->getByIdProyecto($this->getParams('idProyecto'));
		foreach ($tareas as $tarea) {
			$data[] = [
					'label'	=> $tarea['titulo'],
					'start'	=> $tarea['fechaInicioProyectado'],
					'end'	=> $tarea['fechaFinProyectado'],
					'class'	=> ''
				];
		}

		$gantt = new Gantt($data, array(
			'title'      => 'Demo',
			'cellwidth'  => 25,
			'cellheight' => 35,
			'class' => ''
		));

		$this->helper('UsersAccount')->checkLogin();
		$view = $this->newView('Proyectos\ProyectoGantt');
		$view->gantt = $gantt->render();
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}
}