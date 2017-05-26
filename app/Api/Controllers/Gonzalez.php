<?php

use \Sincco\Sfphp\Response;

class GonzalezController extends Sincco\Sfphp\Abstracts\Controller {

	public function proyectos() {
		$model = $this->getModel('Salamandra');
		$data = $this->getParams('data');
		switch ($this->getRequest()['method']) {
			case 'GET':
				new Response('json', ['respuesta'=>$model->getByClave($this->getParams('clave'))]);
				break;
			case 'POST':
				unset($data['idProyecto']);
				$model->init();
				$id = $model->gzlzProyectos()->insert($data);
				if ($id > 0) {
					$model->init();
					$tarea['idProyecto'] = $id;
					$tarea['estatus'] = 'Pendiente';
					$tarea['titulo'] = 'Cut & Fit';
					$model->gzlzProyectosTareas()->insert($tarea);
					$tarea['titulo'] = 'NC Mach';
					$model->insert($tarea);
					$tarea['titulo'] = 'Conventional Mach';
					$model->insert($tarea);
					$tarea['titulo'] = 'Welding';
					$model->insert($tarea);
					$tarea['titulo'] = 'Finishing';
					$model->insert($tarea);
					$tarea['titulo'] = 'Assy / Packing';
					$model->insert($tarea);
					$tarea['titulo'] = 'Laser Service';
					$model->insert($tarea);
					$tarea['titulo'] = 'Installation';
					$model->insert($tarea);
				}
				new Response('json', ['respuesta'=>$id]);
				break;
			case 'PUT':
				$where = ['idProyecto'=>$data['idProyecto']];
				unset($data['idProyecto']);
				$model->init();
				$model->gzlzProyectos()->update($data, $where);
				new Response('json', ['respuesta'=>true]);
				break;
			default:
				new Response('htmlstatuscode', 'Operacion no soportada');
				break;
		}
	}
}