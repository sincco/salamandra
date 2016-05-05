<?php

class ProveedoresController extends Sincco\Sfphp\Abstracts\Controller {

	public function index() {
		$mdlProveedores = $this->getModel('Catalogo\Proveedores');
		$data = $mdlProveedores->getAll();
		$view = $this->newView('Catalogo\ProductosTabla');
		$view->proveedores = $data;
		echo $view->render();
	}
}