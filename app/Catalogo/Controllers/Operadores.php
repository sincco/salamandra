<?php

class OperadoresController extends Sincco\Sfphp\Abstracts\Controller {
	public function index() {
		$view = $this->newView('Catalogos\OperadoresTabla');
		$view->datos = $this->getModel('Catalogos\Operadores')->getAll();
		$view->menus = $this->helper('Menus')->createMenus();
		$view->render();
	}
}