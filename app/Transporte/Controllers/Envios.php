<?php

use \Sincco\Sfphp\Response;
use \Sincco\Tools\Debug;

class EnviosController extends Sincco\Sfphp\Abstracts\Controller {
	public function index()
	{
		$this->helper('UsersAccount')->checkLogin();
		$view = $this->newView('Transporte\EnviosCalendario');
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function apiCalendario()
	{
		$calendario = [];
		foreach ($this->getModel('Ventas\Pedidos')->getFechaPedido($this->getParams('year'), $this->getParams('month')) as $data) {
			$calendario[] = ['date'=>trim(str_replace('00:00:00', '', $data['FECHA_ENT'])), 'badge'=>false, 'title'=>'Entregas solicitadas', 'body'=>'<p class="lead">'.$data['NOMBRE'].'</p>', 'classname'=>'transporte-envio-solicitado'];
		}
		new Response('json', $calendario);
	}

	public function apiFecha()
	{
		new Response('json', $this->getModel('Ventas\Pedidos')->getPedidosDia($this->getParams('fecha')));
	}
}