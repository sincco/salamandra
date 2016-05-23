<?php


class EmpresasController extends Sincco\Sfphp\Abstracts\Controller {
	public function index() {
		$modelo = $this->getModel( 'Empresas' );
		$modelo->loadBy();
	}
}
	