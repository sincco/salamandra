<?php

class ControlPedidosModel extends Sincco\Sfphp\Abstracts\Model {

	public function getAll() {
		$query = 'SELECT empresa, pedido, estatus FROM pedidosEstatus';
		return $this->connector->query($query, ['empresa'=>$_SESSION['companiaClave']]);
	}
}