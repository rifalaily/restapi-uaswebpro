<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Detail_Pembelian extends REST_Controller {
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
        $this->load->model('M_Detail_Pembelian');
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

    $id = $this->get('ID_Detail');
    if ($id == '') {
        $data = $this->M_Detail_Pembelian->fetch_all();
    } else {
        $data = $this->M_Detail_Pembelian->fetch_single_data($id);
    }

    $this->response($data, 200);
}


function index_post(){
    if ($this->post('ID_Pembelian') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'ID_Pembelian',
            'message' => 'Isian Id Pembelian tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }
    if ($this->post('ID_Barang') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'ID_Barang',
            'message' => 'Isian ID_Barang tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }
    if ($this->post('Jumlah_Barang') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Jumlah_Barang',
            'message' => 'Isian Jumlah_Barang tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }
    if ($this->post('Total_Pembelian') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Total_Pembelian',
            'message' => 'Isian Total_Pembelian tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }
    
    $data = array(
        'ID_Pembelian' => $this->post('ID_Pembelian'),
        'ID_Barang' => $this->post('ID_Barang'),
        'Jumlah_Barang' => $this->post('Jumlah_Barang'),
        'Total_Pembelian' => $this->post('Total_Pembelian')
    );
    
    $this->M_Detail_Pembelian->insert_api($data);
    $stok=$this->M_Detail_Pembelian->get_stock($this->post('ID_Barang'));
    $this->M_Detail_Pembelian->update_data($this->post('ID_Barang'),array('Stok'=>$stok-$this->post('Jumlah_Barang')));

    $last_row = $this->db->select('*')->order_by('ID_Detail', 'desc')->limit(1)->get('detail_pembelian')->row();
    $response = array(
        'status' => 'success',
        'data' => $last_row,
        'status_code' => 200,
    );
    
    return $this->response($response);
    
}

function index_put()
{
    $id = $this->put('ID_Detail');
    $check = $this->M_Detail_Pembelian->check_data($id);
    
    if ($check == false) {
        $error = array(
            'status' => 'fail',
            'field' => 'ID_Detail',
            'message' => 'Data tidak ditemukan!',
            'status_code' => 502
        );

        return $this->response($error);
    }

    if ($this->put('ID_Pembelian') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'ID_Pembelian',
            'message' => 'Isian Id Pembelian tidak boleh kosong',
            'status_code' => 502
        );

        return $this->response($response);
    }
    if ($this->put('ID_Barang') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'ID_Barang',
            'message' => 'Isian ID Barang  tidak boleh kosong',
            'status_code' => 502
        );

        return $this->response($response);
    }
    if ($this->put('Jumlah_Barang') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Jumlah_Barang',
            'message' => 'Isian Jumlah Barang  tidak boleh kosong',
            'status_code' => 502
        );

        return $this->response($response);
    }
    if ($this->put('Total_Pembelian') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Total_Pembelian',
            'message' => 'Isian Total Pembelian tidak boleh kosong',
            'status_code' => 502
        );

        return $this->response($response);
    }

    $data = array(
        'ID_Pembelian' => trim($this->put('ID_Pembelian')),
        'ID_Barang' => $this->put('ID_Barang'),
        'Jumlah_Barang' => $this->put('Jumlah_Barang'),
        'Total_Pembelian' => $this->put('Total_Pembelian')
    );

    $this->M_Detail_Pembelian->update_data($id, $data);
    $newData = $this->M_Detail_Pembelian->fetch_single_data($id);
    $response = array(
        'status' => 'success',
        'data' => $newData,
        'status_code' => 200,
    );

    return $this->response($response);
}

function index_delete()
{
    $id = $this->delete('ID_Detail');
    $check = $this->M_Detail_Pembelian->check_data($id);
    if ($check == false) {
        $error = array(
            'status' => 'fail',
            'field' => 'ID_Detail',
            'message' => 'Data tidak ditemukan!',
            'status_code' => 502
        );

        return $this->response($error);
    }

    $delete = $this->M_Detail_Pembelian->delete_data($id);
    $response = array(
        'status' => 'success',
        'data' => null,
        'status_code' => 200,
    );

    return $this->response($response);
}

}
?>