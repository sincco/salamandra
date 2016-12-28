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

	public function loggeo() {
		$this->helper('UsersAccount')->checkLogin();
		$userData = $this->helper('UsersAccount')->getUserData();
		$view = $this->newView('Proyectos\ProyectoTareasLoggeo');
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->tareas = $this->getModel('Proyectos\Tareas')->getById($this->getParams('idTarea'));
		$view->actualLog = $this->getModel('Proyectos\Tareas')->getActualLog($userData['userId']);
		$view->render();
	}

	public function gantt() {
		$data = [];
		$proyecto = $this->getModel('Proyectos\Proyectos')->getById($this->getParams('idProyecto'));
		$tareas = $this->getModel('Proyectos\Tareas')->getByIdProyecto($this->getParams('idProyecto'));
		foreach ($tareas as $tarea) {
			$data[] = [
					'label'	=> wordwrap($tarea['titulo'], 20, '<br>'),
					'start'	=> $tarea['fechaInicioProyectado'],
					'end'	=> $tarea['fechaFinProyectado'],
					'class'	=> ''
				];
		}

		$gantt = new Gantt($data, array(
			'title'      => wordwrap($proyecto[0]['titulo'], 15, '<br>'),
			'cellwidth'  => 25,
			'cellheight' => 35,
			'class' => ''
		));

		$this->helper('UsersAccount')->checkLogin();
		$view = $this->newView('Proyectos\ProyectoGantt');
		$view->gantt = $gantt->render();
		$view->idProyecto = $this->getParams('idProyecto');
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}
}