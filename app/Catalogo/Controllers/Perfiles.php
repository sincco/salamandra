<?php

class PerfilesController extends Sincco\Sfphp\Abstracts\Controller {
	
	public function crear() {
		$view = $this->newView('Catalogo\CrearPerfil');
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->opciones = json_encode($this->helper('UsersAccount')->menuTree());
		$view->render();
	}
}