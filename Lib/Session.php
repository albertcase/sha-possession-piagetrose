<?php

namespace Lib;

class Session{

  public function set($key ,$val){
    $_SESSION[trim($key)] = $val;
  }

  public function get($key){
    return $_SESSION[$key];
  }

  public function has($key){
    return array_key_exists($key,$_SESSION);
  }

  public function delkey($key){
    unset($_SESSION[$key]);
  }

  public function remove($key){
    unset($_SESSION[$key]);
  }

  public function delkeys($key = array()){
    foreach($key as $x)
      $this->delkey($x);
  }

  public function sessionid(){
    return session_id();
  }

  public function setsessionid($id){
    return session_id($id);
  }

  public function clean(){
    session_destroy();
  }

}
