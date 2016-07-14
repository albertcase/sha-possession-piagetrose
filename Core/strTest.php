<?php

namespace Core;

class strTest{
  public function email($key){
    return $this->reg($key ,"/^[0-9a-zA-Z._-]+@[0-9a-zA-Z]+(\.[0-9a-zA-Z]+){0,3}$/");
  }

  public function number($key){
    return $this->reg($key ,"/^-{0,1}[0-9]+$/");
  }

  public function telphone($key){
    return $this->reg($key ,"/^([\+]{0,1}[0-9]{1,5}[\s-]{0,1}){0,1}[0-9]+$/");
  }

  public function text($key){
    return $key;
  }

  public function reg($key ,$reg){
    return preg_match($reg ,trim($key));
  }
}
