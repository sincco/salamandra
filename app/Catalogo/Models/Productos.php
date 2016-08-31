<?php

class ProductosModel extends Sincco\Sfphp\Abstracts\Model {

	public function __construct() {
		parent::__construct( 'sae' );
	}
	
	public function getActivos() {
		$query = "SELECT i.CVE_ART,i.DESCR,i.UNI_MED,i.EXIST,i.PEND_SURT,i.COMP_X_REC,(EXIST + comp_x_rec) - PEND_SURT AS DISPONIBLES, SUBSTRING(cast(i.fch_ultvta as varchar(50)) FROM 1 for 10) ULTIMA_VENTA , p.PRECIO FROM INVE" . $_SESSION[ "companiaClave" ] . " i inner join precio_x_prod" . $_SESSION[ "companiaClave" ] . " p on i.cve_art = p.cve_art WHERE p.cve_precio =1 AND (I.CTRL_ALM <> 'OBS' OR I.CTRL_ALM IS NULL ) AND i.STATUS = 'A';";
		return $this->connector->query($query);
	}

	public function getByClave( $data, $listaPrecio = '1' ) {
		return $this->connector->query('SELECT prd.CVE_ART, prd.DESCR, prd.EXIST, prd.STOCK_MIN, prd.UNI_MED, prd.COSTO_PROM, pre.CVE_PRECIO, pre.PRECIO
			FROM INVE' . $_SESSION[ 'companiaClave' ] . ' prd
			INNER JOIN PRECIO_X_PROD' . $_SESSION[ 'companiaClave' ] . ' pre USING ( CVE_ART )
			WHERE prd.CVE_ART = :DATA AND pre.CVE_PRECIO = :PRECIO', [ 'DATA'=>$data, 'PRECIO'=>$listaPrecio ] );
	}

	public function getListaPrecios() {
		return $this->connector->query('SELECT * FROM precios' . $_SESSION[ 'companiaClave' ] );
	}
}