<?php

class ProductosModel extends Sincco\Sfphp\Abstracts\Model {

	public function __construct() {
		parent::__construct( 'sae' );
	}
	
	public function getAll() {
		return $this->query('SELECT * FROM Inve' . $_SESSION[ 'companiaClave' ] );
	}

	public function getByClave( $data, $listaPrecio = '1' ) {
		return $this->query('SELECT prd.CVE_ART, prd.DESCR, prd.EXIST, prd.STOCK_MIN, prd.UNI_MED, prd.COSTO_PROM, pre.CVE_PRECIO, pre.PRECIO
			FROM INVE' . $_SESSION[ 'companiaClave' ] . ' prd
			INNER JOIN PRECIO_X_PROD' . $_SESSION[ 'companiaClave' ] . ' pre USING ( CVE_ART )
			WHERE prd.CVE_ART = :DATA AND pre.CVE_PRECIO = :PRECIO', [ 'DATA'=>$data, 'PRECIO'=>$listaPrecio ] );
	}

	public function getListaPrecios() {
		return $this->query('SELECT * FROM precios' . $_SESSION[ 'companiaClave' ] );
	}
}