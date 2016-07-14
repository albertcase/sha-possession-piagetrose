<?php

namespace Lib;

class forbidXss{
    private $session;
    private $limite = 15;  /*limite the total codes*/
    private $eachlimit = 10; /*limite the total codes*/
    private $codes;
    private $xsscode;

    public function __construct($xsscode = 0){
    	$this->session = new Session();
      if($xsscode)
        $this->xsscode = $xsscode;
    	if(!$this->session->has('forbidcode')){
  	    $this->session->set('forbidcode',array());
  	    $this->codes = array();
	    }else{
        $this->codes = $this->session->get('forbidcode');
	    }
    }

    public function addXsscode(){
      if(count($this->codes) > $this->limite ){
	       array_shift( $this->codes );
      }
      $xsskey = md5(uniqid().'code');
      $xssval = $this->eachlimit;
      $this->codes = $this->codes + array( $xsskey => $xssval);
      $this->session->set('forbidcode',$this->codes);
      unset($this->codes);
      return $xsskey;
    }

    public function comfirmCode(){
     if(array_key_exists($this->xsscode , $this->codes)){
	      return true;
      }
      return false;
    }

    public function subCode(){
      if(array_key_exists($this->xsscode , $this->codes)){
    	if($this->codes[$this->xsscode] > 0){
    	    $this->codes[$this->xsscode] = $this->codes[$this->xsscode] - 1;
    	    $this->session->set('forbidcode',$this->codes);
    	    return 51; //this code exists
    	 }
	      return 52; //this code is explire
      }
      return 53; //this code is not exists
    }
}
