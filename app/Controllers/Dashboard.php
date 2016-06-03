<?php

use \Sincco\Sfphp\XML;
use \Sincco\Sfphp\Response;
use \Sincco\Tools\Debug;

/**
 * Dashboard del sistema
 */
class DashboardController extends Sincco\Sfphp\Abstracts\Controller {
	
	/**
	 * Acción por default
	 * @return none
	 */
	public function index() {
		$this->helper( 'UsersAccount' )->checkLogin();
		$xml = new XML( 'etc/config/dashboard' . $_SESSION[ 'companiaClave' ] . '.xml' );
		
		$fechaInicio = ( is_null( $this->getParams( 'fechaInicio' ) ) ? date('Y-m-d') : $this->getParams( 'fechaInicio' ) );
		$fechaFin = ( is_null( $this->getParams( 'fechaFin' ) ) ? date('Y-m-d') : $this->getParams( 'fechaFin' ) );
		$params = [ 'fechaInicio'=>$fechaInicio, 'fechaFin'=>$fechaFin ];
		$paramsFront = [];

		$paneles = [];
		$mdlDashboard = $this->getModel( 'Dashboard' );
		foreach ( $xml->data as $llave => $panel ) {
			$query = $panel[ 'resumen' ];
			preg_match_all( '/:([a-zA-Z]+)/', $query, $_paramsFront[] );
			$resumen = $mdlDashboard->run( $query, $params );
			$paneles[] = [ 
				'titulo'=>$panel[ 'titulo' ],
				'liga'=>$panel[ 'liga' ],
				'data'=>array_pop( $resumen ),
				'llave'=>$llave,
				'icono'=>$panel[ 'icono' ],
			];
		}

		$objTmp = (object) array('aFlat' => array());
		array_walk_recursive($_paramsFront, create_function('&$v, $k, &$t', '$t->aFlat[] = $v;'), $objTmp);
		foreach ($objTmp->aFlat as $key => $value) {
			if(substr(trim($value), 0,1) == ":")
				$paramsFront[] = $value;
		}
		
		$view = $this->newView( 'Dashboard' );
		$view->paneles = $paneles;
		$view->menus = $this->helper( 'UsersAccount' )->createMenus();
		$view->filtros = $paramsFront;
		$view->render();
	}

	public function apiDetallePanelCols() {
		$xml = new XML( 'etc/config/dashboard' . $_SESSION[ 'companiaClave' ] . '.xml' );
		$panel = $xml->data[ $this->getParams( 'panel' ) ];
		$mdlDashboard = $this->getModel( 'Dashboard' );
		$detalle = $mdlDashboard->run( str_replace( "SELECT ", "SELECT FIRST 1 ", $panel[ 'detalle' ] ) );
		$columnas = array_keys( array_pop( $detalle ) );
		$respuesta = [];
		foreach ( $columnas as $_columna ) {
			$respuesta[] = [ 'field'=>$_columna, 'title'=>ucwords( str_replace( '_', ' ', $_columna ) ), 'sortable'=>true ];
		}
		new Response( 'json', $respuesta );
	}

	public function apiDetallePanel() {
		$xml = new XML( 'etc/config/dashboard' . $_SESSION[ 'companiaClave' ] . '.xml' );
		$panel = $xml->data[ $this->getParams( 'panel' ) ];
		$mdlDashboard = $this->getModel( 'Dashboard' );
		new Response( 'json', $mdlDashboard->run( $panel[ 'detalle' ] ) );
	}

}