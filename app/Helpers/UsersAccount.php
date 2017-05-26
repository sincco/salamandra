<?php

use \Sincco\Sfphp\Config\Reader;
use \Sincco\Sfphp\Request;
use \Sincco\Sfphp\Crypt;
use \Sincco\Tools\Login;
use \Sincco\Tools\Tokenizer;

/**
 * Funciones para manejo de cuentas de usuario
 */
class UsersAccountHelper extends Sincco\Sfphp\Abstracts\Helper {

	public function editUser($data) {
		$db = Reader::get('bases');
		$db = $db[ 'default' ];
		$db[ 'password' ] = trim(Crypt::decrypt($db['password']));
		Login::setDatabase($db);
		return Login::editUser($data);
	}

	public function createUser($data) {
		$db = Reader::get('bases');
		$db = $db[ 'default' ];
		$db[ 'password' ] = trim(Crypt::decrypt($db['password']));
		Login::setDatabase($db);
		return Login::createUser($data);
	}
	/**
	 * Si un usuario no está loggeado se redirige a la pagina de inicio
	 * @return none
	 */
	public function checkLogin() {
		$db = Reader::get('bases');
		$db = $db[ 'default' ];
		$db[ 'password' ] = trim(Crypt::decrypt($db['password']));
		Login::setDatabase($db);
		if(! $data = Login::isLogged()) {
			if(!isset($_SESSION['SESSION_USERID'])) {
				Request::redirect('login');
			}
		}
		else {
			if(!defined('SESSION_USERID')) {
				$empresas = $this->getModel('Catalogo\Usuarios')->empresasByUser($data[ 'userId' ]);
				$empresas = array_shift($empresas);
				$extra = $this->getModel('Catalogo\Usuarios')->extraByUser($data[ 'userId' ]);
				$extra = array_shift($extra);
				$_SESSION['companiaClave'] = $empresas[ 'empresa' ];
				$_SESSION['companiaRazonSocial'] = $empresas[ 'razonSocial' ];
				$_SESSION['extraPerfil'] = $extra[ 'idPerfil' ];
				$_SESSION['extraFiltroClientes'] = $extra[ 'filtroClientes' ];
				define('SESSION_USERID', $data[ 'userId' ]);
				define('SESSION_USERNAME', $data[ 'userName' ]);
				define('SESSION_USEREMAIL', $data[ 'userEmail' ]);
			}
		}
	}

	public function createLogin($params=false) {
		$db = Reader::get('bases');
		$db = $db[ 'default' ];
		$db[ 'password' ] = trim(Crypt::decrypt($db['password']));
		Login::setDatabase($db);
		if($data = Login::isLogged()) {
			if(!defined('SESSION_USERID')) {
				$empresas = $this->getModel('Catalogo\Usuarios')->empresasByUser($data[ 'userId' ]);
				$empresas = array_shift($empresas);
				$extra = $this->getModel('Catalogo\Usuarios')->extraByUser($data[ 'userId' ]);
				$extra = array_shift($extra);
				$_SESSION['companiaClave'] = $empresas[ 'empresa' ];
				$_SESSION['companiaRazonSocial'] = $empresas[ 'razonSocial' ];
				$_SESSION['extraPerfil'] = $extra[ 'idPerfil' ];
				$_SESSION['extraFiltroClientes'] = $extra[ 'filtroClientes' ];
				define('SESSION_USERID', $data[ 'userId' ]);
				define('SESSION_USERNAME', $data[ 'userName' ]);
				define('SESSION_USEREMAIL', $data[ 'userEmail' ]);
			}
		} else {
			$cliente = $this->getModel('Catalogo\Clientes')->getByIdRFC($params['user'],$params['password']);
			if(count($cliente) > 0) {
				$cliente = $cliente[0];	
				$_SESSION['companiaClave'] = '01';
				$_SESSION['companiaRazonSocial'] = 'Mi Proveedor';
				$_SESSION['extraPerfil'] = 2;
				$_SESSION['extraFiltroClientes'] = 3;
				define('SESSION_USERID', $cliente['CLAVE']);
				define('SESSION_USERNAME', $cliente['NOMBRE']);
				define('SESSION_USEREMAIL', $cliente['RFC']);
				$_SESSION['SESSION_USERID'] = SESSION_USERID;
				$_SESSION['SESSION_USERNAME'] = SESSION_USERNAME;
				$_SESSION['SESSION_USEREMAIL'] = SESSION_USEREMAIL;
			}
		}
	}

	/**
	 * Termina la sesion del usuario y lo envia a la pagina de login
	 * @return [type] [description]
	 */
	public function logout() {
		Login::logout();
		Request::redirect('login');
	}

	/**
	 * Crea la estructura de menus del sistema y valida si el usuario ya hizo login
	 * @return [type] [description]
	 */
	public function createMenus() {
		$this->checkLogin();
		$opcionesBloqueadas = "";
		$perfil = $this->getModel('Salamandra')->perfiles()->where('idPerfil', $_SESSION['extraPerfil'])->getData();
		foreach ($perfil as $_perfil) {
			$opcionesBloqueadas = implode(",", unserialize($_perfil['opcionesBloqueadas']));
		}
		$menus = $this->menuOptions(0, $opcionesBloqueadas);
		$menus[] = ['text'=>'Salir', 'url'=>'login/salir'];
		$response = $this->buildMenu($menus);
		return $response;
	}

	private function buildMenu(array $menu_array, $is_sub=false)
	{
		if ($_SESSION['extraFiltroClientes'] == 3) {
			$menu = "<ul class='nav navbar-nav navbar-right'>";
			$menu .= '<li class="dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Catálogos<span class="caret"></span></a><ul class="dropdown-menu"><li><a href="'.BASE_URL.'catalogo/productos">Productos</a></li></ul></li>';
			$menu .= '<li><a href="'.BASE_URL.'login/salir">Salir<span class="caret"></span></a></li></ul>';
			return $menu;
		} else {
			$ul_attrs = $is_sub ? 'class="dropdown-menu"' : 'class="nav navbar-nav navbar-right"';
			$menu = "<ul $ul_attrs>";
			foreach($menu_array as $attrs) {
				$sub = isset($attrs['nodes']) 
				? $this->buildMenu($attrs['nodes'], true) 
				: null;
				$li_attrs = $sub ? 'class="dropdown-submenu"' : null;
				$a_attrs  = $sub ? 'class="dropdown-toggle" data-toggle="dropdown"' : null;
				$carat    = $sub ? '<span class="caret"></span>' : null;
				$menu .= "<li $li_attrs>";
				$menu .= "<a href='".BASE_URL."${attrs['url']}' $a_attrs>${attrs['text']}$carat</a>$sub";
				$menu .= "</li>";
			}
			return $menu . "</ul>";
		}
	}

	private function menuOptions($parent, $blocked="") {
		$menus = $this->getModel('Menus')->getByParent($parent,$blocked);
		$response = [];
		foreach ($menus as $menu) {
			$nodes = $this->menuOptions($menu['menuId'], $blocked);
			if (count($nodes) > 0) {
				$response[$menu['menuId']] = [
					'text'=>$menu['menuText'],
					'url'=>$menu['menuURL'],
					'nodes'=>$nodes
				];
			} else {
				$response[$menu['menuId']] = [
					'text'=>$menu['menuText'],
					'url'=>$menu['menuURL']
				];
			}
		}
		return $response;
	}

	public function menuTree($parent=0) {
		$menus = $this->getModel('Menus')->getByParent($parent);
		$response = [];
		foreach ($menus as $menu) {
			$nodes = $this->menuTree($menu['menuId']);
			if (count($nodes) > 0) {
				$response[] = [
					'text'=>$menu['menuText'],
					'href'=>$menu['menuURL'],
					'selectedIcon'=>'glyphicon glyphicon-ban-circle',
					'selectable'=>true,
					'state'=>['selected'=>false],
					'nodes'=>$nodes
				];
			} else {
				$response[] = [
					'text'=>$menu['menuText'],
					'selectable'=>true,
					'selectedIcon'=>'glyphicon glyphicon-ban-circle',
					'state'=>['selected'=>false],
					'href'=>$menu['menuURL']
				];
			}
		}
		return $response;
	}

	public function getUserData() {
		return unserialize($_SESSION[ 'sincco\login\controller']);
	}

	public function createTokenForApi() {
		return Login::isLogged();
	}

}