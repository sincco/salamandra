<?php

use \Sincco\Sfphp\Response;

class V1Controller extends Sincco\Sfphp\Abstracts\Controller {

	public function almacenProductos() {
		switch ($this->getRequest()['method']) {
			case 'GET':
				new Response('json', $this->getModel('Catalogo\Almacenes')->getProductos($this->getParams('almacen')));
				break;
			default:
				new Response('json', 'Metodo no soportado');
				break;
		}
	}

	public function pedidosDetalle() {
		$data = [];
		$solicitados = $this->getModel('Ventas\Pedidos')->getDetalleDia($this->getParams('fecha'));
		foreach ($solicitados as $_data) {
			$programados = $this->getModel('Transporte\Envios')->getEntregasProgramadas($_data['CVE_DOC'], $_data['CVE_ART']);
			if (isset($programados[0]['cantidad'])) {
				if ($programados[0]['cantidad'] < $_data['CANT']) {
					$data[] = $_data;
				}
			} else {
				$data[] = $_data;
			}
		}
		new Response('json', $data);
	}

	public function pedidoEntregasProgramadas() {
		switch ($this->getRequest()['method']) {
			case 'GET':
				new Response('json', $this->getModel('Transporte\Envios')->getEntregasPedido($this->getParams('pedido'),$this->getParams('producto')));
				break;
			case 'POST':
				//new Response('json', $this->getModel('Transporte\Envios')->getEntregasPedido($this->getParams('pedido'),$this->getParams('producto')));
				break;
			default:
				# code...
				break;
		}
	}

	public function unidades() {
		switch ($this->getRequest()['method']) {
			case 'POST':
				$model = $this->getModel('Catalogo\Unidades');
				$data = $this->getParams('data');
				unset($data['idUnidad']);
				$id = $model->insert($data);
				new Response('json', ['respuesta'=>$id]);
				break;
			case 'PUT':
				$model = $this->getModel('Catalogo\Unidades');
				$data = $this->getParams('data');
				$where = ['idUnidad'=>$data['idUnidad']];
				unset($data['idUnidad']);
				$model->update($data, $where);
				new Response('json', ['respuesta'=>true]);
				break;
			default:
				new Response('htmlstatuscode', 'Operacion no soportada');
				break;
		}
	}

	public function operadores() {
		switch ($this->getRequest()['method']) {
			case 'POST':
				$model = $this->getModel('Catalogo\Operadores');
				$data = $this->getParams('data');
				unset($data['idOperador']);
				$id = $model->insert($data);
				new Response('json', ['respuesta'=>$id]);
				break;
			case 'PUT':
				$model = $this->getModel('Catalogo\Operadores');
				$data = $this->getParams('data');
				$where = ['idOperador'=>$data['idOperador']];
				unset($data['idOperador']);
				$model->update($data, $where);
				new Response('json', ['respuesta'=>true]);
				break;
			default:
				new Response('htmlstatuscode', 'Operacion no soportada');
				break;
		}
	}

	public function proyectos() {
		$model = $this->getModel('Proyectos\Proyectos');
		$data = $this->getParams('data');
		switch ($this->getRequest()['method']) {
			case 'GET':
				new Response('json', ['respuesta'=>$model->getByClave($this->getParams('clave'))]);
				break;
			case 'POST':
				unset($data['idProyecto']);
				$id = $model->insert($data);
				new Response('json', ['respuesta'=>$id]);
				break;
			case 'PUT':
				$where = ['idProyecto'=>$data['idProyecto']];
				unset($data['idProyecto']);
				$model->update($data, $where);
				new Response('json', ['respuesta'=>true]);
				break;
			default:
				new Response('htmlstatuscode', 'Operacion no soportada');
				break;
		}
	}

	public function proyectosTareas() {
		$model = $this->getModel('Proyectos\Tareas');
		$data = $this->getParams('data');
		switch ($this->getRequest()['method']) {
			case 'GET':
				new Response('json', ['respuesta'=>$model->getByClave($this->getParams('idProyecto'))]);
				break;
			case 'POST':
				unset($data['idTarea']);
				$id = $model->insert($data);
				new Response('json', ['respuesta'=>$id]);
				break;
			case 'PUT':
				$where = ['idTarea'=>$data['idTarea']];
				unset($data['idTarea']);
				$model->update($data, $where);
				new Response('json', ['respuesta'=>true]);
				break;
			default:
				new Response('htmlstatuscode', 'Operacion no soportada');
				break;
		}
	}

	public function proyectosAdjuntos() {
		switch ($this->getRequest()['method']) {
			case 'GET':
				$data = [];
				foreach (scandir(PATH_PROJECTS_FILES . '/' . $this->getParams('idProyecto')) as $file) {
					$info = finfo_file(finfo_open(FILEINFO_MIME_TYPE), PATH_PROJECTS_FILES . '/' . $this->getParams('idProyecto') . '/' . $file);
					switch ($info) {
						case 'image/jpeg':
						case 'image/gif':
						case 'image/png':
							$data[] = ['type'=>'image','path'=>'_proyectos/' . $this->getParams('idProyecto') . '/' . $file, 'name'=>$file];
							break;
						case 'directory':
							break;
						default:
							$data[] = ['type'=>'other','path'=>'_proyectos/' . $this->getParams('idProyecto') . '/' . $file, 'name'=>$file];
							break;
					}
				}
				new Response('json', ['files'=>$data]);
				break;
			default:
				new Response('htmlstatuscode', 'Operacion no soportada');
				break;
		}
	}

	public function envio() {
		$model = $this->getModel('Transporte\Envios');
		$data = $this->getParams('data');
		switch ($this->getRequest()['method']) {
			case 'GET':
				new Response('json', ['respuesta'=>$model->getEntregasPedido($this->getParams('pedido'), $this->getParams('producto'))]);
				break;
			case 'POST':
				$id = [];
				try {
					foreach ($data as $_data) {
						if($_data['cantidad'] > 0) {
							$_data['producto'] = explode('|', $_data['producto'])[0];
							$_data['idOperador'] = explode('|', $_data['idOperador'])[0];
							$_data['idOperador'] = $this->getModel('Catalogo\Operadores')->getBy(['clave'=>$_data['idOperador']])[0]['idOperador'];
							$_data['idUnidad'] = $this->getModel('Catalogo\Unidades')->getBy(['noEco'=>$_data['idUnidad']])[0]['idUnidad'];
							$id[] = $model->insert($_data);
						}
					}
				} catch (Exception $e) {
					$id = false;
				}
				if ($id) {
					new Response('json', ['respuesta'=>true, 'ids'=>$id]);
				} else {
					new Response('json', ['respuesta'=>false]);
				}
				break;
			case 'PUT':
				$where = ['idTarea'=>$data['idTarea']];
				unset($data['idTarea']);
				$model->update($data, $where);
				new Response('json', ['respuesta'=>true]);
				break;
			default:
				new Response('htmlstatuscode', 'Operacion no soportada');
				break;
		}
	}

	public function detallePedido() {
		$model = $this->getModel('Ventas\Pedidos');
		$data = $this->getParams('data');
		switch ($this->getRequest()['method']) {
			case 'GET':
				new Response('json', $model->getDetallePedido($this->getParams('pedido')));
				break;
			default:
				new Response('htmlstatuscode', 'Operacion no soportada');
				break;
		}
	}

	public function pedidos() {
		$model = $this->getModel('Ventas\Pedidos');
		$data = $this->getParams('data');
		switch ($this->getRequest()['method']) {
			case 'POST':
				switch ($this->getParams('seccion')) {
					case 'por_aprobar':
						$enProceso = $this->getModel('Ventas\ControlPedidos')->getAll();
						foreach ($model->getNotIn($enProceso) as $_pedido) {
							$this->getModel('Ventas\ControlPedidos')->insert(['empresa'=>$_SESSION['companiaClave'], 'pedido'=>$_pedido['CVE_DOC'], 'estatus'=>'Pendiente'], 'pedidosEstatus');
						}
						new Response('json', ['actualizado'=>true]);
						break;
					
					default:
						new Response('json', $model->getAll());
						break;
				}
				break;
			default:
				new Response('htmlstatuscode', 'Operacion no soportada');
				break;
		}	
	}
}