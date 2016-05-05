<?php

class ClientesModel extends Sincco\Sfphp\Abstracts\Model {

	// public function __construct() {
	// 	parent::__construct('sae');
	// }
	
	public function getAll() {
		return $this->query("SELECT * FROM Clie01 WHERE STATUS = :STATUS", array("STATUS"=>"A"));
	}
}