<?php

class CotizacionesModel extends Sincco\Sfphp\Abstracts\Model {

	public function getAll() {
		return $this->query( 'SELECT cot.cotizacion, cot.fecha, cot.razonSocial, cot.estatus
			FROM cotizaciones cot
			ORDER BY cot.cotizacion' );
	}

}