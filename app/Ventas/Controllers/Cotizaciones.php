<?php

use \Sincco\Sfphp\Response;

class CotizacionesController extends Sincco\Sfphp\Abstracts\Controller {

	public function index() {
		$model = $this->getModel('Ventas\Cotizaciones');
		$view = $this->newView('Ventas\CotizacionesTabla');
		$view->cotizaciones = $model->getAll();
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function agregar() {
		$mdlProductos	= $this->getModel('Catalogo\Productos');
		$mdlClientes	= $this->getModel('Catalogo\Clientes');
		$view 			= $this->newView('Ventas\CotizacionesAgregar');
		$view->menus 	= $this->helper('UsersAccount')->createMenus();
		$view->precios 	= $mdlProductos->getListaPrecios();
		$user = unserialize($_SESSION[ 'sincco\login\controller']);
		if (intval((isset($_SESSION[ 'extraFiltroClientes' ]) ? $_SESSION[ 'extraFiltroClientes' ] : 0) == 1)) {
			$view->clientes = $mdlClientes->getByVendedor($user[ 'userName' ]);
		}
		else {
			$view->clientes = $mdlClientes->getAll();
		}
		$view->render();
	}

	public function previo() {
		$model = $this->getModel('Ventas\Cotizaciones');
		$view 			= $this->newView('Ventas\CotizacionesPrevio');
		$data 			= $model->getById($this->getParams('id'));
		$view->cotizacion  = $data[ 0 ];
		$view->detalle  = $data;
		$view->render();
	}

	public function apiEnviar() {
		$model = $this->getModel('Ventas\Cotizaciones');
		$model->update([ 'estatus'=>'Enviada' ], [ 'cotizacion'=>$this->getParams('id') ]);
		$view 			= $this->newView('Ventas\CotizacionesPrevio');
		$data 			= $model->getById($this->getParams('id'));
		$view->cotizacion  = $data[ 0 ];
		$view->detalle  = $data;
		$cotizacion 	= $view->getContent();
		$respuesta 		= $this->helper('ElasticEmail')->send($this->getParams('email'), 'Cotización', '', $cotizacion, 'contacto@tricorp.com', APP_COMPANY);
		new Response('json', [ 'respuesta'=>$respuesta ]);
	}

	public function apiCancelar() {
		$model = $this->getModel('Ventas\Cotizaciones');
		$respuesta = $model->update([ 'estatus'=>'Cancelada' ], [ 'cotizacion'=>$this->getParams('id') ]);
		new Response('json', [ 'respuesta'=>$respuesta ]);
	}

	public function apiGuardar() {
		$model = $this->getModel('Ventas\Cotizaciones');
		$data = $this->getParams('data');
		$data = array_pop($data);
		if ($id = $model->insert($data)) {
			new Response('json', [ 'id'=>$id ]);
		}
	}
}