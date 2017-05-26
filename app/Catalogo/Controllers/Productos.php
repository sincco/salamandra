<?php

use \Sincco\Sfphp\Response;

class ProductosController extends Sincco\Sfphp\Abstracts\Controller {

	public function index() {
		$this->helper('UsersAccount')->checkLogin();
		$mdlProductos = $this->getModel('Catalogo\Productos');
		$data = $mdlProductos->getActivos();
		$view = $this->newView('Catalogo\ProductosTabla');
		$view->productos = $data;
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function clientes() {
		$this->helper('UsersAccount')->checkLogin();
		$mdlProductos = $this->getModel('Catalogo\Productos');
		$data = $mdlProductos->getActivos();
		$view = $this->newView('Catalogo\ProductosClientes');
		$view->productos = $data;
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function cotizar() {
		$this->helper('UsersAccount')->checkLogin();
		$view = $this->newView('Catalogo\ProductosCotizar');
		$view->productos = $this->getModel('Salamandra')->cotizacionesDetalle()->where('precio', 0.01, '<=')->getData();
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function apiClave() {
		$mdlProductos = $this->getModel('Catalogo\Productos');
		$data = $mdlProductos->getByClave($this->getParams('data'), $this->getParams('listaPrecio'));
		new Response('json', $data);
	}

	public function apiCatalogo() {
		$mdlProductos = $this->getModel('Catalogo\Productos');
		$data = $mdlProductos->getActivos();
		new Response('json', $data);
	}

	public function apiBuscar() {
		$mdlFirebird = $this->getModel('Firebird');
		$campo = $this->getParams('campo');
		$valor = $this->getParams('valor');
		$comparacion = $this->getParams('comparacion');
		$data = $mdlFirebird
			->table('INVE' . $_SESSION[ 'companiaClave' ])
			->where($campo, $valor, $comparacion)
			->getData();
		new Response('json', $data);
	}

	public function receta() {
		$salamandra = $this->getModel('Salamandra');
		$salamandra->init();
		$receta = $salamandra->produccionrecetas()->where('producto', trim($this->getParams('producto')))->getData();
		$receta = $receta[0];
		$salamandra->init();
		$recetaDetalle = $salamandra->produccionrecetasdetalle()->where('receta', $receta['receta'])->getData();
		foreach ($recetaDetalle as $key=>$producto) {
			$surtido = 'Surtido';
			$salamandra->init();
			$piezas = $salamandra->pedidospiezassurtidas()->where('producto', trim($this->getParams('producto')))->where('pieza', trim($producto['producto']))->getData();
			if (count($piezas) == 0) {
				$surtido = 'Pendiente';
			} else {
				if(intval($piezas[0]['surtido']) == 0) {
					$surtido = 'Pendiente';
				}
			}
			$recetaDetalle[$key]['surtido'] = $surtido;
			$recetaDetalle[$key]['padre'] = trim($this->getParams('producto'));
			$recetaDetalle[$key]['pedido'] = trim($this->getParams('pedido'));
		}
		new Response('json', $recetaDetalle);
	}
}