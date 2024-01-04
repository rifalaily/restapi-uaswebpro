<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Pembelian_Barang extends REST_Controller {
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
        $this->load->model('M_Pembelian_Barang');
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

   $id = $this->get('ID_Pembelian');

   if (!empty($id)) {
       $data = $this->M_Pembelian_Barang->fetch_single_data($id);
   } else {
       $data = $this->M_Pembelian_Barang->fetch_all();
   }

   $this->response($data, 200);
}


function index_post(){
    if ($this->post('ID_Pelanggan') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'ID_Pelanggan',
            'message' => 'Isian ID Pelanggan tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }
    if ($this->post('Tanggal_Pembelian') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Tanggal_Pembelian',
            'message' => 'Isian Nama Barang tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }
    $data = array(
        'ID_Pelanggan' => $this->post('ID_Pelanggan'),
        'Tanggal_Pembelian' => $this->post('Tanggal_Pembelian')
    );
    
    $this->M_Pembelian_Barang->insert_api($data);
    $last_row = $this->db->select('*')->order_by('ID_Pembelian', 'desc')->limit(1)->get('pembelian_barang')->row();
    $response = array(
        'status' => 'success',
        'data' => $last_row,
        'status_code' => 200,
    );
    
    return $this->response($response);
    
}

function index_put()
{
    $id = $this->put('ID_Pembelian');
    $check = $this->M_Pembelian_Barang->check_data($id);
    
    if ($check == false) {
        $error = array(
            'status' => 'fail',
            'field' => 'ID_Pembelian',
            'message' => 'Data tidak ditemukan!',
            'status_code' => 502
        );

        return $this->response($error);
    }
    if ($this->put('ID_Pelanggan') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'ID_Pelanggan',
            'message' => 'Isian ID Pelanggan tidak boleh kosong',
            'status_code' => 502
        );

        return $this->response($response);
    }
    if ($this->put('Tanggal_Pembelian') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Tanggal_Pembelian',
            'message' => 'Isian Nama Pegawai tidak boleh kosong',
            'status_code' => 502
        );

        return $this->response($response);
    }
    $data = array(
        'ID_Pelanggan' => trim($this->put('ID_Pelanggan')),
        'Tanggal_Pembelian' => trim($this->put('Tanggal_Pembelian'))
    );

    $this->M_Pembelian_Barang->update_data($id, $data);
    $newData = $this->M_Pembelian_Barang->fetch_single_data($id);
    $response = array(
        'status' => 'success',
        'data' => $newData,
        'status_code' => 200,
    );

    return $this->response($response);
}

function index_delete()
{
    $id = $this->delete('ID_Pembelian');
    $check = $this->M_Pembelian_Barang->check_data($id);
    if ($check == false) {
        $error = array(
            'status' => 'fail',
            'field' => 'id',
            'message' => 'Data tidak ditemukan!',
            'status_code' => 502
        );

        return $this->response($error);
    }

    $delete = $this->M_Pembelian_Barang->delete_data($id);
    $response = array(
        'status' => 'success',
        'data' => null,
        'status_code' => 200,
    );

    return $this->response($response);
}

}
?>
