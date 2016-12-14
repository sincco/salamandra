<?php

class UsuariosModel extends Sincco\Sfphp\Abstracts\Model {

	public function getAll() {
		return $this->connector->query('SELECT userName, userEmail FROM __usersControl ORDER BY userName ASC');
	}

	public function loadByUserName($name) {
		return $this->connector->query('SELECT usr.userId, usr.userName, usr.userEmail, ext.nombre, ext.idPerfil, ext.filtroClientes FROM __usersControl usr LEFT JOIN usuariosExtra ext USING (userId) WHERE userName = :name', array('name'=>$name));
	}

	public function empresasByUser($userId) {
		return $this->connector->query('SELECT emp.empresa, emp.razonSocial 
			FROM empresas emp 
			INNER JOIN usuariosEmpresas usr USING (empresa) 
			WHERE usr.userId = :userId AND emp.estatus = :estatus', 
		[ 'userId'=>$userId, 'estatus'=>'Activa' ]);
	}

	public function extraByUser($userId) {
		return $this->connector->query('SELECT * 
			FROM usuariosExtra 
			WHERE userId = :userId', 
		[ 'userId'=>$userId ]);
	}

	public function usuarioAEmpresas($userId) {
		$data = $this->connector->query('SELECT MAX(userId) userId FROM __usersControl');
		$userId = array_pop($data);
		$query = 'INSERT INTO usuariosEmpresas (userId, empresa)
		SELECT ' . $userId[ 'userId' ] . ', empresa FROM empresas';
		return $this->connector->query($query);
	}

	public function insExtra($data) {
		return $this->connector->query('INSERT INTO usuariosExtra (userId, idPerfil, filtroClientes) ' .
			' VALUES (:userId, :perfil, :filtroClientes)', $data);
	}

}