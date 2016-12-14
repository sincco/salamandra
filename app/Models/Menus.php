<?php

class MenusModel extends Sincco\Sfphp\Abstracts\Model {

	public function getAll() {
		return $this->connector->query('SELECT menuId, menuText, menuURL, menuParent FROM __menus ORDER BY menuParent, menuText ASC');
	}

	public function getByParent($parent) {
		return $this->connector->query('SELECT menuId, menuText, menuURL, menuParent FROM __menus WHERE menuParent=' . $parent . ' ORDER BY menuParent, menuText ASC;');
	}

}