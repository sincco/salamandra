<?php

use \Sincco\Tools\Debug;
use \Sincco\Sfphp\Config\Reader;
use \Sincco\Sfphp\Response;

class BackorderController extends Sincco\Sfphp\Abstracts\Controller {

	public function index() {
		$this->helper( 'UsersAccount' )->checkLogin();
		$mdlAdeudos = $this->getModel( 'Cxc\Adeudos' );
		$view = $this->newView('Cxc\BAckorderTabla');
		$view->data = $mdlAdeudos->getBackOrder();
		$view->menus = $this->helper( 'UsersAccount' )->createMenus();
		$view->render();
	}

}