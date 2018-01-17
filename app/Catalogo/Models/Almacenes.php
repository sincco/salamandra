<?php

class AlmacenesModel extends Sincco\Sfphp\Abstracts\Model {

	public function __construct() {
		parent::__construct('sae');
	}

	public function getProductos($almacen) {
		#return $this->connector->query('SELECT m.CVE_ART, i.DESCR, m.STATUS, m.EXIST FROM MULT' . $_SESSION[ 'companiaClave' ] . ' m INNER JOIN INVE' . $_SESSION[ 'companiaClave' ] . ' i ON m.CVE_ART=i.CVE_ART WHERE CVE_ALM = ' . $almacen);
		return $this->connector->query("SELECT 
			M.CVE_ART, I.DESCR, 
			MOV.CANT Cantidad_Vendida_2017, 
			I.FCH_ULTCOM Ultima_Compra, I.FCH_ULTVTA Ultima_Venta, 
			I.STOCK_MIN Minimo, I.STOCK_MAX Maximo
			FROM MULT" . $_SESSION[ 'companiaClave' ] . " M
			JOIN INVE" . $_SESSION[ 'companiaClave' ] . " I ON M.CVE_ART=I.CVE_ART
			LEFT JOIN (
			SELECT PF.NUM_ALM, PF.CVE_ART, SUM(PF.CANT) CANT 
			FROM PAR_FACTF" . $_SESSION[ 'companiaClave' ] . " PF
			INNER JOIN FACTF" . $_SESSION[ 'companiaClave' ] . " F ON PF.CVE_DOC=F.CVE_DOC
			WHERE F.STATUS = 'E' AND F.FECHA_DOC BETWEEN '01/01/2017' AND '12/31/2017' 
			AND PF.NUM_ALM=  " . $almacen . " 
			GROUP BY PF.NUM_ALM, PF.CVE_ART
			) MOV ON M.CVE_ALM=MOV.NUM_ALM AND M.CVE_ART = MOV.CVE_ART
			WHERE M.CVE_ALM =  " . $almacen . " AND CHAR_LENGTH(TRIM(I.CVE_ART)) = 5");
	}

}
