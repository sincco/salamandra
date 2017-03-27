<?php

class MenusModel extends Sincco\Sfphp\Abstracts\Model {

	public function getAll() {
		return $this->connector->query('SELECT menuId, menuText, menuURL, menuParent FROM __menus ORDER BY menuParent, menuText ASC');
	}

	public function getByParent($parent,$blocked="") {
		$query = 'SELECT menuId, menuText, menuURL, menuParent FROM __menus WHERE menuParent=' . $parent; 
		if(strlen(trim($blocked))>0) {
			$query .= " AND menuId not in (" . $blocked .")";
		}
		$query .= ' ORDER BY menuParent, menuText ASC;';
		return $this->connector->query($query);
	}

}