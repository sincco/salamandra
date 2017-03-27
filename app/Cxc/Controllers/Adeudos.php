<?php

use \Sincco\Tools\Debug;
use \Sincco\Sfphp\Config\Reader;
use \Sincco\Sfphp\Response;

class AdeudosController extends Sincco\Sfphp\Abstracts\Controller {

	public function index() {
		$this->helper('UsersAccount')->checkLogin();
		$mdlAdeudos = $this->getModel('Cxc\Adeudos');
		$view = $this->newView('Cxc\AdeudosTabla');
		if (strlen($this->getParams('fechaInicio')) >0) {
			$view->adeudos = $mdlAdeudos->getAdeudos(false, $this->getParams('fechaInicio'), $this->getParams('fechaFin'));	
		} else {
			$view->adeudos = $mdlAdeudos->getAdeudos();
		}
		$view->clientes = $mdlAdeudos->getNotificaciones();
		$view->fechaInicio = $this->getParams('fechaInicio');
		$view->fechaFin = $this->getParams('fechaFin');
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function apiNotificar() {
		$apiElastic = Reader::get('elasticemail');
		if(file_exists(PATH_ROOT.'/html/img/logo_cliente_mail.jpg'))
				$logo = 'html/img/logo_cliente_mail.jpg';
			else
				$logo = 'html/img/logo.jpg';
		$request = $this->getRequest();
		$clientes = $this->getParams('clientes');
		$mdlClientes = $this->getModel('Catalogo\Clientes');
		$mdlAdeudos = $this->getModel('Cxc\Adeudos');

		// Si es una peticion de linea de comando, se procesan todos los clientes
		if($request[ 'method' ] == 'CLI') {
			$clientes = [];
			$_SESSION['companiaClave'] = $this->getParams('empresa');
			$_clientes = $mdlAdeudos->getAdeudos(TRUE);
			foreach ($_clientes as $_cliente) {
				$clientes[ $_cliente[ 'CVE_CLIE' ] ] = array("1"=>trim($_cliente[ 'CVE_CLIE' ]) , "2"=>trim($_cliente[ 'NOMBRE' ]), "3"=>trim($_cliente[ 'CVE_VEND' ]), "4"=>trim($_cliente[ 'CORREO_VENDEDOR' ]));
			}
		}

		$avisos = [ 'primer'=>0, 'segundo'=>0, 'tercer'=>0 ];
		$actual = 0;
		$enviar = TRUE;

		foreach ($clientes as $_cliente) {
			$actual++;
			if($request[ 'method' ] == 'CLI')
				echo 'Cliente ' . $actual . ' de ' . count($clientes) . ' (enviar ' . ($enviar ? 'si' : 'no') . ') vendedor ' . $_cliente[ 4 ] . PHP_EOL;

			$emails = $mdlClientes->getContactos($_cliente[ 1 ]);
			if(is_null($emails[ 0 ][ 'EMAIL' ]))
				continue;

			if($apiElastic[ 'test' ] == "1")
				$emails = 'ivan.miranda@sincco.com;riverojorgea@gmail.com;';
			else
				$emails = $emails[ 0 ][ 'EMAIL' ] . ';pedro.acevedo@suhner.com;' . $_cliente[ 4 ] . ';';


			$primerAviso = [];
			$segundoAviso = [];
			$tercerAviso = [];
			$adeudosTotales = [];
			foreach ($mdlAdeudos->getAdeudoCliente($_cliente[ 1 ]) as $_adeudo) {
				$adeudosTotales[] = $_adeudo;
				if($_adeudo['ATRASO'] >=30 AND $_adeudo['ATRASO'] < 60)
					$primerAviso[] = $_adeudo;
				if($_adeudo['ATRASO'] >=60 AND $_adeudo['ATRASO'] < 89)
					$segundoAviso[] = $_adeudo;
				if($_adeudo['ATRASO'] >=90)
					$tercerAviso[] = $_adeudo;
			}

			if(count($primerAviso) > 0) {
				$view 			= $this->newView('Cxc\PrimerAviso');
				$view->general  = [ 'NOMBRE'=>$_cliente[ 2 ] ];
				$view->adeudos 	= $adeudosTotales;
				$view->logo 	= $logo;
				$html 			= $view->getContent();
				if($enviar)
					$this->helper('ElasticEmail')->send($emails, '1er Aviso de adeudo C.' . $_cliente[ 1 ] . ' V.' . $_cliente[ 3 ], '', $html, $apiElastic[ 'from' ], APP_COMPANY);
				$avisos[ 'primer' ] ++;
				if($apiElastic[ 'test' ] == "1")
					$enviar = FALSE;
			}
			if(count($segundoAviso) > 0) {
				$view 			= $this->newView('Cxc\SegundoAviso');
				$view->general  = [ 'NOMBRE'=>$_cliente[ 2 ] ];
				$view->adeudos 	= $adeudosTotales;
				$view->logo 	= $logo;
				$html 			= $view->getContent();
				if($enviar)
					$this->helper('ElasticEmail')->send($emails, '2o Aviso de adeudo C.' . $_cliente[ 1 ] . ' V.' . $_cliente[ 3 ], '', $html, $apiElastic[ 'from' ], APP_COMPANY);
				$avisos[ 'segundo' ] ++;
				if($apiElastic[ 'test' ] == "1")
					$enviar = FALSE;
			}
			if(count($tercerAviso) > 0) {
				$view 			= $this->newView('Cxc\TercerAviso');
				$view->general  = [ 'NOMBRE'=>$_cliente[ 2 ] ];
				$view->adeudos 	= $adeudosTotales;
				$view->logo 	= $logo;
				$html 			= $view->getContent();
				if($enviar)
					$this->helper('ElasticEmail')->send($emails, '3er Aviso de adeudo C.' . $_cliente[ 1 ] . ' V.' . $_cliente[ 3 ], '', $html, $apiElastic[ 'from' ], APP_COMPANY);
				$avisos[ 'tercer' ] ++;
				if($apiElastic[ 'test' ] == "1")
					$enviar = FALSE;
			}

		}
		echo json_encode([ 'avisos'=>$avisos ]);
	}

	public function apiBloquear() {
		$request = $this->getRequest();
		$codigo = $this->getParams('auth');

		if($request[ 'method' ] != 'CLI' && ! $this->helper('Google2Step')->validaCodigo('GEAKO2IJW4PKLBXF', $codigo)){
			new Response('json', [ 'status'=>FALSE, 'error'=>'El código de seguridad no es válido' ]);
			return FALSE;
		}

		$apiElastic = Reader::get('elasticemail');
		
		$enviar = TRUE;
		if($apiElastic[ 'test' ] == "1")
			$enviar = FALSE;

		if(file_exists(PATH_ROOT.'/html/img/logo_cliente_mail.jpg'))
				$logo = 'html/img/logo_cliente_mail.jpg';
			else
				$logo = 'html/img/logo.jpg';
		$clientes = $this->getParams('clientes');

		// Si es una peticion de linea de comando, se procesan todos los clientes
		if($request[ 'method' ] == 'CLI') {
			$clientes = [];
			$_SESSION['companiaClave'] = $this->getParams('empresa');
			$_clientes = $this->getModel('Cxc\Adeudos')->getAdeudos();
			foreach ($_clientes as $_cliente) {
				$clientes[ $_cliente[ 'CVE_CLIE' ] ] = array("1"=>trim($_cliente[ 'CVE_CLIE' ]) , "2"=>trim($_cliente[ 'NOMBRE' ]), "3"=>trim($_cliente[ 'CVE_VEND' ]), "4"=>trim($_cliente[ 'CORREO_VENDEDOR' ]));
			}
		}

		$actual = 0;

		// Debug::log($clientes);

		foreach ($clientes as $_cliente) {
			$actual++;
			if($request[ 'method' ] == 'CLI')
				echo 'Bloqueando cliente ' . $_cliente[ 1 ] . '-' . $_cliente[ 2 ] . ' - ' . $actual . ' de ' . count($clientes) . ' (enviar ' . ($enviar ? 'si' : 'no') . ') vendedor ' . $_cliente[ 4 ] . PHP_EOL;
			$this->getModel('Catalogo\Clientes')->setStatus('S', $_cliente[ 1 ]);

			if($apiElastic[ 'test' ] == "1")
				$emails = 'ivan.miranda@sincco.com;riverojorgea@gmail.com;';
			else
				$emails = $emails[ 0 ][ 'EMAIL' ] . ';pedro.acevedo@suhner.com;' . $_cliente[ 4 ] . ';';
		}

		if($request[ 'method' ] == 'CLI')
			echo 'Terminado';
		else
			new Response('json', [ 'status'=>TRUE, 'bloqueos'=>count($clientes) ]);
	}

}