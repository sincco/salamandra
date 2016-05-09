<?php

use \Sincco\Sfphp\Request;
use \Sincco\Login\Login;

/**
 * Dashboard del sistema
 */
class DashboardController extends Sincco\Sfphp\Abstracts\Controller {
	
	/**
	 * Acción por default
	 * @return none
	 */
	public function index() {
		$view = $this->newView( 'Dashboard' );
		$view->menus = $this->helper( 'UsersAccount' )->createMenus( $data );
		echo $view->render();
	}

}