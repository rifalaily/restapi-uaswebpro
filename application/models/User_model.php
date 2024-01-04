<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database(); 
    }

    public function get_user_by_credentials($username, $password) {
        $query = $this->db->get_where('users', array('username' => $username, 'password' => $password));
    
        if ($query->num_rows() > 0) {
            return $query->row();
        }
    
        return null;
    }
    

    public function get_user_data($username) {
       
        $query = $this->db->get_where('users', array('username' => $username));

        return $query->row();
    }
}
