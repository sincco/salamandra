<?php

class ClientesModel extends Sincco\Sfphp\Abstracts\Model {

	public function __construct() {
		parent::__construct('sae');
	}
	
	public function getAll() {
		return $this->connector->query('SELECT cli.*,ven.NOMBRE VENDEDOR FROM CLIE' . $_SESSION[ 'companiaClave' ] . ' cli LEFT JOIN VEND' . $_SESSION[ 'companiaClave' ] . ' ven ON ven.CVE_VEND=cli.CVE_VEND');
	}

	public function getById($id) {
		return $this->connector->query('SELECT * FROM CLIE' . $_SESSION[ 'companiaClave' ] . ' WHERE TRIM(CLAVE)=:cliente', ['cliente'=>$id]);
	}

	public function getContactos($cliente) {
		return $this->connector->query('SELECT LIST(EMAIL,\';\') EMAIL FROM CONTAC' . $_SESSION[ 'companiaClave' ] . ' WHERE EMAIL IS NOT NULL AND TRIM(CVE_CLIE) = :CLIENTE', [ 'CLIENTE'=>$cliente ]);
	}

	public function getByVendedor($vendedor) {
		return $this->connector->query('SELECT * FROM CLIE' . $_SESSION[ 'companiaClave' ] . ' WHERE TRIM(CVE_VEND) = :VENDEDOR', [ 'VENDEDOR'=>$vendedor ]);
	}

	public function setStatus($status, $cliente = FALSE) {
		$query = 'UPDATE CLIE' . $_SESSION[ 'companiaClave' ] . ' SET STATUS = :STATUS';
		if($cliente)
			$query .= ' WHERE TRIM(CLAVE) = :CLIENTE';
		return $this->connector->query($query, [ 'STATUS'=>$status, 'CLIENTE'=>$cliente ]);
	}
}