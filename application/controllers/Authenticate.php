<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH .'/libraries/REST_Controller.php';
require_once FCPATH .'vendor/autoload.php';
use Restserver\Libraries\REST_Controller;


class Authenticate extends REST_Controller {
    function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->library('jwt');
        $this->load->model('User_model');
    }

    public function generateSecretKey_get()
    {
        $length = 32;
        $secretKey = bin2hex(random_bytes($length));

        return $this->response(["jwt_scret_key" => $secretKey]);
    }

    public function getToken_post()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

       
        if ($this->validate_credentials($username, $password)) {
            
            $token = $this->generate_token($username);
            $this->response([
                'status' => true,
                'message' => 'Berhasil login',
                'token' => $token
            ], REST_Controller::HTTP_OK);
        } 
        else 
        {
            
            $this->response([
                'status' => false,
                'message' => 'Login gagal. User Tidak Tersedia.'
            ], REST_Controller::HTTP_UNAUTHORIZED);
        }
    }


    private function validate_credentials($username, $password) 
    {
        $this->load->model('user_model'); 
        return $this->user_model->get_user_by_credentials($username, $password);
    }
    
    

    private function generate_token($username) 
    {
        $user_data = $this->User_model->get_user_data($username);  
        $token_data = array(
            'user_id' => $user_data->id,
            'username' => $user_data->username,
            'exp' => time() + 3600 
        );     
        $token = $this->jwt->encode($token_data);
        return $token;
    }

    }

    
