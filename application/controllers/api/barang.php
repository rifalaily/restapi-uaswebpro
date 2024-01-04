<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Barang extends REST_Controller {
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
        $this->load->model('M_Barang');
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

    $id = $this->get('ID_Barang');
   if ($id == '') {
       $data = $this->M_Barang->fetch_all();
   } else {
       $data = $this->M_Barang->fetch_single_data($id);
   }

   $this->response($data, 200);
}


function index_post(){
    if (!$this->is_login()) {
        return;
       }
    if ($this->post('Nama_Barang') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Nama_Barang',
            'message' => 'Isian Nama Barang tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }
    if ($this->post('Harga_Jual') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Harga_Jual',
            'message' => 'Isian Harga_Jual tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }
    if ($this->post('Harga_Beli') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Harga_Beli',
            'message' => 'Isian Harga_Beli tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }
    if ($this->post('Stok') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Stok',
            'message' => 'Isian Stok  tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }
    
    $data = array(
        'Nama_Barang' => $this->post('Nama_Barang'),
        'Harga_Jual' => $this->post('Harga_Jual'),
        'Harga_Beli' => $this->post('Harga_Beli'),
        'Stok' => $this->post('Stok')
    );
    
    $this->M_Barang->insert_api($data);
    $last_row = $this->db->select('*')->order_by('ID_Barang', 'desc')->limit(1)->get('barang')->row();
    $response = array(
        'status' => 'success',
        'data' => $last_row,
        'status_code' => 200,
    );
    
    return $this->response($response);
    
}

function index_put()
{
    if (!$this->is_login()) {
        return;
       }
    $id = $this->put('ID_Barang');
    $check = $this->M_Barang->check_data($id);
    if ($check == false) {
        $error = array(
            'status' => 'fail',
            'field' => 'ID_Barang',
            'message' => 'Data tidak ditemukan!',
            'status_code' => 502
        );

        return $this->response($error);
    }

    // Mengambil informasi barang
    $barang = $this->M_Barang->fetch_single_data($id);
    $stok_sekarang = $barang->Stok;    // Mendapatkan jumlah stok saat ini
    $stok_terbaru = $this->put('Stok'); // Mendapatkan jumlah stok yang baru

    // Memperbarui stok berdasarkan perbedaan jumlah stok baru dengan stok sekarang
    $stok_berkurang = $stok_sekarang - ($stok_sekarang - $stok_terbaru);

    $data = array(
        'Nama_Barang' => trim($this->put('Nama_Barang')),
        'Harga_Jual' => $this->put('Harga_Jual'),
        'Harga_Beli' => $this->put('Harga_Beli'),
        'Stok' => $stok_berkurang // Memperbarui stok dengan jumlah terbaru
    );

    $this->M_Barang->update_data($id, $data);
    $newData = $this->M_Barang->fetch_single_data($id);
    $response = array(
        'status' => 'success',
        'data' => $newData,
        'status_code' => 200,
    );

    return $this->response($response);
}

function index_delete()
{
    if (!$this->is_login()) {
        return;
       }
    $id = $this->delete('ID_Barang');
    $check = $this->M_Barang->check_data($id);
    if ($check == false) {
        $error = array(
            'status' => 'fail',
            'field' => 'id',
            'message' => 'Data tidak ditemukan!',
            'status_code' => 502
        );

        return $this->response($error);
    }

    $delete = $this->M_Barang->delete_data($id);
    $response = array(
        'status' => 'success',
        'data' => null,
        'status_code' => 200,
    );

    return $this->response($response);
}

}
?>