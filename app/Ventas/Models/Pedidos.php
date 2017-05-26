<?php

class PedidosModel extends Sincco\Sfphp\Abstracts\Model {

	public function __construct() {
		parent::__construct('sae');
	}
	
	public function getAll() {
		$query = 'SELECT TRIM(f.CVE_DOC) CVE_DOC, c.NOMBRE CLIENTE ,  f.FECHA_DOC,  f.IMPORTE, COALESCE(c.CVE_VEND,0) CVE_VEND, v.NOMBRE VENDEDOR
			FROM FACTP' . $_SESSION['companiaClave'] . ' f
			INNER JOIN CLIE' . $_SESSION['companiaClave'] . ' c ON c.CLAVE=f.CVE_CLPV
			LEFT JOIN VEND' . $_SESSION['companiaClave'] . ' v ON v.CVE_VEND=f.CVE_VEND
			WHERE f.status= :estatus AND f.TIP_DOC_SIG IS NULL';
		$params['estatus'] = 'E';
		$user = unserialize($_SESSION['sincco\login\controller']);
		if(intval((isset($_SESSION['extraFiltroClientes']) ? $_SESSION['extraFiltroClientes'] : 0) == 1)) {
			$query .= ' AND trim(c.CVE_VEND) = :vendedor ';
			$params['vendedor'] = $user['userName'];
		}
		return $this->connector->query($query, $params);
	}

	public function getNotIn($pedidos=[]) {
		$pedido = [];
		foreach ($pedidos as $_pedido) {
			$pedido[] = "'" . $_pedido['pedido'] . "'";
		}
		$query = 'SELECT TRIM(f.CVE_DOC) CVE_DOC, c.NOMBRE CLIENTE ,  f.FECHA_DOC,  f.IMPORTE, COALESCE(c.CVE_VEND,0) CVE_VEND, v.NOMBRE VENDEDOR
			FROM FACTP' . $_SESSION['companiaClave'] . ' f
			INNER JOIN CLIE' . $_SESSION['companiaClave'] . ' c ON c.CLAVE=f.CVE_CLPV
			LEFT JOIN VEND' . $_SESSION['companiaClave'] . ' v ON v.CVE_VEND=f.CVE_VEND
			WHERE f.status= :estatus AND f.TIP_DOC_SIG IS NULL';
		if (count($pedido)) {
			$query .= '	AND TRIM(f.CVE_DOC) NOT IN (' . implode(",", $pedido) . ')';
		}
		$params['estatus'] = 'E';
		return $this->connector->query($query, $params);
	}

	public function getIn($pedidos=[]) {
		$pedido = [];
		foreach ($pedidos as $_pedido) {
			$pedido[] = "'" . $_pedido['pedido'] . "'";
		}
		$query = 'SELECT TRIM(f.CVE_DOC) CVE_DOC, c.NOMBRE CLIENTE ,  f.FECHA_DOC,  f.IMPORTE, COALESCE(c.CVE_VEND,0) CVE_VEND, v.NOMBRE VENDEDOR
			FROM FACTP' . $_SESSION['companiaClave'] . ' f
			INNER JOIN CLIE' . $_SESSION['companiaClave'] . ' c ON c.CLAVE=f.CVE_CLPV
			LEFT JOIN VEND' . $_SESSION['companiaClave'] . ' v ON v.CVE_VEND=f.CVE_VEND
			WHERE f.status= :estatus AND f.TIP_DOC_SIG IS NULL	AND TRIM(f.CVE_DOC) IN (' . implode(",", $pedido) . ')';
		$params['estatus'] = 'E';
		return $this->connector->query($query, $params);
	}

	public function _getIn($pedidos=[]) {
		$pedido = [];
		foreach ($pedidos as $_pedido) {
			$pedido[] = "'" . $_pedido['pedido'] . "'";
		}
		$query = 'SELECT TRIM(f.CVE_DOC) CVE_DOC, c.CLAVE CVE_CLIENTE, c.NOMBRE CLIENTE ,  f.FECHA_DOC,  f.IMPORTE, COALESCE(c.CVE_VEND,0) CVE_VEND, v.NOMBRE VENDEDOR
			FROM FACTP' . $_SESSION['companiaClave'] . ' f
			INNER JOIN CLIE' . $_SESSION['companiaClave'] . ' c ON c.CLAVE=f.CVE_CLPV
			LEFT JOIN VEND' . $_SESSION['companiaClave'] . ' v ON v.CVE_VEND=f.CVE_VEND
			WHERE TRIM(f.CVE_DOC) IN (' . implode(",", $pedido) . ')';
		return $this->connector->query($query);
	}

	public function getRemisiones() {
		$query = '
			SELECT CVE_DOC,NOMBRE,FECHA_DOC,FECHA_ENT,CAN_TOT,STR_OBS,REMISION,FACTURA,SUM(IMPORTE_PAGO) PAGO, CAN_TOT - SUM(IMPORTE_PAGO) RESTA FROM (
				SELECT
				TRIM(f.CVE_DOC) FACTURA, FAC.CVE_CLPV CLIENTE, FAC.FECHA_DOC FACTURA_FECHA,FAC.CAN_TOT FACTURA_TOTAL,
				CTA.IMPORTE IMPORTE_PAGO,
				NULL REMISION,NULL REMISION_FECHA, NULL REMISION_TOTAL,
				PED.*
				FROM FACTF' . $_SESSION['companiaClave'] . ' FAC
				INNER JOIN CUEN_DET' . $_SESSION['companiaClave'] . ' CTA ON (CTA.NO_FACTURA=FAC.CVE_DOC)
				INNER JOIN FACTP' . $_SESSION['companiaClave'] . ' PED ON (FAC.DOC_ANT=PED.CVE_DOC)
				WHERE FAC.STATUS=\'E\' AND FAC.TIP_DOC_ANT =\'P\'
				UNION ALL
				SELECT
				FAC.CVE_DOC FACTURA, FAC.CVE_CLPV CLIENTE, FAC.FECHA_DOC FACTURA_FECHA,FAC.CAN_TOT FACTURA_TOTAL,
				CTA.IMPORTE IMPORTE_PAGO,
				REM.CVE_DOC REMISION,REM.FECHA_DOC REMISION_FECHA, REM.CAN_TOT REMISION_TOTAL,
				PED.*
				FROM FACTF' . $_SESSION['companiaClave'] . ' FAC
				INNER JOIN CUEN_DET' . $_SESSION['companiaClave'] . ' CTA ON (CTA.NO_FACTURA=FAC.CVE_DOC)
				INNER JOIN FACTR' . $_SESSION['companiaClave'] . ' REM ON (REM.CVE_DOC=FAC.DOC_ANT)
				INNER JOIN FACTP' . $_SESSION['companiaClave'] . ' PED ON (REM.DOC_ANT=PED.CVE_DOC)
				WHERE FAC.STATUS=\'E\' AND FAC.TIP_DOC_ANT =\'R\' AND REM.TIP_DOC_ANT =\'P\'
				UNION ALL
				SELECT 
					NULL FACTURA, NULL CLIENTE, NULL FACTURA_FECHA, NULL FACTURA_TOTAL,
					0 IMPORTE_PAGO,
					NULL REMISION,NULL REMISION_FECHA, NULL REMISION_TOTAL,
					PED.*
				FROM FACTP' . $_SESSION['companiaClave'] . ' PED 
				WHERE PED.STATUS=\'E\' AND PED.DOC_SIG IS NULL
			) TMP 
			INNER JOIN OBS_DOCF' . $_SESSION['companiaClave'] . ' OBS ON TMP.CVE_OBS=OBS.CVE_OBS
			INNER JOIN CLIE' . $_SESSION['companiaClave'] . ' CLI ON TMP.CVE_CLPV=CLI.CLAVE
			GROUP BY CVE_DOC,NOMBRE,FECHA_DOC,FECHA_ENT,CAN_TOT,STR_OBS,REMISION,FACTURA
			ORDER BY CVE_DOC';
		return $this->connector->query($query, $params);
	}

	public function getFechaPedido($year, $month) {
		$query = 'SELECT f.*, c.*
			FROM FACTP' . $_SESSION['companiaClave'] . ' f
			INNER JOIN CLIE' . $_SESSION['companiaClave'] . ' c ON c.CLAVE=f.CVE_CLPV
			LEFT JOIN vend' . $_SESSION['companiaClave'] . ' v ON v.CVE_VEND=f.CVE_VEND
			WHERE f.status= :estatus AND EXTRACT(YEAR FROM f.FECHA_ENT) = :year AND EXTRACT(MONTH FROM f.FECHA_ENT) = :month';
		$params['estatus'] = 'E';
		$params['year'] = $year;
		$params['month'] = $month;
		$user = unserialize($_SESSION['sincco\login\controller']);
		if(intval((isset($_SESSION['extraFiltroClientes']) ? $_SESSION['extraFiltroClientes'] : 0) == 1)) {
			$query .= ' AND trim(c.CVE_VEND) = :vendedor ';
			$params['vendedor'] = $user['userName'];
		}
		return $this->connector->query($query, $params);
	}

	public function getPedidosDia($date) {
		$query = 'SELECT f.*, c.*
			FROM FACTP' . $_SESSION['companiaClave'] . ' f
			INNER JOIN CLIE' . $_SESSION['companiaClave'] . ' c ON c.CLAVE=f.CVE_CLPV
			LEFT JOIN vend' . $_SESSION['companiaClave'] . ' v ON v.CVE_VEND=f.CVE_VEND
			WHERE f.status= :estatus AND f.FECHA_ENT = :fecha';
		$params['estatus'] = 'E';
		$params['fecha'] = $date;
		$user = unserialize($_SESSION['sincco\login\controller']);
		if(intval((isset($_SESSION['extraFiltroClientes']) ? $_SESSION['extraFiltroClientes'] : 0) == 1)) {
			$query .= ' AND trim(c.CVE_VEND) = :vendedor ';
			$params['vendedor'] = $user['userName'];
		}
		return $this->connector->query($query, $params);
	}

	public function getDetallePedido($pedido)
	{
		$query = "SELECT TRIM(P.CVE_DOC) CVE_DOC, P.CVE_CLPV, C.NOMBRE, I.CVE_ART, I.DESCR, PP.CANT, P.FECHA_ENT, C.CALLE, C.COLONIA, C.MUNICIPIO, C.ESTADO
			FROM FACTP" . $_SESSION['companiaClave'] . " P
			INNER JOIN CLIE" . $_SESSION['companiaClave'] . " C ON P.CVE_CLPV=C.CLAVE
			INNER JOIN PAR_FACTP" . $_SESSION['companiaClave'] . " PP ON P.CVE_DOC=PP.CVE_DOC
			INNER JOIN INVE" . $_SESSION['companiaClave'] . " I ON PP.CVE_ART=I.CVE_ART
			WHERE P.STATUS=:estatus AND TRIM(P.CVE_DOC)=:pedido";
		$params['estatus'] = 'E';
		$params['pedido'] = $pedido;
		$user = unserialize($_SESSION['sincco\login\controller']);
		if(intval((isset($_SESSION['extraFiltroClientes']) ? $_SESSION['extraFiltroClientes'] : 0) == 1)) {
			$query .= ' AND trim(c.CVE_VEND) = :vendedor ';
			$params['vendedor'] = $user['userName'];
		}
		return $this->connector->query($query, $params);
	}

	public function getDetalleDia($fecha)
	{
		$query = "SELECT TRIM(P.CVE_DOC) CVE_DOC, P.CVE_CLPV, C.NOMBRE, I.CVE_ART, I.DESCR, PP.CANT, P.FECHA_ENT, C.CALLE, C.COLONIA, C.MUNICIPIO, C.ESTADO
			FROM FACTP" . $_SESSION['companiaClave'] . " P
			INNER JOIN CLIE" . $_SESSION['companiaClave'] . " C ON P.CVE_CLPV=C.CLAVE
			INNER JOIN PAR_FACTP" . $_SESSION['companiaClave'] . " PP ON P.CVE_DOC=PP.CVE_DOC
			INNER JOIN INVE" . $_SESSION['companiaClave'] . " I ON PP.CVE_ART=I.CVE_ART
			WHERE P.STATUS=:estatus AND P.FECHA_ENT=:fecha";
		$params['estatus'] = 'E';
		$params['fecha'] = $fecha;
		$user = unserialize($_SESSION['sincco\login\controller']);
		if(intval((isset($_SESSION['extraFiltroClientes']) ? $_SESSION['extraFiltroClientes'] : 0) == 1)) {
			$query .= ' AND trim(c.CVE_VEND) = :vendedor ';
			$params['vendedor'] = $user['userName'];
		}
		return $this->connector->query($query, $params);
	}

}