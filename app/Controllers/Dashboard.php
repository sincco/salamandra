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
		if(! Login::isLogged() )
			Request::redirect( 'login' );
		$view = $this->newView( 'Dashboard' );
		echo $view->render();
	}

}