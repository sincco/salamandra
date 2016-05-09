<?php

class UsuariosModel extends Sincco\Sfphp\Abstracts\Model {

	public function getAll() {
		return $this->query( 'SELECT userName, userEmail FROM __usersControl ORDER BY userName ASC' );
	}

	public function loadByUserName( $name ) {
		return $this->query( 'SELECT userName, userEmail FROM __usersControl WHERE userName = :name', array( 'name'=>$name ) );
	}

}