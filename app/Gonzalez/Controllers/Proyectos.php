<?php

use \Sincco\Tools\Debug;
use \Sincco\Sfphp\Response;
use \Sincco\Tools\Gantt;

class ProyectosController extends Sincco\Sfphp\Abstracts\Controller {

	public function index() {
		$this->helper('UsersAccount')->checkLogin();
		$view = $this->newView('Gonzalez\ProyectosTabla');
		$view->proyectos = $this->getModel('Salamandra')->gzlzProyectos()->getData();
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function nuevo() {
		$this->helper('UsersAccount')->checkLogin();
		$view = $this->newView('Gonzalez\ProyectoNuevo');
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function tareas() {
		$model = $this->getModel('Salamandra');
		$this->helper('UsersAccount')->checkLogin();
		$view = $this->newView('Gonzalez\ProyectoTareas');
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$model->init();
		$view->proyectos = $model->gzlzProyectos()->where('idProyecto', $this->getParams('idProyecto'))->getData();
		$model->init();
		$view->tareas = $model->gzlzProyectosTareas()->where('idProyecto', $this->getParams('idProyecto'))->getData();
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
		$info = $model->gzlzProyectos()->where('idProyecto', $this->getParams('idProyecto'))->getData();
		$model->init();
		$productos = $model->gzlzProyectosProductos()->where('idProyecto', $this->getParams('idProyecto'))->getData();

		$view = $this->newView('Gonzalez\ProyectoCotizacion');
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->proyectos = $info;
		$view->productos = $productos;
		$view->render();
	}

	public function cotiza() {
		$model = $this->getModel('Salamandra');
		foreach ($this->getParams('data') as $_data) {
			foreach ($_data['productos'] as $producto) {
				$model->init();
				$existe = $model->gzlzProyectosProductos()->where('idProyecto', $_data['idProyecto'])->where('descripcion', $producto[1])->getData();
				if (count($existe) > 0) {

				} else {
					if (trim($producto[0]) != '') {
						$_producto['idProyecto'] = $_data['idProyecto'];
						$_producto['producto'] = $producto[0];
						$_producto['descripcion'] = $producto[1];
						$_producto['precio'] = $producto[2];
						$_producto['hoja'] = $producto[4];
						$_producto['det'] = $producto[5];
						$_producto['subDet'] = $producto[6];
						$_producto['cutFit'] = floatval($producto[7]);
						$_producto['ncMach'] = floatval($producto[8]);
						$_producto['conventionalMach'] = floatval($producto[9]);
						$_producto['welding'] = floatval($producto[10]);
						$_producto['finishing'] = floatval($producto[11]);
						$_producto['assyPacking'] = floatval($producto[12]);
						$_producto['laserService'] = floatval($producto[13]);
						$_producto['installation'] = floatval($producto[14]);
						$model->init();
						$id = $model->gzlzProyectosProductos()->insert($_producto);
					}
				}
			}
		}
		new Response('json', ['respuesta'=>true]);
	}
}