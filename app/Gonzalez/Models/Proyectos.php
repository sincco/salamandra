<?php

class ProyectosModel extends Sincco\Sfphp\Abstracts\Model {

	public function getAll() {
		$query = 'SELECT * FROM proyectos;';
		return $this->connector->query($query);
	}

	public function getById($data) {
		return $this->connector->query('SELECT  * FROM proyectos WHERE idProyecto = :idProyecto
			ORDER BY clave', ['idProyecto'=>$data]);
	}

	public function getByClave($data) {
		return $this->connector->query('SELECT  * FROM proyectos WHERE clave = :clave
			ORDER BY clave', ['clave'=>$data]);
	}

	public function insert($data, $tabla='') {
		$campos = [];
		foreach ($data as $campo => $valor)
			$campos[] = $campo . "=:" . $campo;
		$campos = implode(",", $campos);
		$query = 'INSERT INTO proyectos 
			SET ' . $campos;
		return $this->connector->query($query, $data);
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
		$query = 'UPDATE proyectos 
			SET ' . $campos . ' WHERE ' . $condicion;
		$parametros = array_merge($set, $where);
		return $this->connector->query($query, $parametros);
	}

}