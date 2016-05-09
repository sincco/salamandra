<?php

class MenusModel extends Sincco\Sfphp\Abstracts\Model {

	public function getAll() {
		return $this->query( 'SELECT menuId, menuText, menuURL, menuParent FROM __menus ORDER BY menuParent, menuText ASC' );
	}

}