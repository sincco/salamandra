<?php

class EnviosModel extends Sincco\Sfphp\Abstracts\Model
{

	public function getEntregasPedido($pedido, $producto)
	{
		$query = 'SELECT * FROM entregas
			WHERE pedido = :pedido AND producto = :producto';
		$params['pedido'] = $pedido;
		$params['producto'] = $producto;
		return $this->connector->query($query, $params);
	}

	public function insert($data) {
		$campos = [];
		foreach ($data as $campo => $valor)
			$campos[] = $campo . "=:" . $campo;
		$campos = implode(",", $campos);
		$query = 'INSERT INTO entregas 
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
		$query = 'UPDATE entregas 
			SET ' . $campos . ' WHERE ' . $condicion;
		$parametros = array_merge($set, $where);
		return $this->connector->query($query, $parametros);
	}
}
