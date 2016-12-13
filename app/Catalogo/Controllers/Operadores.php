<?php

class OperadoresController extends Sincco\Sfphp\Abstracts\Controller {
	public function index() {
		$view = $this->newView('Catalogo\OperadoresTabla');
		$view->datos = $this->getModel('Catalogo\Operadores')->getAll();
		$view->menus = $this->helper('Menus')->createMenus();
		$view->render();
	}
}