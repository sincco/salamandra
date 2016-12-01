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
}