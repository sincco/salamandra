<?php

class PedidosModel extends Sincco\Sfphp\Abstracts\Model {

	public function __construct() {
		parent::__construct( 'sae' );
	}
	
	public function getAll() {
		$query = 'SELECT f.CVE_DOC, c.NOMBRE CLIENTE ,  f.FECHA_DOC,  f.IMPORTE, COALESCE(c.CVE_VEND,0) CVE_VEND, v.NOMBRE VENDEDOR
			FROM FACTP' . $_SESSION[ 'companiaClave' ] . ' f
			INNER JOIN CLIE' . $_SESSION[ 'companiaClave' ] . ' c ON c.CLAVE=f.CVE_CLPV
			INNER JOIN vend' . $_SESSION[ 'companiaClave' ] . ' v ON v.CVE_VEND=f.CVE_VEND
			WHERE f.status= :estatus AND f.TIP_DOC_SIG IS NULL';
		$params[ 'estatus' ] = 'E';
		$user = unserialize( $_SESSION[ 'sincco\login\controller'] );
		if( intval( ( isset( $_SESSION[ 'extraFiltroClientes' ] ) ? $_SESSION[ 'extraFiltroClientes' ] : 0 ) == 1 ) ) {
			$query .= ' AND trim(c.CVE_VEND) = :vendedor ';
			$params[ 'vendedor' ] = $user[ 'userName' ];
		}
		return $this->connector->query( $query, $params );
	}

	public function getRemisiones() {
		$query = '
			SELECT CVE_DOC,NOMBRE,FECHA_DOC,FECHA_ENT,CAN_TOT,STR_OBS,REMISION,FACTURA,SUM(IMPORTE_PAGO) PAGO, CAN_TOT - SUM(IMPORTE_PAGO) RESTA FROM (
				SELECT
				FAC.CVE_DOC FACTURA, FAC.CVE_CLPV CLIENTE, FAC.FECHA_DOC FACTURA_FECHA,FAC.CAN_TOT FACTURA_TOTAL,
				CTA.IMPORTE IMPORTE_PAGO,
				NULL REMISION,NULL REMISION_FECHA, NULL REMISION_TOTAL,
				PED.*
				FROM FACTF' . $_SESSION[ 'companiaClave' ] . ' FAC
				INNER JOIN CUEN_DET' . $_SESSION[ 'companiaClave' ] . ' CTA ON (CTA.NO_FACTURA=FAC.CVE_DOC)
				INNER JOIN FACTP' . $_SESSION[ 'companiaClave' ] . ' PED ON (FAC.DOC_ANT=PED.CVE_DOC)
				WHERE FAC.STATUS='E' AND FAC.TIP_DOC_ANT ='P'
				UNION ALL
				SELECT
				FAC.CVE_DOC FACTURA, FAC.CVE_CLPV CLIENTE, FAC.FECHA_DOC FACTURA_FECHA,FAC.CAN_TOT FACTURA_TOTAL,
				CTA.IMPORTE IMPORTE_PAGO,
				REM.CVE_DOC REMISION,REM.FECHA_DOC REMISION_FECHA, REM.CAN_TOT REMISION_TOTAL,
				PED.*
				FROM FACTF' . $_SESSION[ 'companiaClave' ] . ' FAC
				INNER JOIN CUEN_DET' . $_SESSION[ 'companiaClave' ] . ' CTA ON (CTA.NO_FACTURA=FAC.CVE_DOC)
				INNER JOIN FACTR' . $_SESSION[ 'companiaClave' ] . ' REM ON (REM.CVE_DOC=FAC.DOC_ANT)
				INNER JOIN FACTP' . $_SESSION[ 'companiaClave' ] . ' PED ON (REM.DOC_ANT=PED.CVE_DOC)
				WHERE FAC.STATUS=\'E\' AND FAC.TIP_DOC_ANT =\'R\' AND REM.TIP_DOC_ANT =\'P\'
				UNION ALL
				SELECT 
					NULL FACTURA, NULL CLIENTE, NULL FACTURA_FECHA, NULL FACTURA_TOTAL,
					0 IMPORTE_PAGO,
					NULL REMISION,NULL REMISION_FECHA, NULL REMISION_TOTAL,
					PED.*
				FROM FACTP' . $_SESSION[ 'companiaClave' ] . ' PED 
				WHERE PED.STATUS=\'E\' AND PED.DOC_SIG IS NULL
			) TMP 
			INNER JOIN OBS_DOCF' . $_SESSION[ 'companiaClave' ] . ' OBS ON TMP.CVE_OBS=OBS.CVE_OBS
			INNER JOIN CLIE' . $_SESSION[ 'companiaClave' ] . ' CLI ON TMP.CVE_CLPV=CLI.CLAVE
			GROUP BY CVE_DOC,NOMBRE,FECHA_DOC,FECHA_ENT,CAN_TOT,STR_OBS,REMISION,FACTURA
			ORDER BY CVE_DOC';
		return $this->connector->query( $query, $params );
	}

}