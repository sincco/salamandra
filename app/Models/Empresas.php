<?php

class EmpresasModel extends Sincco\Sfphp\Abstracts\Model {

	public function getAll() {
		return $this->connector->query( 'SELECT empresa, razonSocial, estatus FROM empresas ORDER BY empresa ASC' );
	}

	public function insert( $data ) {
		$campos = [];
		$variables = [];
		foreach ( $data as $campo => $valor ){
			$campos[] = $campo;
			$variables[] = ":" . $campo;
		}

		$campos		= implode( ",", $campos );
		$variables	= implode( ",", $variables );
		$query = 'INSERT INTO empresas (' . $campos . ' ) VALUES ( ' . $variables . ')';
		return $this->connector->query( $query, $data );
	}

	public function update( $set, $where ) {
		$campos = [];
		$condicion = [];
		foreach ( $set as $campo => $valor )
			$campos[] = $campo . "=:" . $campo;
		foreach ( $where as $campo => $valor )
			$condicion[] = $campo . "=:" . $campo;
		$campos = implode( ",", $campos );
		$condicion = implode( " AND ", $condicion );
		$query = 'UPDATE empresas 
			SET ' . $campos . ' WHERE ' . $condicion;
		$parametros = array_merge( $set, $where );
		return $this->connector->query( $query, $parametros );
	}

	public function delete( $data ) {
		$query = 'DELETE FROM empresas WHERE empresa = :empresa';
		return $this->connector->query( $query, [ 'empresa'=>$data ]);
	}

	public function empresasByUser( $userId ) {
		return $this->connector->query( 'SELECT emp.empresa, emp.razonSocial 
			FROM empresas emp 
			INNER JOIN usuariosEmpresas usr USING (empresa) 
			WHERE usr.userId = :userId AND emp.estatus = :estatus', 
		[ 'userId'=>$userId, 'estatus'=>'Activa' ] );
	}

}