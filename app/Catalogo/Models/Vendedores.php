<?php

class VendedoresModel extends Sincco\Sfphp\Abstracts\Model {

	public function __construct() {
		parent::__construct('sae');
	}

	public function getVendedor($vendedor) {
		return $this->connector->query("SELECT * FROM VEND" . $_SESSION[ 'companiaClave' ] . " WHERE TRIM(CVE_VEND)='" . $vendedor . "'");
	}

}