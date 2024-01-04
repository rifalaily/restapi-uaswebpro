<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Pelanggan extends REST_Controller {
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
        $this->load->model('M_Pelanggan');
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
    
   $id = $this->get('ID_Pelanggan');
   if ($id == '') {
       $data = $this->M_Pelanggan->fetch_all();
   } else {
       $data = $this->M_Pelanggan->fetch_single_data($id);
   }

   $this->response($data, 200);
}


function index_post(){


    if ($this->post('Nama') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Nama',
            'message' => 'Isian Nama tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }


    if ($this->post('Alamat') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Alamat',
            'message' => 'Isian Alamat tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }


    $nomorTeleponBaru = trim($this->post('Nomor_Telepon'));
$cekNomorTelepon = $this->M_Pelanggan->cek_nomor_telepon($nomorTeleponBaru);

if ($cekNomorTelepon) {

        $response = array(
            'status' => 'fail',
            'field' => 'Nomor_Telepon',
            'message' => 'Isian Nomor_Telepon tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }
   
    $data = array(
        'Nama' => $this->post('Nama'),
        'Alamat' => $this->post('Alamat'),
        'Nomor_Telepon' => trim($this->post('Nomor_Telepon'))
    );
    
    $this->M_Pelanggan->insert_api($data);
    $last_row = $this->db->select('*')->order_by('ID_Pelanggan', 'desc')->limit(1)->get('Pelanggan')->row();
    $response = array(
        'status' => 'success',
        'data' => $last_row,
        'status_code' => 200,
    );
    
    return $this->response($response);
    
}

function index_put()
{
    $id = $this->put('ID_Pelanggan');
    $check = $this->M_Pelanggan->check_data($id);
    
    if ($check == false) {
        $error = array(
            'status' => 'fail',
            'field' => 'ID_Pelanggan',
            'message' => 'Data tidak ditemukan!',
            'status_code' => 502
        );

        return $this->response($error);
    }

    if ($this->put('Nama') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Nama',
            'message' => 'Isian Nama tidak boleh kosong',
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

    if ($this->put('Nomor_Telepon') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Nomor_Telepon',
            'message' => 'Isian Nomor Telepon tidak boleh kosong',
            'status_code' => 502
        );

        return $this->response($response);
    }

    $data = array(
         
        'Nama' => trim($this->put('Nama')),
        'Alamat' => $this->put('Alamat'),
        'Nomor_Telepon' => trim($this->put('Nomor_Telepon'))
    );

    $this->M_Pelanggan->update_data($id, $data);
    $newData = $this->M_Pelanggan->fetch_single_data($id);
    $response = array(
        'status' => 'success',
        'data' => $newData,
        'status_code' => 200,
    );

    return $this->response($response);
}

function index_delete()
{
    $id = $this->delete('ID_Pelanggan');
    $check = $this->M_Pelanggan->check_data($id);
    if ($check == false) {
        $error = array(
            'status' => 'fail',
            'field' => 'ID_Pelanggan',
            'message' => 'Data tidak ditemukan!',
            'status_code' => 502
        );

        return $this->response($error);
    }

    $delete = $this->M_Pelanggan->delete_data($id);
    $response = array(
        'status' => 'success',
        'data' => null,
        'status_code' => 200,
    );

    return $this->response($response);
}

}
?>
