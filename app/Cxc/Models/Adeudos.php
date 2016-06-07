<?php

class AdeudosModel extends Sincco\Sfphp\Abstracts\Model {

	public function __construct() {
		parent::__construct( 'sae' );
	}
	
	public function getCargos() {
		$query = 'SELECT trim(cargo.CVE_CLIE) CVE_CLIE, trim(cliente.NOMBRE) NOMBRE, trim(cargo.NO_FACTURA) NO_FACTURA, substring(CAST(factura.FECHA_VEN as varchar(25) character SET utf8) from 1 for 10) AS Vencimiento, max(cargo.NUM_MONED) as MONEDA, CASE cargo.NUM_MONED WHEN 1 THEN sum(cargo.IMPORTE) ELSE sum(cargo.IMPMON_EXT) END CARGO, 0 ABONO, substring(CAST(max(cargo.FECHA_APLI) as varchar(25)) from 1 for 10) AS aplicacion
			FROM CUEN_M' . $_SESSION[ 'companiaClave' ] . ' cargo
			INNER JOIN Clie' . $_SESSION[ 'companiaClave' ] . ' cliente ON ( cliente.CLAVE = cargo.CVE_CLIE )
			INNER JOIN FACTF' . $_SESSION[ 'companiaClave' ] . ' factura ON ( factura.CVE_DOC = cargo.NO_FACTURA )
			WHERE trim(cargo.TIPO_MOV) = :C_TIPO_MOV
			GROUP BY cargo.CVE_CLIE, cliente.NOMBRE, cargo.NO_FACTURA, factura.FECHA_VEN, cargo.NUM_MONED';
		return $this->connector->query( $query, [ 'C_TIPO_MOV'=>'C' ] );
	}

	public function getAbonos() {
		$query = 'SELECT trim(abono.CVE_CLIE) CVE_CLIE, trim(cliente.NOMBRE) NOMBRE, trim(abono.REFER) NO_FACTURA, max(abono.NUM_MONED) as MONEDA, 0 CARGO, CASE abono.NUM_MONED WHEN 1 THEN sum(abono.IMPORTE) ELSE sum(abono.IMPMON_EXT) END ABONO, substring(CAST(max(abono.FECHA_APLI) as varchar(25)) from 1 for 10) AS aplicacion
			FROM CUEN_DET' . $_SESSION[ 'companiaClave' ] . ' abono
			INNER JOIN Clie' . $_SESSION[ 'companiaClave' ] . ' cliente ON ( cliente.CLAVE = abono.CVE_CLIE )
			WHERE trim(abono.TIPO_MOV) = :A_TIPO_MOV
			GROUP BY abono.CVE_CLIE, cliente.NOMBRE, abono.REFER, abono.NUM_MONED';
		return $this->connector->query( $query, [ 'A_TIPO_MOV'=>'A' ] );
	}

	public function getAdeudos() {
		$query = 'SELECT CVE_CLIE, NOMBRE, NO_FACTURA, 
				substring(CAST(factura.FECHA_VEN as varchar(25) character SET utf8) from 1 for 10) AS Vencimiento, 
				MONEDA, CARGO, ABONO, SALDO, EMITIDA, 
				CASE ABONO WHEN 0 THEN NULL ELSE ULTIMO_PAGO END ULTIMO_PAGO,
				datediff (day from CAST(factura.FECHA_VEN AS DATE) to cast(current_date as date)) AS atraso
			FROM (
				SELECT CVE_CLIE, NOMBRE, NO_FACTURA, MONEDA, SUM(CARGO) CARGO, SUM(ABONO) ABONO,  SUM(CARGO) - SUM(ABONO) SALDO, MAX(EMITIDA) EMITIDA, MAX(APLICACION) ULTIMO_PAGO
				FROM (
					SELECT trim(cargo.CVE_CLIE) CVE_CLIE, trim(cliente.NOMBRE) NOMBRE, trim(cargo.NO_FACTURA) NO_FACTURA, max(cargo.NUM_MONED) as MONEDA, CASE cargo.NUM_MONED WHEN 1 THEN sum(cargo.IMPORTE) ELSE sum(cargo.IMPMON_EXT) END CARGO, 0 ABONO, substring(CAST(max(cargo.FECHA_APLI) as varchar(25)) from 1 for 10) emitida, \'1900-01-01\' as aplicacion
					FROM CUEN_M' . $_SESSION[ 'companiaClave' ] . ' cargo
					INNER JOIN Clie' . $_SESSION[ 'companiaClave' ] . ' cliente ON ( cliente.CLAVE = cargo.CVE_CLIE )
					WHERE trim(cargo.TIPO_MOV) = :C_TIPO_MOV
					GROUP BY cargo.CVE_CLIE, cliente.NOMBRE, cargo.NO_FACTURA, cargo.NUM_MONED
					UNION ALL
					SELECT trim(abono.CVE_CLIE) CVE_CLIE, trim(cliente.NOMBRE) NOMBRE, trim(abono.REFER) NO_FACTURA, max(abono.NUM_MONED) as MONEDA, 0 CARGO, CASE abono.NUM_MONED WHEN 1 THEN sum(abono.IMPORTE) ELSE sum(abono.IMPMON_EXT) END ABONO, \'1900-01-01\' as emitida, substring(CAST(max(abono.FECHA_APLI) as varchar(25)) from 1 for 10) AS aplicacion
					FROM CUEN_DET' . $_SESSION[ 'companiaClave' ] . ' abono
					INNER JOIN Clie' . $_SESSION[ 'companiaClave' ] . ' cliente ON ( cliente.CLAVE = abono.CVE_CLIE )
					WHERE trim(abono.TIPO_MOV) = :A_TIPO_MOV
					GROUP BY abono.CVE_CLIE, cliente.NOMBRE, abono.REFER, abono.NUM_MONED
				) saldos
				GROUP BY CVE_CLIE, NOMBRE, NO_FACTURA, MONEDA
			) saldos
		INNER JOIN FACTF' . $_SESSION[ 'companiaClave' ] . ' factura ON ( factura.CVE_DOC = saldos.NO_FACTURA )
		WHERE SALDO > 0.99
		ORDER BY ATRASO DESC, CVE_CLIE ASC, NO_FACTURA ASC';
		return $this->connector->query( $query, [ 'C_TIPO_MOV'=>'C', 'A_TIPO_MOV'=>'A' ] );
	}

	public function getNotificaciones() {
		$query = 'SELECT CVE_CLIE, NOMBRE, SUM(SALDO) SALDO
			FROM (
				SELECT CVE_CLIE, NOMBRE, NO_FACTURA, MONEDA, SUM(CARGO) CARGO, SUM(ABONO) ABONO,  SUM(CARGO) - SUM(ABONO) SALDO, MAX(EMITIDA) EMITIDA, MAX(APLICACION) ULTIMO_PAGO
				FROM (
					SELECT trim(cargo.CVE_CLIE) CVE_CLIE, trim(cliente.NOMBRE) NOMBRE, trim(cargo.NO_FACTURA) NO_FACTURA, max(cargo.NUM_MONED) as MONEDA, CASE cargo.NUM_MONED WHEN 1 THEN sum(cargo.IMPORTE) ELSE sum(cargo.IMPMON_EXT) END CARGO, 0 ABONO, substring(CAST(max(cargo.FECHA_APLI) as varchar(25)) from 1 for 10) emitida, \'1900-01-01\' as aplicacion
					FROM CUEN_M' . $_SESSION[ 'companiaClave' ] . ' cargo
					INNER JOIN Clie' . $_SESSION[ 'companiaClave' ] . ' cliente ON ( cliente.CLAVE = cargo.CVE_CLIE )
					WHERE trim(cargo.TIPO_MOV) = :C_TIPO_MOV
					GROUP BY cargo.CVE_CLIE, cliente.NOMBRE, cargo.NO_FACTURA, cargo.NUM_MONED
					UNION ALL
					SELECT trim(abono.CVE_CLIE) CVE_CLIE, trim(cliente.NOMBRE) NOMBRE, trim(abono.REFER) NO_FACTURA, max(abono.NUM_MONED) as MONEDA, 0 CARGO, CASE abono.NUM_MONED WHEN 1 THEN sum(abono.IMPORTE) ELSE sum(abono.IMPMON_EXT) END ABONO, \'1900-01-01\' as emitida, substring(CAST(max(abono.FECHA_APLI) as varchar(25)) from 1 for 10) AS aplicacion
					FROM CUEN_DET' . $_SESSION[ 'companiaClave' ] . ' abono
					INNER JOIN Clie' . $_SESSION[ 'companiaClave' ] . ' cliente ON ( cliente.CLAVE = abono.CVE_CLIE )
					WHERE trim(abono.TIPO_MOV) = :A_TIPO_MOV
					GROUP BY abono.CVE_CLIE, cliente.NOMBRE, abono.REFER, abono.NUM_MONED
				) saldos
				GROUP BY CVE_CLIE, NOMBRE, NO_FACTURA, MONEDA
			) saldos
		INNER JOIN FACTF' . $_SESSION[ 'companiaClave' ] . ' factura ON ( factura.CVE_DOC = saldos.NO_FACTURA )
		WHERE SALDO > 0.99
		GROUP BY CVE_CLIE, NOMBRE
		ORDER BY CVE_CLIE';
		return $this->connector->query( $query, [ 'C_TIPO_MOV'=>'C', 'A_TIPO_MOV'=>'A' ] );
	}

	public function getAdeudoCliente( $cliente ) {
		$query = 'SELECT CVE_CLIE, NOMBRE, NO_FACTURA, 
				substring(CAST(factura.FECHA_VEN as varchar(25) character SET utf8) from 1 for 10) AS Vencimiento, 
				MONEDA, CARGO, ABONO, SALDO, EMITIDA, 
				CASE ABONO WHEN 0 THEN NULL ELSE ULTIMO_PAGO END ULTIMO_PAGO,
				datediff (day from CAST(factura.FECHA_VEN AS DATE) to cast(current_date as date)) AS atraso
			FROM (
				SELECT CVE_CLIE, NOMBRE, NO_FACTURA, MONEDA, SUM(CARGO) CARGO, SUM(ABONO) ABONO,  SUM(CARGO) - SUM(ABONO) SALDO, MAX(EMITIDA) EMITIDA, MAX(APLICACION) ULTIMO_PAGO
				FROM (
					SELECT trim(cargo.CVE_CLIE) CVE_CLIE, trim(cliente.NOMBRE) NOMBRE, trim(cargo.NO_FACTURA) NO_FACTURA, max(cargo.NUM_MONED) as MONEDA, CASE cargo.NUM_MONED WHEN 1 THEN sum(cargo.IMPORTE) ELSE sum(cargo.IMPMON_EXT) END CARGO, 0 ABONO, substring(CAST(max(cargo.FECHA_APLI) as varchar(25)) from 1 for 10) emitida, \'1900-01-01\' as aplicacion
					FROM CUEN_M' . $_SESSION[ 'companiaClave' ] . ' cargo
					INNER JOIN Clie' . $_SESSION[ 'companiaClave' ] . ' cliente ON ( cliente.CLAVE = cargo.CVE_CLIE )
					WHERE trim(cargo.TIPO_MOV) = :C_TIPO_MOV AND trim(cargo.CVE_CLIE) = \'' . $cliente . '\'
					GROUP BY cargo.CVE_CLIE, cliente.NOMBRE, cargo.NO_FACTURA, cargo.NUM_MONED
					UNION ALL
					SELECT trim(abono.CVE_CLIE) CVE_CLIE, trim(cliente.NOMBRE) NOMBRE, trim(abono.REFER) NO_FACTURA, max(abono.NUM_MONED) as MONEDA, 0 CARGO, CASE abono.NUM_MONED WHEN 1 THEN sum(abono.IMPORTE) ELSE sum(abono.IMPMON_EXT) END ABONO, \'1900-01-01\' as emitida, substring(CAST(max(abono.FECHA_APLI) as varchar(25)) from 1 for 10) AS aplicacion
					FROM CUEN_DET' . $_SESSION[ 'companiaClave' ] . ' abono
					INNER JOIN Clie' . $_SESSION[ 'companiaClave' ] . ' cliente ON ( cliente.CLAVE = abono.CVE_CLIE )
					WHERE trim(abono.TIPO_MOV) = :A_TIPO_MOV AND trim(abono.CVE_CLIE) = \'' . $cliente . '\'
					GROUP BY abono.CVE_CLIE, cliente.NOMBRE, abono.REFER, abono.NUM_MONED
				) saldos
				GROUP BY CVE_CLIE, NOMBRE, NO_FACTURA, MONEDA
			) saldos
			INNER JOIN FACTF' . $_SESSION[ 'companiaClave' ] . ' factura ON ( factura.CVE_DOC = saldos.NO_FACTURA )
			WHERE SALDO > 0.99 AND datediff (day from CAST(factura.FECHA_VEN AS DATE) to cast(current_date as date)) > 29
			ORDER BY ATRASO DESC, CVE_CLIE ASC, NO_FACTURA ASC';
		return $this->connector->query( $query, [ 'C_TIPO_MOV'=>'C', 'A_TIPO_MOV'=>'A' ] );
	}
}