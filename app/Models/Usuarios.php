<?php

class UsuariosModel extends Sincco\Sfphp\Abstracts\Model {

	public function getAll() {
		return $this->connector->query( 'SELECT userName, userEmail FROM __usersControl ORDER BY userName ASC' );
	}

	public function loadByUserName( $name ) {
		return $this->connector->query( 'SELECT userName, userEmail FROM __usersControl WHERE userName = :name', array( 'name'=>$name ) );
	}

	public function empresasByUser( $userId ) {
		return $this->connector->query( 'SELECT emp.empresa, emp.razonSocial 
			FROM empresas emp 
			INNER JOIN usuariosEmpresas usr USING (empresa) 
			WHERE usr.userId = :userId AND emp.estatus = :estatus', 
		[ 'userId'=>$userId, 'estatus'=>'Activa' ] );
	}

	public function usuarioAEmpresas( $userId ) {
		$userId = array_pop($this->connector->query( 'SELECT MAX(userId) userId FROM __usersControl' ));
		$query = 'INSERT INTO usuariosEmpresas ( userId, Empresa )
		SELECT ' . $userId[ 'userId' ] . ', empresa FROM empresas';
		return $this->connector->query( $query );
	}

}