<?php

namespace Lib;

class DatabaseAPI {

	private $db;

	/**
	 * Initialize
	 */
	public function __construct(){
		$connect = new \mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
		$connect->set_charset('utf8');
		$this->db = $connect;
	}

	public function insertUser($data){
		$sql = "INSERT INTO `lounge_user` SET `useid` = ?, `title` = ?, `firstname` = ?, `lastname` = ?, `mobile` = ?, `email` = ?, `city` = ?, `getmsg` = ?";
		$res = $this->db->prepare($sql);
		$res->bind_param("ssssssss", $data['useid'], $data['title'], $data['firstname'], $data['lastname'], $data['mobile'], $data['email'], $data['city'], $data['getmsg']);
		if($res->execute())
			return true;
		return false;
	}

	public function changephoneNo($mobile){
		$sql = "SELECT `id` FROM `lounge_user` WHERE `mobile` = ?";
		$res = $this->db->prepare($sql);
		$res->bind_param("s", $mobile);
		$res->execute();
		$res->bind_result($id);
		if($res->fetch()){
			return $id;
		}
		return false;
	}

}
