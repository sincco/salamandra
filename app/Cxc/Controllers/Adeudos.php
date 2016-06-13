<?php

use \Sincco\Tools\Debug;
use \Sincco\Sfphp\Config\Reader;

class AdeudosController extends Sincco\Sfphp\Abstracts\Controller {

	public function index() {
		$this->helper( 'UsersAccount' )->checkLogin();
		$mdlAdeudos = $this->getModel( 'Cxc\Adeudos' );
		$view = $this->newView('Cxc\AdeudosTabla');
		$view->adeudos = $mdlAdeudos->getAdeudos();;
		$view->clientes = $mdlAdeudos->getNotificaciones();;
		$view->menus = $this->helper( 'UsersAccount' )->createMenus();
		$view->render();
	}

	public function apiNotificar() {
		$apiElastic = Reader::get( 'elasticemail' );
		if( file_exists( file_exists( 'html/img/logo_cliente_mail.jpg' ) ) )
				$logo = 'html/img/logo_cliente_mail.jpg';
			else
				$logo = 'html/img/logo.jpg';

		$request = $this->getRequest();
		$clientes = $this->getParams( 'clientes' );
		$mdlClientes = $this->getModel( 'Catalogo\Clientes' );
		$mdlAdeudos = $this->getModel( 'Cxc\Adeudos' );

		// Si es una peticion de linea de comando, se procesan todos los clientes
		if( $request[ 'method' ] == 'CLI' ) {
			$_SESSION['companiaClave'] = $this->getParams( 'empresa' );
			$_clientes = $mdlAdeudos->getAdeudos();
			foreach ( $_clientes as $_cliente ) {
				$clientes[] = array( "1"=>trim( $_cliente[ 'CVE_CLIE' ] ) , "2"=>trim( $_cliente[ 'NOMBRE' ] ) );
			}
		}

		$avisos = [ 'primer'=>0, 'segundo'=>0, 'tercer'=>0 ];
		$actual = 0;

		foreach ( $clientes as $_cliente ) {
			
			$actual++;
			if( $request[ 'method' ] == 'CLI' )
				echo 'Cliente ' . $actual . ' de ' . count($clientes) . PHP_EOL;

			$emails = $mdlClientes->getContactos( $_cliente[ 1 ] );
			if(is_null( $emails[ 0 ][ 'EMAIL' ] ) )
				continue;

			if( $apiElastic[ 'test' ] == "1" )
				$emails = 'ivan.miranda@sincco.com;riverojorgea@gmail.com';
			else
				$emails = $emails[ 0 ][ 'EMAIL' ] . ';pedro.acevedo@suhner.com';

			$enviar = FALSE;

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
				$view->logo 	= $logo;
				$html 			= $view->getContent();
				// if( $enviar )
					// $this->helper( 'ElasticEmail' )->send( $emails, '1er Aviso de adeudo', '', $html, $apiElastic[ 'from' ], APP_COMPANY );
				$avisos[ 'primer' ] ++;
				if( $apiElastic[ 'test' ] == "1" )
					$enviar = FALSE;
			}
			if( count( $segundoAviso ) > 0 ) {
				$view 			= $this->newView( 'Cxc\SegundoAviso' );
				$view->general  = [ 'NOMBRE'=>$_cliente[ 2 ] ];
				$view->adeudos 	= $segundoAviso;
				$view->logo 	= $logo;
				$html 			= $view->getContent();
				// if( $enviar )
					// $this->helper( 'ElasticEmail' )->send( $emails, '2o Aviso de adeudo', '', $html, $apiElastic[ 'from' ], APP_COMPANY );
				$avisos[ 'segundo' ] ++;
				if( $apiElastic[ 'test' ] == "1" )
					$enviar = FALSE;
			}
			if( count( $tercerAviso ) > 0 ) {
				$view 			= $this->newView( 'Cxc\TercerAviso' );
				$view->general  = [ 'NOMBRE'=>$_cliente[ 2 ] ];
				$view->adeudos 	= $tercerAviso;
				$view->logo 	= $logo;
				$html 			= $view->getContent();
				// if( $enviar )
					// $this->helper( 'ElasticEmail' )->send( $emails, '3er Aviso de adeudo', '', $html, $apiElastic[ 'from' ], APP_COMPANY );
				$avisos[ 'tercer' ] ++;
				if( $apiElastic[ 'test' ] == "1" )
					$enviar = FALSE;
			}

		}
		echo json_encode( [ 'avisos'=>$avisos ] );
	}

}