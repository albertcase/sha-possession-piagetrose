<?php
namespace Core;

class Response {

	public $response;

	public function __construct($response = '') {
		EventDispatcher::dispatch('on.request', new Request);
		EventDispatcher::dispatch('on.response', $this->response);
		$this->response = $response;
	}

	public function send() {
		print $this->response;
	}

	public function statusPrint($status, $msg = '') {
		$data = array();
		if($status || $status == 0)
			$data['status'] = $status;
		if($msg)
			$data['msg'] = $msg;
		header("Content-type: application/json");
		print json_encode($data);
		exit;
	}

	public function dataPrint($data) {
		header("Content-type: application/json");
		print json_encode($data);
		exit;
	}

	public function redirect($uri) {
		Header('Location:' . $uri);
		exit;
	}
}