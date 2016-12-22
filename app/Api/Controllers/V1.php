<?php

use \Sincco\Sfphp\Response;

class V1Controller extends Sincco\Sfphp\Abstracts\Controller {
	public function pedidosDetalle() {
		new Response('json', $this->getModel('Ventas\Pedidos')->getDetalleDia($this->getParams('fecha')));
	}

	public function pedidoEntregasProgramadas() {
		switch ($this->getRequest()['method']) {
			case 'GET':
				new Response('json', $this->getModel('Transporte\Envios')->getEntregasPedido($this->getParams('pedido'),$this->getParams('producto')));
				break;
			case 'POST':
				var_dump($this->getParams());
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
}