<?php

class OperadoresModel extends Sincco\Sfphp\Abstracts\Model {

	public function getAll() {
		$query = 'SELECT * FROM operadores;';
		return $this->connector->query($query);
	}

	public function getById($data) {
		return $this->connector->query('SELECT  * FROM operadores WHERE idOperador = :idOperador
			ORDER BY nombre', ['idOperador'=>$data]);
	}

	public function getBy($data) {
		$where = [];
		foreach ($data as $campo => $valor)
			$where[] = $campo . "=:" . $campo;
		return $this->connector->query('SELECT  * FROM operadores WHERE ' . implode(",", $where), $data);
	}

	public function update($set,$where) {
		$campos = [];
		$condicion = [];
		foreach ($set as $campo => $valor)
			$campos[] = $campo . "=:" . $campo;
		foreach ($where as $campo => $valor)
			$condicion[] = $campo . "=:" . $campo;
		$campos = implode(",", $campos);
		$condicion = implode(" AND ", $condicion);
		$query = 'UPDATE operadores 
			SET ' . $campos . ' WHERE ' . $condicion;
		$parametros = array_merge($set, $where);
		return $this->connector->query($query, $parametros);
	}

}