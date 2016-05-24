<?php

class PedidosModel extends Sincco\Sfphp\Abstracts\Model {

	public function __construct() {
		parent::__construct( 'sae' );
	}
	
	public function getAll() {
		return $this->query( 'SELECT f.CVE_DOC, c.NOMBRE CLIENTE ,  f.FECHA_DOC,  f.IMPORTE, v.NOMBRE VENDEDOR
			FROM FACTP' . $_SESSION[ 'companiaClave' ] . ' f
			INNER JOIN CLIE' . $_SESSION[ 'companiaClave' ] . ' c ON c.CLAVE=f.CVE_CLPV
			INNER JOIN vend' . $_SESSION[ 'companiaClave' ] . ' v ON v.CVE_VEND=f.CVE_VEND
			WHERE f.status= :estatus AND f.TIP_DOC_SIG IS NULL', [ 'estatus'=>'E' ] );
	}

}