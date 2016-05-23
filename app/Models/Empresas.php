<?php

class EmpresasModel extends Sincco\Sfphp\Abstracts\Model {

	public function getAll() {
		return $this->query( 'SELECT empresa, razonSocial FROM empresas ORDER BY empresa ASC' );
	}

	public function loadBy( $campos = [] ) {
		var_dump($campos);
	}

	public function loadByClave( $name ) {
		return $this->query( 'SELECT userName, userEmail FROM __usersControl WHERE userName = :name', array( 'name'=>$name ) );
	}

	public function empresasByUser( $userId ) {
		return $this->query( 'SELECT emp.empresa, emp.razonSocial 
			FROM empresas emp 
			INNER JOIN usuariosEmpresas usr USING (empresa) 
			WHERE usr.userId = :userId AND emp.estatus = :estatus', 
		[ 'userId'=>$userId, 'estatus'=>'Activa' ] );
	}

}