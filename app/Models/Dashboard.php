<?php

class DashboardModel extends Sincco\Sfphp\Abstracts\Model {

	public function __construct() {
		parent::__construct('sae');
	}
	
	public function run($query, $params = []) {
		return $this->connector->query($query, $params);
	}

	public function connector() {
		return $this->connector;
	}

	public function getCompras($inicio='2016-01-01', $fin='2016-12-31') {
		$query = "SELECT inv.CVE_ART, inv.DESCR, inv.EXIST, inv.COMP_X_REC,  inv.PEND_SURT, max(tmp.PROVEEDOR) PROVEEDOR,
			MAX(inv.FCH_ULTVTA) VENTA, Max(inv.FCH_ULTCOM) COMPRA, 
			SUM(tmp.vendidos) VENDIDOS, SUM(tmp.comprados) COMPRADOS
			FROM INVE" . $_SESSION[ 'companiaClave' ] . " inv
			INNER JOIN (
				SELECT pf.CVE_ART, pf.CVE_DOC, f.CVE_CLPV,'' AS PROVEEDOR, MAX(P.NOMBRE) AS CLIENTE, sum(CANT) AS VENDIDOS, 0 AS COMPRADOS
				FROM PAR_FACTF" . $_SESSION[ 'companiaClave' ] . " pf
				INNER JOIN FACTF" . $_SESSION[ 'companiaClave' ] . " f ON pf.CVE_DOC=f.CVE_DOC
				INNER JOIN clie" . $_SESSION[ 'companiaClave' ] . " P ON f.CVE_CLPV=P.CLAVE
				WHERE f.STATUS='E' AND f.FECHA_DOC BETWEEN '" . $inicio ."' AND '". $fin . "'
				GROUP BY pf.CVE_ART, pf.CVE_DOC, f.CVE_CLPV
				UNION ALL
				SELECT pc.CVE_ART, c.CVE_DOC, C.CVE_CLPV, MAX(P.NOMBRE) AS PROVEEDOR, '' AS CLIENTE,0 AS VENDIDOS, sum(PC.CANT) AS COMPRADOS
				FROM COMPC" . $_SESSION[ 'companiaClave' ] . " c
				INNER JOIN PAR_COMPC" . $_SESSION[ 'companiaClave' ] . " PC ON c.CVE_DOC=PC.CVE_DOC
				INNER JOIN PROV" . $_SESSION[ 'companiaClave' ] . " P ON C.CVE_CLPV=P.CLAVE
				WHERE C.STATUS='E' AND C.FECHA_DOC BETWEEN '" . $inicio ."' AND '". $fin . "'
				GROUP BY pc.CVE_ART, c.CVE_DOC, C.CVE_CLPV
			) tmp ON tmp.cve_art = inv.cve_art
			GROUP BY inv.CVE_ART, inv.DESCR, inv.EXIST, inv.COMP_X_REC,  inv.PEND_SURT;";
		return $this->connector->query($query);
	}

}