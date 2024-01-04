<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Pegawai extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        header('Access-Control-Allow-Origin:*');
        header("Access-Control-Allow-Headers:X-API-KEY,Origin,X-Requested-With,Content-Type,Accept,Access-Control-Request-Method,Authorization");
        header("Access-Control-Allow-Methods:GET,POST,OPTIONS,PUT,DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
        $this->load->database(); //optional
        $this->load->model('M_Pegawai');
        $this->load->library('form_validation');
        $this->load->library('jwt');
    }  

    public function options_method() {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        exit();
    }

    function is_login() {
        $authorizationHeader = $this->input->get_request_header('Authorization', true);

        function is_login() {
            $authorizationHeader = $this->input->get_request_header('Authorization', true);
        
            if (empty($authorizationHeader) || $this->jwt->decode($authorizationHeader) === false) {
                error_log('Validasi token gagal');
                return false;
            }
        }

        return true;
    }

function index_get()
{
    if (!$this->is_login()) {
        return;
       }
   $id = $this->get('ID_Pegawai');
   if ($id == '') {
       $data = $this->M_Pegawai->fetch_all();
   } else {
       $data = $this->M_Pegawai->fetch_single_data($id);
   }

   $this->response($data, 200);
}


function index_post(){

    
    if ($this->post('Nama_Pegawai') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Nama_Pegawai',
            'message' => 'Isian Nama Pegawai tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }
    if ($this->post('Jabatan') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Jabatan',
            'message' => 'Isian Jabatan Pegawai tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }
    
    if ($this->post('Nomor_Telepon') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Nomor_Telepon',
            'message' => 'Isian Nomor Telepon tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }
    if ($this->post('Alamat') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Alamat',
            'message' => 'Isian Alamat Pegawai tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }
    
    $data = array(
        'Nama_Pegawai' => $this->post('Nama_Pegawai'),
        'Jabatan' => $this->post('Jabatan'),
        'Nomor_Telepon' => trim($this->post('Nomor_Telepon')),
        'Alamat' => $this->post('Alamat')
    );
    
    $this->M_Pegawai->insert_api($data);
    $last_row = $this->db->select('*')->order_by('ID_Pegawai', 'desc')->limit(1)->get('Pegawai')->row();
    $response = array(
        'status' => 'success',
        'data' => $last_row,
        'status_code' => 200,
    );
    
    return $this->response($response);
    
}

function index_put()
{
    $id = $this->put('ID_Pegawai');
    $check = $this->M_Pegawai->check_data($id);
    
    if ($check == false) {
        $error = array(
            'status' => 'fail',
            'field' => 'ID_Pegawai',
            'message' => 'Data tidak ditemukan!',
            'status_code' => 502
        );

        return $this->response($error);
    }

    if ($this->put('Nama_Pegawai') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Nama_Pegawai',
            'message' => 'Isian Nama Pegawai tidak boleh kosong',
            'status_code' => 502
        );

        return $this->response($response);
    }
    if ($this->put('Jabatan') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Jabatan',
            'message' => 'Isian Jabatan Pegawai tidak boleh kosong',
            'status_code' => 502
        );

        return $this->response($response);
    }

    if ($this->put('Nomor_Telepon') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Nomor_Telepon',
            'message' => 'Isian Nomor Telepon tidak boleh kosong',
            'status_code' => 502
        );

        return $this->response($response);
    }
    if ($this->put('Alamat') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Alamat',
            'message' => 'Isian Alamat Pegawai tidak boleh kosong',
            'status_code' => 502
        );

        return $this->response($response);
    }

    $data = array(
        'Nama_Pegawai' => trim($this->put('Nama_Pegawai')),
        'Jabatan' => $this->put('Jabatan'),
        'Nomor_Telepon' => trim($this->put('Nomor_Telepon')),
        'Alamat' => $this->put('Alamat')
    );

    $this->M_Pegawai->update_data($id, $data);
    $newData = $this->M_Pegawai->fetch_single_data($id);
    $response = array(
        'status' => 'success',
        'data' => $newData,
        'status_code' => 200,
    );

    return $this->response($response);
}

function index_delete()
{
    $id = $this->delete('ID_Pegawai');
    $check = $this->M_Pegawai->check_data($id);
    if ($check == false) {
        $error = array(
            'status' => 'fail',
            'field' => 'id',
            'message' => 'Data tidak ditemukan!',
            'status_code' => 502
        );

        return $this->response($error);
    }

    $delete = $this->M_Pegawai->delete_data($id);
    $response = array(
        'status' => 'success',
        'data' => null,
        'status_code' => 200,
    );

    return $this->response($response);
}

}
?>
