<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Perbaikan extends REST_Controller {
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
        $this->load->model('M_Perbaikan');
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
   $id = $this->get('ID_Perbaikan');
   if ($id == '') {
       $data = $this->M_Perbaikan->fetch_all();
   } else {
       $data = $this->M_Perbaikan->fetch_single_data($id);
   }

   $this->response($data, 200);
}


function index_post(){
    if ($this->post('ID_Kendaraan') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'ID_Kendaraan',
            'message' => 'Isian ID_Kendaraan tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }
    if ($this->post('Tanggal_Perbaikan') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Tanggal_Perbaikan',
            'message' => 'Isian Tanggal_Perbaikan  tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }
    
    if ($this->post('Deskripsi_Perbaikan') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Deskripsi_Perbaikan',
            'message' => 'Isian Nomor Telepon tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }
    if ($this->post('Biaya_Perbaikan') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Biaya_Perbaikan',
            'message' => 'Isian Nomor Telepon tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }
    $data = array(
        'ID_Kendaraan' => $this->post('ID_Kendaraan'),
        'Tanggal_Perbaikan' => $this->post('Tanggal_Perbaikan'),
        'Deskripsi_Perbaikan' => trim($this->post('Deskripsi_Perbaikan')),
        'Biaya_Perbaikan' => trim($this->post('Biaya_Perbaikan'))
    );
    
    $this->M_Perbaikan->insert_api($data);
    $last_row = $this->db->select('*')->order_by('ID_Perbaikan', 'desc')->limit(1)->get('Perbaikan')->row();
    $response = array(
        'status' => 'success',
        'data' => $last_row,
        'status_code' => 200,
    );
    
    return $this->response($response);
    
}

function index_put()
{
    $id = $this->put('ID_Perbaikan');
    $check = $this->M_Perbaikan->check_data($id);
    
    if ($check == false) {
        $error = array(
            'status' => 'fail',
            'field' => 'ID_Perbaikan',
            'message' => 'Data tidak ditemukan!',
            'status_code' => 502
        );

        return $this->response($error);
    }

    if ($this->put('ID_Kendaraan') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'ID_Kendaraan',
            'message' => 'Isian ID_Kendaraan tidak boleh kosong',
            'status_code' => 502
        );

        return $this->response($response);
    }
    if ($this->put('Tanggal_Perbaikan') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Tanggal_Perbaikan',
            'message' => 'Isian Tanggal Perbaikan Pegawai tidak boleh kosong',
            'status_code' => 502
        );

        return $this->response($response);
    }

    if ($this->put('Deskripsi_Perbaikan') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Deskripsi_Perbaikan',
            'message' => 'Isian Deskripsi perbaikan tidak boleh kosong',
            'status_code' => 502
        );

        return $this->response($response);
    }
    if ($this->put('Biaya_Perbaikan') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Iisan Biaya Perbaikan tidak boleh kosong',
            'status_code' => 502
        );

        return $this->response($response);
    }

    $data = array(
        'ID_Perbaikan' => ($this->put('ID_Perbaikan')),
        'ID_Kendaraan' => trim($this->put('ID_Kendaraan')),
        'Tanggal_Perbaikan' => $this->put('Tanggal_Perbaikan'),
        'Deskripsi_Perbaikan' => trim($this->put('Deskripsi_Perbaikan')),
        'Biaya_Perbaikan' => trim($this->put('Biaya_Perbaikan'))
    );

    $this->M_Perbaikan->update_data($id, $data);
    $newData = $this->M_Perbaikan->fetch_single_data($id);
    $response = array(
        'status' => 'success',
        'data' => $newData,
        'status_code' => 200,
    );

    return $this->response($response);
}

function index_delete()
{
    $id = $this->delete('ID_Perbaikan');
    $check = $this->M_Perbaikan->check_data($id);
    if ($check == false) {
        $error = array(
            'status' => 'fail',
            'field' => 'ID_Perbaikan',
            'message' => 'Data tidak ditemukan!',
            'status_code' => 502
        );

        return $this->response($error);
    }

    $delete = $this->M_Perbaikan->delete_data($id);
    $response = array(
        'status' => 'success',
        'data' => null,
        'status_code' => 200,
    );

    return $this->response($response);
}

}
?>
