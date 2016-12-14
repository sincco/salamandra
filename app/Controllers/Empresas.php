<?php


class EmpresasController extends Sincco\Sfphp\Abstracts\Controller {
	public function index() {
		$this->helper('UsersAccount')->checkLogin();
		$modelo = $this->getModel('Empresas');
		$view = $this->newView('EmpresasTabla');
		$view->empresas = $modelo->getAll();
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function agregar() {
		$this->helper('UsersAccount')->checkLogin();
		$view = $this->newView('EmpresasAlta');
		$view->menus = $this->helper('UsersAccount')->createMenus();
		$view->render();
	}

	public function apiAgregar() {
		$modelo = $this->getModel('Empresas');
		$empresa = $this->getParams('empresa');
		echo json_encode([ 'respuesta'=>$modelo->insert($empresa) ]);
	}

	public function apiBorrar() {
		$modelo = $this->getModel('Empresas');
		echo json_encode([ 'respuesta'=>$modelo->delete($this->getParams('empresa')) ]);
	}

	public function apiEditar() {
		$modelo = $this->getModel('Empresas');
		echo json_encode([ 'respuesta'=>$modelo->update($this->getParams('set'), $this->getParams('where')) ]);
	}
}
	