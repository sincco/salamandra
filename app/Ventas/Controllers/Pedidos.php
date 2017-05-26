<?php

use \Sincco\Sfphp\Response;

class PedidosController extends Sincco\Sfphp\Abstracts\Controller {
	public function index() {
		$model = $this->getModel('Ventas\Pedidos');
		$salamandra = $this->getModel('Salamandra');
		$salamandra->init();
		$aprobados = $salamandra->pedidosestatus()->where('estatus','Autorizado')->getData();

		$pedidosTerminados = [];
		foreach ($aprobados as $pedido) {
			$model->init();
			$detalle = $model->getDetallePedido($pedido['pedido']);
			$salamandra->init();
			$producido = $salamandra->pedidosproductossurtidos()->where('pedido', trim($pedido['pedido']))->where('producido',1)->getData();
			if (count($detalle) == count($producido)) {
				$salamandra->init();
				$salamandra->pedidosestatus()->update(['estatus'=>'Terminado'],['empresa'=>$_SESSION['companiaClave'], 'pedido'=>$pedido['pedido']]);
			}
		}

		$model = $this->getModel('Ventas\Pedidos');
		$view = $this->newView('Ventas\PedidosTabla');
		$pedidos = $model->getAll();
		foreach ($pedidos as $key=>$pedido) {
			$salamandra->init();
			$estatus = $salamandra->pedidosestatus()->where('pedido',trim($pedido['CVE_DOC']))->getData();
			$estatus = $estatus[0];
			$pedidos[$key]['ESTATUS'] = $estatus['estatus'];

		}
		$view->pedidos = $pedidos;
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function porAprobar() {
		$view = $this->newView('Ventas\PedidosPorAprobar');
		$view->pedidos = $this->getModel('Salamandra')->pedidosEstatus()->where('estatus', 'Pendiente')->getData();
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function aprobados() {
		$view = $this->newView('Ventas\PedidosTabla');
		$aprobados = $this->getModel('Salamandra')->pedidosEstatus()->where('estatus', 'Autorizado')->getData();
		$pedidos = [];
		foreach ($aprobados as $_pedido) {
			$pedidos[] = $_pedido;
		}
		$view->pedidos = $this->getModel('Ventas\Pedidos')->getIn($pedidos);
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function remisiones() {
		$model = $this->getModel('Ventas\Pedidos');
		$view = $this->newView('Ventas\RemisionesTabla');
		$view->remisiones = $model->getRemisiones();
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function setPiezasSurtidas() {
		$salamandra = $this->getModel('Salamandra');
		$id = [];
		foreach ($this->getParams('piezas') as $pieza) {
			$salamandra->init();
			$data = [];
			$data['empresa'] = $_SESSION['companiaClave'];
			$data['pedido'] = $pieza[1];
			$data['producto'] = $pieza[2];
			$data['pieza'] = $pieza[3];
			$data['surtido'] = 1;
			$id[] = $salamandra->pedidospiezassurtidas()->insert($data);
		}
		new Response('json', ['id'=>$id]);
	}

	public function setProductosProducidos() {
		$salamandra = $this->getModel('Salamandra');
		$id = [];
		foreach ($this->getParams('productos') as $producto) {
			$salamandra->init();
			$data = [];
			$data['empresa'] = $_SESSION['companiaClave'];
			$data['pedido'] = $producto[1];
			$data['producto'] = $producto[2];
			$data['producido'] = 1;
			$id[] = $salamandra->pedidosproductossurtidos()->insert($data);
		}
		new Response('json', ['id'=>$id]);
	}

	public function piezasSurtir() {
		$view = $this->newView('Ventas\PiezasSurtirTabla');
		$view->menus = $this->helper('UsersAccount')->createMenus();

		$piezasSinSurtir = [];
		
		$salamandra = $this->getModel('Salamandra');
		$salamandra->init();
		$aprobados = $salamandra->pedidosestatus()->where('estatus','Autorizado')->getData();
		foreach ($aprobados as $pedido) {
			$salamandra->init();
			$model = $this->getModel('Ventas\Pedidos');
			$model->init();
			$detalle = $model->getDetallePedido($pedido['pedido']);
			foreach ($detalle as $key=>$producto) {
				$surtido = 'En Producción';

				$piezas = $salamandra->pedidospiezassurtidas()->where('producto', trim($producto['CVE_ART']))->where('pedido', trim($producto['CVE_DOC']))->getData();

				
				$salamandra->init();
				$receta = $salamandra->produccionrecetas()->where('producto', trim($producto['CVE_ART']))->getData();
				$receta = $receta[0];

				$salamandra->init();
				$receta = $salamandra->produccionrecetasdetalle()->where('receta', trim($receta['receta']))->getData();

				foreach ($receta as $pieza) {
					$salamandra->init();
					$surtido = $salamandra->pedidospiezassurtidas()->where('empresa', $_SESSION['companiaClave'])->where('pedido', $pedido['pedido'])->where('producto', $producto['CVE_ART'])->where('pieza', $pieza['producto'])->getData();
					if (count($surtido) == 0) {
						$pieza['pedido'] = $pedido['pedido'];
						$pieza['parent'] = $producto['CVE_ART'];
						$pieza['cantidad'] = $producto['CANT'] * $pieza['cantidad'];
						$piezasSinSurtir[] = $pieza;
					}
				}
			}
		}
		
		$view->piezas = $piezasSinSurtir;
		$view->render();
	}

	public function productosProducir() {
		$model = $this->getModel('Ventas\Pedidos');
		
		$salamandra = $this->getModel('Salamandra');
		$salamandra->init();
		$aprobados = $salamandra->pedidosestatus()->where('estatus','Autorizado')->getData();

		$productosProducir = [];
		foreach ($aprobados as $pedido) {
			$model->init();
			$detalle = $model->getDetallePedido($pedido['pedido']);
			foreach ($detalle as $producto) {

				$surtido = 'En Producción';

				$salamandra->init();
				$producido = $salamandra->pedidosproductossurtidos()->where('producto', trim($producto['CVE_ART']))->where('pedido', trim($producto['CVE_DOC']))->where('producido',1)->getData();
				if (count($producido) == 0) {

					$salamandra->init();
					$piezas = $salamandra->pedidospiezassurtidas()->where('producto', trim($producto['CVE_ART']))->where('pedido', trim($producto['CVE_DOC']))->getData();
					
					$salamandra->init();
					$receta = $salamandra->produccionrecetas()->where('producto', trim($producto['CVE_ART']))->getData();
					$receta = $receta[0];

					$salamandra->init();
					$receta = $salamandra->produccionrecetasdetalle()->where('receta', trim($receta['receta']))->getData();					
					$conteoPiezas = count($receta);

					if (count($piezas) != $conteoPiezas) {
						$surtido = 'Pendiente';
					} else {
						foreach ($piezas as $pieza) {
							if (intval($pieza['surtido'] )== 0) {
								$surtido = 'Pendiente';
							}
						}
					}
					if ($surtido == 'En Producción') {
						$productosProducir[] = $producto;
					}
				}
			}
		}

		$view = $this->newView('Ventas\ProductosProducirTabla');
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->productos = $productosProducir;
		$view->render();
	}
}