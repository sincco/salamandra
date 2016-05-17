<?php

use \Sincco\Sfphp\XML;
use \Sincco\Sfphp\Request;
use \Sincco\Sfphp\Response;

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
				'llave'=>$llave,
				'icono'=>$panel[ 'icono' ],
			];
		}
		$view = $this->newView( 'Dashboard' );
		$view->paneles = $paneles;
		$view->menus = $this->helper( 'UsersAccount' )->createMenus( $data );
		$view->render();
	}

	public function apiDetallePanelCols() {
		$xml = new XML( 'etc/config/dashboard.xml' );
		$panel = $xml->data[ Request::getParams( 'panel' ) ];
		$mdlDashboard = $this->getModel( 'Dashboard' );
		$columnas = array_keys( array_pop( $mdlDashboard->run( str_replace( "SELECT ", "SELECT FIRST 1 ", $panel[ 'detalle' ] ) ) ) );
		$respuesta = [];
		foreach ( $columnas as $_columna ) {
			$respuesta[] = [ 'field'=>$_columna, 'title'=>ucwords( str_replace( '_', ' ', $_columna ) ), 'sortable'=>true ];
		}
		new Response( 'json', $respuesta );
	}

	public function apiDetallePanel() {
		$xml = new XML( 'etc/config/dashboard.xml' );
		$panel = $xml->data[ Request::getParams( 'panel' ) ];
		$mdlDashboard = $this->getModel( 'Dashboard' );
		new Response( 'json', $mdlDashboard->run( $panel[ 'detalle' ] ) );
	}

}