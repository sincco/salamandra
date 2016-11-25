<?php

class UnidadesController extends Sincco\Sfphp\Abstracts\Controller {
	public function index() {
		$view = $this->newView('Catalogos\UnidadesTabla');
		$view->datos = $this->getModel('Catalogos\Unidades')->getAll();
		$view->socios = $this->getModel('Catalogos\Socios')->getAll();
		$view->menus = $this->helper('Menus')->createMenus();
		$view->render();
	}
}