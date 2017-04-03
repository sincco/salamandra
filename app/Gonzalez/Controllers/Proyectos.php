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
						$_producto['idProyecto'] = intval($_data['idProyecto']);
						$_producto['seccion'] = trim($producto[0]);
						$_producto['hoja'] = floatval($producto[1]);
						$_producto['det'] = floatval($producto[2]);
						$_producto['subDet'] = floatval($producto[3]);
						$_producto['producto'] = trim($producto[4]);
						$_producto['descripcion'] = trim($producto[5]);
						$_producto['kgm'] = floatval($producto[6]);
						$_producto['kgpzs'] = floatval($producto[7]);
						$_producto['cant'] = floatval($producto[8]);
						$_producto['lng'] = floatval($producto[9]);
						$_producto['scrap'] = floatval($producto[10]);
						$_producto['pesoTotal'] = floatval($producto[11]);
						$_producto['precio'] = floatval($producto[12]);
						$_producto['subTotal'] = floatval($producto[13]);
						$_producto['cutFit'] = floatval($producto[14]);
						$_producto['ncMach'] = floatval($producto[15]);
						$_producto['conventionalMach'] = floatval($producto[16]);
						$_producto['welding'] = floatval($producto[17]);
						$_producto['finishing'] = floatval($producto[18]);
						$_producto['assyPacking'] = floatval($producto[19]);
						$_producto['laserService'] = floatval($producto[20]);
						$_producto['installation'] = floatval($producto[21]);
						$model->init();
						$id = $model->gzlzProyectosProductos()->insert($_producto);
					}
				}
			}
		}
		new Response('json', ['respuesta'=>true]);
	}

	public function apiProducto() {
		$productos = $this->getModel('Salamandra')->gzlzProductos()->where('CVE_ART', $this->getParams('data'))->getData();
		new Response('json', $productos);	
	}

	public function apiProductos() {
		$productos = $this->getModel('Salamandra')->gzlzProductos()->getData();
		new Response('json', $productos);	
	}
}