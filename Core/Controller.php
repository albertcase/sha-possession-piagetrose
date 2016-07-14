<?php
namespace Core;

class Controller {

	public function addListener($event, $listener, $method) {
		EventDispatcher::addListener($event, $listener, $method);
	}

	public function dispatch($event, $eventObject) {
		EventDispatcher::dispatch($event, $eventObject);
	}

	public function Request() {
		return new Request();
	}

	public function Response($response = '') {
		return new Response($response);
	}

	public function statusPrint($status, $msg = '') {
		$this->Response()->statusPrint($status, $msg);
	}

	public function dataPrint($data) {
		$this->Response()->dataPrint($data);
	}

	public function redirect($uri) {
		$this->Response()->redirect($uri);
	}

	public function render($tpl_name, $params) {
		$template = new Theme();
		$data = $template->theme($tpl_name, $params);
		$response = new Response($data);
		$response->send();
	}
}