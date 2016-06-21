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

}