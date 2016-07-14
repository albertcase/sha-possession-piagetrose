<?php
namespace Core;

class Request {

	public $request;

	public $query;

	public $params;

	public $validation;

	public function __construct() {
		$this->request = NULL;
		$this->query = NULL;
		if($_SERVER['REQUEST_METHOD'] == 'GET') {
			$this->params = $_GET;
			$this->query = $this;
		} else {
			$this->params = $_POST;
			$this->request = $this;
		}

	}

	// public function get($param){
	// 	if(isset($this->params[$param])) {
	// 		return $this->params[$param];
	// 	} else {
	// 		return NULL;
	// 	}
	// }

	public function validation($fields) {
		if($this->request) {
			$this->validRules($fields, $_POST);
			$_POST = $this->validation;
		}
		if($this->query) {
			$this->validRules($fields, $_GET);
			$_GET = $this->validation;
		}
	}

	public function validRules($fields, $raw) {
		$data = array();
		foreach($fields as $field => $info) {
			if(!isset($_GET[$field])) {
				$response = new Response;
		        $response->statusPrint('999');
			}
		    $value = trim($raw[$field]);
		    if($info) {
		      if($info[0] == 'notnull' && $value == '') {
		        $code = isset($info[1]) ? $info[1] : '999';
		        $response = new Response;
		        $response->statusPrint($code);
		      }
		      if($info[0] == 'date' && !strtotime($value)){
		        $code = isset($info[1]) ? $info[1] : '999';
		        $response = new Response;
		        $response->statusPrint($code);
		      }
		    }
		    $data[$field] = $value;
		}
		$this->validation = $data;
	}

	public function get($key ,$val=''){
		return trim(isset($_GET[$key])?$_GET[$key]:$val);
	}

	public function post($key ,$val=''){
		return trim(isset($_POST[$key])?$_POST[$key]:$val);
	}

	public function comfirmKeys($keys){ /*array(array('key' => key ,'type'=> post/get ,'regtype'=> regtype ,$selfReg => '') )*/
		$out = array();
		$k = '';
		$strTest = new strTest();
		foreach($keys as $x){
			$k = $this->$x['type']($x['key']);
			if($x['regtype'] != 'text'){
					if(!$strTest->$x['regtype']($k)){
						return false;
					}
			}
			$out = $out + array($x['key'] => $k);
			unset($k);
		}
		return $out;
	}

	public function uselykeys($keys){
		$out = array();
		$kk = $this->comfirmKeys($keys);
		if(!is_array($kk))
			return false;/*format error*/
		foreach($kk as $x => $x_val){
			if($x_val != '')
				$out[$x] = $x_val;
		}
		if(count($out)>0)
			return $out;
		return true;
	}

	public function text($key){
		return $key;
	}
}
