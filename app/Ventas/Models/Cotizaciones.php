<?php

class CotizacionesModel extends Sincco\Sfphp\Abstracts\Model {

	public function getAll() {
		return $this->query( 'SELECT cot.cotizacion, cot.fecha, cot.razonSocial, cot.estatus
			FROM cotizaciones cot
			ORDER BY cot.cotizacion' );
	}

	public function getById( $data ) {
		return $this->query( 'SELECT cot.cotizacion, cot.fecha, cot.razonSocial, cot.estatus,
			det.producto, det.descripcion, det.unidad, det.cantidad, det.precio, det.cantidad * det.precio AS subtotal
			FROM cotizaciones cot
			INNER JOIN cotizacionesDetalle det USING( cotizacion )
			WHERE cotizacion = :Cotizacion
			ORDER BY det.descripcion', [ 'Cotizacion'=>$data ] );
	}

	public function insert( $data ) {
		$query = 'INSERT INTO cotizaciones 
			SET fecha=NOW(), cliente=:Cliente, 
			razonSocial=:RazonSocial, email=:Email, 
			estatus=:Estatus,userId=:UserId';
		$id = $this->query( $query, [
			'Cliente'=>$data[ 'cliente' ],
			'RazonSocial'=>$data[ 'razonSocial' ],
			'Email'=>$data[ 'email' ],
			'Estatus'=>$data[ 'estatus' ],
			'UserId'=>$data[ 'vendedor' ]
			] );
		if( $id ) {
			foreach ($data[ 'productos' ] as $producto) {
				if( trim( $producto[ 0 ] ) == '' )
					continue;
				$query = 'INSERT INTO cotizacionesDetalle 
				SET cotizacion=:Cotizacion, producto=:Producto, 
				descripcion=:Descripcion, unidad=:Unidad,
				cantidad=:Cantidad, precio=:Precio';
				$detalle = $this->query( $query, [
					'Cotizacion'=>$id,
					'Producto'=>$producto[ 0 ],
					'Descripcion'=>$producto[ 1 ],
					'Unidad'=>$producto[ 2 ],
					'Cantidad'=>$producto[ 3 ],
					'Precio'=>$producto[ 4 ]
					]);
			}
			return $id;
		}
	}

}