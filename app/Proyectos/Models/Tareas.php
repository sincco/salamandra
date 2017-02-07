<?php

class TareasModel extends Sincco\Sfphp\Abstracts\Model {

	public function getAll() {
		$query = 'SELECT * FROM proyectosTareas;';
		return $this->connector->query($query);
	}

	public function getByIdProyecto($data) {
		return $this->connector->query('SELECT  * FROM proyectosTareas WHERE idProyecto = :idProyecto
			ORDER BY fechaInicioProyectado', ['idProyecto'=>$data]);
	}

	public function getById($data) {
		return $this->connector->query('SELECT  * FROM proyectosTareas WHERE idTarea = :idTarea
			ORDER BY fechaInicioProyectado', ['idTarea'=>$data]);
	}

	public function getActualLog($data) {
		return $this->connector->query('SELECT idLoggeo, idTarea, idUsuario, UNIX_TIMESTAMP(inicio)*1000 inicio, UNIX_TIMESTAMP(fin)*1000 fin FROM proyectosTareasTiempos WHERE idUsuario = :idUsuario AND idTarea = :idTarea
			ORDER BY inicio', $data);
	}

	public function insert($data, $tabla='') {
		$campos = [];
		foreach ($data as $campo => $valor)
			$campos[] = $campo . "=:" . $campo;
		$campos = implode(",", $campos);
		$query = 'INSERT INTO proyectosTareas 
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
		$query = 'UPDATE proyectosTareas 
			SET ' . $campos . ' WHERE ' . $condicion;
		$parametros = array_merge($set, $where);
		return $this->connector->query($query, $parametros);
	}

}