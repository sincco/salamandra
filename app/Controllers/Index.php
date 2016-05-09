<?php
# pwd db 4nRcDsfSAQ62C8Lt

use \Sincco\Login\Login;
use \Sincco\Sfphp\Request;

/**
 * Captura de petición al home
 */
class IndexController extends Sincco\Sfphp\Abstracts\Controller {
	/**
	 * Validar si el usuario está loggeado para accesar al dashboard
	 * @return none
	 */
	public function index() {
		if(! Login::isLogged() ) {
			$view = $this->newView( 'Login' );
			echo $view->render();
		} else
			Request::redirect( 'dashboard' );
	}
}