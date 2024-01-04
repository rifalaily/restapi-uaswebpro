<?php

defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
require_once FCPATH . 'vendor/autoload.php';

use Restserver\Libraries\REST_Controller;
class Login extends REST_Controller
{
    function __construct($config = 'rest'){
        parent::__construct($config);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'OPTIONS') {
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE, PATCH");
            header("Access-Control-Allow-Headers: Authorization, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, X-My-Custom-Header");
             
        } else {
            exit(); 
        }
        $this->load->database();
        $this->load->model('M_User');
        $this->load->library('form_validation');
        $this->load->library('jwt');
    }

    function validate(){
        $input_data = file_get_contents("php://input");
        parse_str($input_data, $put_data);

        $this->form_validation->set_data($put_data);
        
        $this->form_validation->set_rules('email', 'Email','required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
    }

    public function options_get() {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        exit();
    }
    
    function index_post(){

        $this->validate();
        if ($this->form_validation->run() === FALSE) {
            $error = $this->form_validation->error_array();
            $response = array(
                'status_code' => 502,
                'message' => $error
            );
            return $this->response($response);
        }
        $email = $this->input->post('email');
        $password = md5($this->input->post('password'));

        $user = $this->M_User->userExist($email, $password);

        if (!$user) {
            $response = array(
                'status_code' => 401,
                'message' => 'Inavalid email or password'
            );
            return $this->response($response);
        }

        $token = $this->jwt->encode($email, $password);
        $response = array(
                'status_code' => 200,
                'message' => 'success',
                'user_data' => $user,
                'token' => $token,
        );

        return $this->response($response);
    }



}

?>