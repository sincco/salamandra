<?php

class ProcesosModel extends Sincco\Sfphp\Abstracts\Model {

	public function insert( $data ) {
		foreach ( $data as $registro ) {
			$registro[ 'empresa' ] = $_SESSION[ 'companiaClave' ];
			$query = "INSERT INTO almacenes VALUES (:empresa,:almacen,:descripcion,'Activo');";
			$idReceta = $this->connector->query( $query, $registro );
		}
		return $idReceta;
	}

}