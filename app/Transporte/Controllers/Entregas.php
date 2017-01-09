<?php

use \Sincco\Sfphp\Response;
use \Sincco\Tools\Debug;

class EntregasController extends Sincco\Sfphp\Abstracts\Controller {
	public function index()
	{
		$this->helper('UsersAccount')->checkLogin();
		$view = $this->newView('Transporte\EntregasDia');
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->entregas = $this->getModel('Transporte\Envios')->getEntregasDia();
		$view->render();
	}

	public function apiCalendario()
	{
		$calendario = [];
		foreach ($this->getModel('Ventas\Pedidos')->getFechaPedido($this->getParams('year'), $this->getParams('month')) as $data) {
			$calendario[] = ['date'=>trim(str_replace('00:00:00', '', $data['FECHA_ENT'])), 'badge'=>false, 'title'=>'Entregas solicitadas', 'body'=>'<p class="lead">'.$data['NOMBRE'].'</p>', 'classname'=>'transporte-envio-solicitado'];
		}
		foreach ($this->getModel('Transporte\Envios')->getEntregasFecha($this->getParams('year'), $this->getParams('month')) as $data) {
			$calendario[] = ['date'=>trim(str_replace('00:00:00', '', $data['fechaEntrega'])), 'badge'=>true, 'title'=>'Entregas solicitadas', 'body'=>'<p class="lead">Envío Programado</p>', 'classname'=>''];
		}
		new Response('json', $calendario);
	}

	public function apiFecha()
	{
		new Response('json', $this->getModel('Ventas\Pedidos')->getPedidosDia($this->getParams('fecha')));
	}
}