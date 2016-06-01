<?php

class ProveedoresModel extends Sincco\Sfphp\Abstracts\Model {

	public function __construct() {
		parent::__construct( 'sae' );
	}
	
	public function getAll() {
		return $this->connector->query( 'SELECT * FROM Prov' . $_SESSION[ 'companiaClave' ] . ' WHERE STATUS = :STATUS', [ 'STATUS'=>'A' ] );
	}
}