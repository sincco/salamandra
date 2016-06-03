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

}