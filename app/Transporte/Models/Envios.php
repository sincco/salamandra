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
}
