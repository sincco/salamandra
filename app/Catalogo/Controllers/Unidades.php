<?php

class UnidadesController extends Sincco\Sfphp\Abstracts\Controller {
	public function index() {
		$view = $this->newView('Catalogo\UnidadesTabla');
		$view->datos = $this->getModel('Catalogo\Unidades')->getAll();
		$view->menus = $this->helper('Menus')->createMenus();
		$view->render();
	}
}