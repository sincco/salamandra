<?php

class AlmacenesModel extends Sincco\Sfphp\Abstracts\Model {

	public function __construct() {
		parent::__construct('sae');
	}

	public function getProductos($almacen) {
		#return $this->connector->query('SELECT m.CVE_ART, i.DESCR, m.STATUS, m.EXIST FROM MULT' . $_SESSION[ 'companiaClave' ] . ' m INNER JOIN INVE' . $_SESSION[ 'companiaClave' ] . ' i ON m.CVE_ART=i.CVE_ART WHERE CVE_ALM = ' . $almacen);
		return $this->connector->query('SELECT M.CVE_ART Clave_Articulo, I.DESCR Descriocion, SUM(PF.CANT) Cantidad_Vendida_2017, I.FCH_ULTCOM Ultima_Compra, I.FCH_ULTVTA Ultima_Venta, I.STOCK_MIN Minimo, I.STOCK_MAX Maximo
			FROM MULT' . $_SESSION[ 'companiaClave' ] . ' M
			INNER JOIN INVE' . $_SESSION[ 'companiaClave' ] . ' I ON M.CVE_ART=I.CVE_ART
			INNER JOIN PAR_FACTF' . $_SESSION[ 'companiaClave' ] . ' PF ON I.CVE_ART=PF.CVE_ART
			INNER JOIN FACTF' . $_SESSION[ 'companiaClave' ] . ' F ON PF.CVE_DOC=F.CVE_DOC
			WHERE F.STATUS = 'E' AND F.FECHA_DOC BETWEEN '01/01/2017' AND '12/31/2017'  AND CHAR_LENGTH(trim(M.CVE_ART)) = 5 AND CVE_ALM = ' . $almacen);'
			GROUP BY M.CVE_ART, I.DESCR, I.FCH_ULTCOM, I.FCH_ULTVTA, I.STOCK_MIN, I.STOCK_MAX
			ORDER BY I.DESCR ASC');
	}

}
