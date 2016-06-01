<?php

class ClientesModel extends Sincco\Sfphp\Abstracts\Model {

	public function __construct() {
		parent::__construct( 'sae' );
	}
	
	public function getAll() {
		return $this->connector->query( 'SELECT * FROM Clie' . $_SESSION[ 'companiaClave' ] . ' WHERE STATUS = :STATUS', [ 'STATUS'=>'A' ] );
	}
}