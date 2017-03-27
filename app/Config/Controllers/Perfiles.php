<?php

use \Sincco\Sfphp\XML;
use \Sincco\Sfphp\Response;
use \Sincco\Tools\Debug;
use \Sincco\Sfphp\Messages;

/**
 * Dashboard del sistema
 */
class PerfilesController extends Sincco\Sfphp\Abstracts\Controller {
	
	/**
	 * Acción por default
	 * @return none
	 */
	public function index() {
		$view = $this->newView('Pefiles');
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}
}