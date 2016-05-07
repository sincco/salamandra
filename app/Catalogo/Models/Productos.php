<?php

class ProductosModel extends Sincco\Sfphp\Abstracts\Model {

	public function __construct() {
		parent::__construct('sae');
	}
	
	public function getAll() {
		return $this->query("SELECT * FROM Inve01");
	}
}