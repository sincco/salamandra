<?php

use \Sincco\Tools\Debug;

class AdeudosController extends Sincco\Sfphp\Abstracts\Controller {

	public function index() {
		$mdlAdeudos = $this->getModel( 'Cxc\Adeudos' );
		$view = $this->newView('Cxc\AdeudosTabla');
		$view->adeudos = $mdlAdeudos->getAdeudos();;
		$view->clientes = $mdlAdeudos->getNotificaciones();;
		$view->menus = $this->helper( 'UsersAccount' )->createMenus();
		$view->render();
	}

	public function apiNotificar() {
		$mdlClientes = $this->getModel( 'Catalogo\Clientes' );
		$mdlAdeudos = $this->getModel( 'Cxc\Adeudos' );
		$avisos = [ 'primer'=>0, 'segundo'=>0, 'tercer'=>0 ];

		foreach ( $this->getParams( 'clientes' ) as $_cliente ) {
			$emails = $mdlClientes->getContactos( $_cliente[ 1 ] );
			if(is_null( $emails[ 0 ][ 'EMAIL' ] ) )
				continue;
			$emails = $emails[ 0 ][ 'EMAIL' ] . ';pedro.acevedo@suhner.com';
			//$emails = 'ivan.miranda@sincco.com;riverojorgea@gmail.com';
			$enviar = TRUE;

			$primerAviso = [];
			$segundoAviso = [];
			$tercerAviso = [];
			foreach ( $mdlAdeudos->getAdeudoCliente( $_cliente[ 1 ] ) as $_adeudo ) {
				if( $_adeudo['ATRASO'] >=30 AND $_adeudo['ATRASO'] < 60 )
					$primerAviso[] = $_adeudo;
				if( $_adeudo['ATRASO'] >=60 AND $_adeudo['ATRASO'] < 89 )
					$segundoAviso[] = $_adeudo;
				if( $_adeudo['ATRASO'] >=90 )
					$tercerAviso[] = $_adeudo;
			}

			if( count( $primerAviso ) > 0 ) {
				$view 			= $this->newView( 'Cxc\PrimerAviso' );
				$view->general  = [ 'NOMBRE'=>$_cliente[ 2 ] ];
				$view->adeudos 	= $primerAviso;
				$html 			= $view->getContent();
				if( $enviar )
					$this->helper( 'ElasticEmail' )->send( $emails, '1er Aviso de adeudo', '', $html, 'contacto@suhner.com', APP_COMPANY );
				$avisos[ 'primer' ] ++;
			}
			if( count( $segundoAviso ) > 0 ) {
				$view 			= $this->newView( 'Cxc\SegundoAviso' );
				$view->general  = [ 'NOMBRE'=>$_cliente[ 2 ] ];
				$view->adeudos 	= $segundoAviso;
				$html 			= $view->getContent();
				if( $enviar )
					$this->helper( 'ElasticEmail' )->send( $emails, '2o Aviso de adeudo', '', $html, 'contacto@suhner.com', APP_COMPANY );
				$avisos[ 'segundo' ] ++;
			}
			if( count( $tercerAviso ) > 0 ) {
				$view 			= $this->newView( 'Cxc\TercerAviso' );
				$view->general  = [ 'NOMBRE'=>$_cliente[ 2 ] ];
				$view->adeudos 	= $tercerAviso;
				$html 			= $view->getContent();
				if( $enviar )
					$this->helper( 'ElasticEmail' )->send( $emails, '3er Aviso de adeudo', '', $html, 'contacto@suhner.com', APP_COMPANY );
				$avisos[ 'tercer' ] ++;
			}

		}
		echo json_encode( [ 'avisos'=>$avisos ] );
	}

}