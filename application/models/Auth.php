<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Model{
	function _construct(){
    parent::_construct();
    }
	
	function getuser($username){
        $sql =" SELECT * from user where username='$username'";
        return $this->db->query($sql);
    }
	
	function getmember($email,$pass){
        $sql =" SELECT * from member where email='$email' and password='$pass'";
        return $this->db->query($sql);
    }
}