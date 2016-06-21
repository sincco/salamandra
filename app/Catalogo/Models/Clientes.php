<?php

class ClientesModel extends Sincco\Sfphp\Abstracts\Model {

	public function __construct() {
		parent::__construct( 'sae' );
	}
	
	public function getAll() {
		return $this->connector->query( 'SELECT * FROM Clie' . $_SESSION[ 'companiaClave' ] . ' WHERE STATUS = :STATUS', [ 'STATUS'=>'A' ] );
	}

	public function getContactos( $cliente ) {
		return $this->connector->query('SELECT LIST(EMAIL,\';\') EMAIL FROM CONTAC' . $_SESSION[ 'companiaClave' ] . ' WHERE EMAIL IS NOT NULL AND TRIM(CVE_CLIE) = :CLIENTE', [ 'CLIENTE'=>$cliente ]);
	}

	public function getByVendedor( $vendedor ) {
		return $this->connector->query( 'SELECT * FROM Clie' . $_SESSION[ 'companiaClave' ] . ' WHERE STATUS = :STATUS AND TRIM(CVE_VEND) = :VENDEDOR', [ 'STATUS'=>'A', 'VENDEDOR'=>$vendedor ] );
	}
}