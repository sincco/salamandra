<?php

class DashboardModel extends Sincco\Sfphp\Abstracts\Model {

	public function __construct() {
		parent::__construct('sae');
	}
	
	public function run( $query, $params = [] ) {
		return $this->connector->query( $query, $params );
	}

	public function connector() {
		return $this->connector;
	}

	public function getCompras() {
		$query = 'SELECT inv.CVE_ART, inv.DESCR, inv.EXIST, inv.COMP_X_REC,  inv.PEND_SURT, max(tmp.proveedor) proveedor,
			MAX(inv.FCH_ULTVTA) venta, Max(inv.FCH_ULTCOM) COMPRA, 
			SUM(tmp.vendidos) vendidos, SUM(tmp.comprados) comprados
			FROM INVE01 inv
			INNER JOIN (
				SELECT pf.CVE_ART, pf.CVE_DOC, f.CVE_CLPV,'' AS PROVEEDOR, MAX(P.NOMBRE) AS CLIENTE, sum(CANT) AS VENDIDOS, 0 AS COMPRADOS
				FROM PAR_FACTF01 pf
				INNER JOIN FACTF01 f ON pf.CVE_DOC=f.CVE_DOC
				INNER JOIN clie01 P ON f.CVE_CLPV=P.CLAVE
				WHERE f.STATUS='E' AND f.FECHA_DOC BETWEEN '2016-01-01' AND '2016-12-31'
				GROUP BY pf.CVE_ART, pf.CVE_DOC, f.CVE_CLPV
				UNION ALL
				SELECT pc.CVE_ART, c.CVE_DOC, C.CVE_CLPV, MAX(P.NOMBRE) AS PROVEEDOR, '' AS CLIENTE,0 AS VENDIDOS, sum(PC.CANT) AS COMPRADOS
				FROM COMPC01 c
				INNER JOIN PAR_COMPC01 PC ON c.CVE_DOC=PC.CVE_DOC
				INNER JOIN PROV01 P ON C.CVE_CLPV=P.CLAVE
				WHERE C.STATUS='E' AND C.FECHA_DOC BETWEEN '2016-01-01' AND '2016-12-31'
				GROUP BY pc.CVE_ART, c.CVE_DOC, C.CVE_CLPV
			) tmp ON tmp.cve_art = inv.cve_art
			GROUP BY inv.CVE_ART, inv.DESCR, inv.EXIST, inv.COMP_X_REC,  inv.PEND_SURT;'
		return $this->connector->query($query);
	}

}