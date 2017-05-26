<?php

class RecetasModel extends Sincco\Sfphp\Abstracts\Model {
	public function insert($data,$table=false) {
		$ingredientes = $data[ 'ingredientes' ];
		unset($data[ 'ingredientes' ]);

		$query = "INSERT INTO produccionRecetas VALUES (NULL,:claveProducto,:descripcionProducto,:unidadMedida,'Activo');";
		$idReceta = $this->connector->query($query, $data);
		if($idReceta) {
			foreach ($ingredientes as $ingrediente) {
				$ingrediente[ 'receta' ] = $idReceta;
				$query = "INSERT INTO produccionRecetasDetalle VALUES (:receta,:clave,:descripcion,:cantidad,:costo);";
				var_dump($query,$ingrediente);
				#$this->connector->query($query, $ingrediente);
			}
		}
		return $idReceta;
	}
}