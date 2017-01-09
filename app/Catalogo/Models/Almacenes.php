<?php

class AlmacenesModel extends Sincco\Sfphp\Abstracts\Model {

	public function __construct() {
		parent::__construct('sae');
	}

	public function getProductos($almacen) {
		return $this->connector->query('SELECT m.CVE_ART, i.DESCR, m.STATUS, m.EXIST FROM MULT' . $_SESSION[ 'companiaClave' ] . ' m INNER JOIN INVE' . $_SESSION[ 'companiaClave' ] . ' i ON m.CVE_ART=i.CVE_ART WHERE CVE_ALM = ' . $almacen);
	}

}