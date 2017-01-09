<?php

class EnviosModel extends Sincco\Sfphp\Abstracts\Model
{

	public function getEntregasFecha($year, $month)
	{
		return $this->connector->query('SELECT DISTINCT fechaEntrega FROM entregas WHERE EXTRACT(YEAR FROM fechaEntrega) = :year AND EXTRACT(MONTH FROM fechaEntrega) = :month',['year'=>$year, 'month'=>$month]);
	}

	public function getEntregasDia()
	{
		return $this->connector->query('SELECT ent.*, noEco unidad, nombre operador FROM entregas ent INNER JOIN unidades USING (idUnidad) INNER JOIN operadores USING (idOperador) WHERE ent.fechaEntrega = CURDATE();');
	}

	public function getEntregasPedido($pedido, $producto)
	{
		$query = 'SELECT ent.*, noEco unidad, concat(concat(clave,\'|\'),nombre) operador FROM entregas ent INNER JOIN unidades USING (idUnidad) INNER JOIN operadores USING (idOperador) WHERE ent.pedido = :pedido AND ent.producto = :producto;';
		$params['pedido'] = $pedido;
		$params['producto'] = $producto;
		return $this->connector->query($query, $params);
	}

	public function getEntregasProgramadas($pedido, $producto)
	{
		$query = 'SELECT SUM(cantidad) cantidad FROM entregas ent WHERE ent.pedido = :pedido AND ent.producto = :producto;';
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
