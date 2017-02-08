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
		$view->logs = $this->getModel('Proyectos\Tareas')->getActualLog(['idUsuario'=>$userData['userId'], 'idTarea'=>intval($this->getParams('idTarea'))]);
		$view->idProyecto = $this->getParams('idProyecto');
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

	public function formato() {
		$this->helper('UsersAccount')->checkLogin();
		$view = $this->newView('Proyectos\ProyectoFormato');
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->proyectos = $this->getModel('Proyectos\Proyectos')->getById($this->getParams('idProyecto'));
		$view->tareas = $this->getModel('Proyectos\Tareas')->getByIdProyecto($this->getParams('idProyecto'));
		$view->idProyecto = $this->getParams('idProyecto');
		$view->render();
	}

	public function cotizacion() {
		$this->helper('UsersAccount')->checkLogin();
		$userData = $this->helper('UsersAccount')->getUserData();

		$model = $this->getModel('Salamandra');
		$info = $model->proyectos()->join('proyectosTareas tar', 'tar.idProyecto = maintable.idProyecto')->join('proyectosCotizacion cot', 'cot.idTarea = tar.idTarea')->where('idTarea', $this->getParams('idTarea'), '=', 'tar')->fields('idProyecto')->fields('titulo')->fields('idTarea', 'tar')->fields('titulo tarea', 'tar')->fields('idProyectoCotizacion', 'cot')->getData();

		$idProyectoCotizacion = $info[0];
		$model->init();
		$cotizacion = $model->proyectosCotizacionDetalle()->where('idProyectoCotizacion', $idProyectoCotizacion['idProyectoCotizacion'])->getData();

		$view = $this->newView('Proyectos\ProyectoCotizacion');
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->info = $info;
		$view->cotizacion = $cotizacion;
		$view->render();
	}

	public function cotizar() {
		$model = $this->getModel('Salamandra');
		$tareas = $model->proyectosTareas()->fields('idTarea')->where('idTarea', $this->getParams('idTarea'))->getData();
		foreach ($tareas as $_tarea) {
			$model->proyectosCotizacion()->insert($_tarea);
		}
	}

	public function cotiza() {
		$model = $this->getModel('Salamandra');
		foreach ($this->getParams('data') as $_data) {
			foreach ($_data['productos'] as $producto) {
				$model->init();
				$existe = $model->proyectosCotizacionDetalle()->where('idProyectoCotizacion', $_data['idProyectoCotizacion'])->where('descripcion', $producto[1])->getData();
				if (count($existe) > 0) {

				} else {
					if (trim($producto[0]) != '') {
						$_producto['idProyectoCotizacion'] = $_data['idProyectoCotizacion'];
						$_producto['producto'] = $producto[0];
						$_producto['descripcion'] = $producto[1];
						$_producto['unidad'] = $producto[2];
						$_producto['precio'] = $producto[3];
						$_producto['cantidad'] = $producto[4];
						$model->init();
						$id = $model->proyectosCotizacionDetalle()->insert($_producto);
					}
				}
			}
		}
		new Response('json', ['respuesta'=>true]);
	}
}