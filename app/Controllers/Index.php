<?php

class IndexController extends Sincco\Sfphp\Abstracts\Controller {
	public function index() {
		$view = $this->newView('Login');
		echo $view->render();
	}
}