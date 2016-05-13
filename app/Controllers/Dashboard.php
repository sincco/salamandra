<?php

use \Sincco\Sfphp\XML;

/**
 * Dashboard del sistema
 */
class DashboardController extends Sincco\Sfphp\Abstracts\Controller {
	
	/**
	 * Acción por default
	 * @return none
	 */
	public function index() {
		$xml = new XML( 'etc/config/dashboard.xml' );
		$paneles = [];
		$mdlDashboard = $this->getModel( 'Dashboard' );
		foreach ( $xml->data as $llave => $panel ) {
			$paneles[] = [ 
				'titulo'=>$panel[ 'titulo' ],
				'liga'=>$panel[ 'liga' ],
				'data'=>array_pop( $mdlDashboard->run( $panel[ 'resumen' ] ) ),
				'llave'=>$llave
			];
		}
		//var_dump($paneles);//die();
		$view = $this->newView( 'Dashboard' );
		$view->paneles = $paneles;
		$view->menus = $this->helper( 'UsersAccount' )->createMenus( $data );
		echo $view->render();
	}

}