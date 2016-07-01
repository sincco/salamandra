<?php

class ClientesController extends Sincco\Sfphp\Abstracts\Controller {
	public function index() {
		$this->helper( 'UsersAccount' )->checkLogin();
		$mdlClientes = $this->getModel('Catalogo\Clientes');
		$user = unserialize( $_SESSION[ 'sincco\login\controller'] );
		if( intval( ( isset( $_SESSION[ 'extraFiltroClientes' ] ) ? $_SESSION[ 'extraFiltroClientes' ] : 0 ) == 1 ) )
			$data = $mdlClientes->getByVendedor( $user[ 'userName' ] );
		else
			$data = $mdlClientes->getAll();
		$view = $this->newView('Catalogo\ClientesTabla');
		$view->perfil = $_SESSION[ 'extraPerfil' ];
		$view->clientes = $data;
		$view->adeudos = $this->getModel( 'Cxc\Adeudos' )->getNotificaciones();
		$view->menus = $this->helper( 'UsersAccount' )->createMenus();
		$view->render();
	}

	public function apiStatus() {
		$request = $this->getRequest();
		$codigo = $this->getParams( 'auth' );
		$status = $this->getParams( 'status' );

		if( ! $this->helper( 'Google2Step' )->validaCodigo('GEAKO2IJW4PKLBXF', $codigo) ){
			new Response( 'json', [ 'status'=>FALSE, 'error'=>'El código de seguridad no es válido' ] );
			return FALSE;
		}

		$clientes = $this->getParams( 'clientes' );

		foreach ( $clientes as $_cliente ) {
			$this->getModel( 'Catalogo\Clientes' )->setStatus( $status, $_cliente[ 1 ] );
		}

		new Response( 'json', [ 'status'=>TRUE, 'activados'=>count($clientes) ] );
	}
}