<?php

class DashboardModel extends Sincco\Sfphp\Abstracts\Model {

	public function __construct() {
		parent::__construct('sae');
	}
	
	public function run( $query ) {
		return $this->query( $query );
	}

}