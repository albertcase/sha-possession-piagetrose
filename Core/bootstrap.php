<?php

function __autoload($class) {
	$class_dir = array(
		'^Lib\\' => '',
		'^Core\\' => '',
		'Bundle\\' => 'bundle/',
		);
	foreach($class_dir as $base => $dir) {
		if(preg_match("/$base\/", $class)) {
			$class = str_replace( '\\', DIRECTORY_SEPARATOR, $class);
			require_once(dirname(__FILE__) . '/../' . $dir . $class . '.php' ); 
		}
	}
}

class Core {
	static public function Response() {

		include_once dirname(__FILE__) . "/../config/config.php";
		include_once dirname(__FILE__) . "/../config/router.php";

		$current_router = preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI']);

		if(isset($routers[$current_router])) {
			$callback = $routers[$current_router];
			$class = $callback[0] . 'Controller';
			$method = $callback[1];
			$data = call_user_func_array(array(new $class, $method), array());
			$response = new \Core\Response($data);
			$response->send();
		}
		foreach($routers as $router => $callback) {
			$pattern = '/' . preg_replace(array('/\//', '/%/'), array('\/', '(.*)'), $router) . '/';
			if(preg_match($pattern, $current_router, $matches)) {
				unset($matches[0]);
				$class = $callback[0] . 'Controller';
				$method = $callback[1];
				$data = call_user_func_array(array(new $class, $method), $matches);
				$response = new \Core\Response($data);
				$response->send();
			}			
		}
	}
}
