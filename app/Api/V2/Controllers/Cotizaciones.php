<?php

use \Sincco\Sfphp\Response;

class CotizacionesController extends Sincco\Sfphp\Abstracts\Controller {

	public function detalle() {
		switch ($this->getRequest()['method']) {
			case 'PUT':

				new Response('json', ['respuesta'=>$this->getModel('Salamandra')->cotizacionesDetalle()->_update(['precio'=>$this->getParams('precio')], ['idCotizacionDetalle'=>$this->getParams('id')])]);
				break;
			default:
				new Response('json', 'Metodo no soportado');
				break;
		}
	}
}