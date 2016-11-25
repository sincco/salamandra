<?php

class OperadoresModel extends Sincco\Sfphp\Abstracts\Model {

	public function getAll() {
		$query = 'SELECT * FROM operadores;';
		return $this->connector->query( $query );
	}

	public function getById( $data ) {
		return $this->connector->query('SELECT  * FROM operadores WHERE idSocio = :idSocio
			ORDER BY nombre');
	}

	public function insert($data) {
		$campos = [];
		foreach ($data as $campo => $valor)
			$campos[] = $campo . "=:" . $campo;
		$campos = implode( ",", $campos );
		$query = 'INSERT INTO operadores 
			SET ' . $campos;
		return $this->connector->query($query, $data );
	}

	public function update($set,$where ) {
		$campos = [];
		$condicion = [];
		foreach ( $set as $campo => $valor )
			$campos[] = $campo . "=:" . $campo;
		foreach ( $where as $campo => $valor )
			$condicion[] = $campo . "=:" . $campo;
		$campos = implode( ",", $campos );
		$condicion = implode( " AND ", $condicion );
		$query = 'UPDATE operadores 
			SET ' . $campos . ' WHERE ' . $condicion;
		$parametros = array_merge($set, $where);
		return $this->connector->query($query, $parametros );
	}

}