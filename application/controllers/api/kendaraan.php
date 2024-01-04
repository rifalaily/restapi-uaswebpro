<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Kendaraan extends REST_Controller {
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
        $this->load->model('M_Kendaraan');
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
    $id = $this->get('ID_Kendaraan');
   if ($id == '') {
       $data = $this->M_Kendaraan->fetch_all();
   } else {
       $data = $this->M_Kendaraan->fetch_single_data($id);
   }

   $this->response($data, 200);
}


function index_post(){
    if ($this->post('ID_Pelanggan') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'ID_Pelanggan',
            'message' => 'Isian ID_Pelanggan tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }
    if ($this->post('Merek') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Merek',
            'message' => 'Isian Merek tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }
    if ($this->post('Model') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Model',
            'message' => 'Isian Model tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }
    
    if ($this->post('Tahun_Pembuatan') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Tahun_Pembuatan',
            'message' => 'Isian Tahun Pembuatan tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }
    if ($this->post('Nomor_Plat') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Nomor_Plat',
            'message' => 'Isian Nomor Plat  tidak boleh kosong',
            'status_code' => 502
        );
    
        return $this->response($response);
    }
    
    $data = array(
        'ID_Pelanggan' => $this->post('ID_Pelanggan'),
        'Merek' => $this->post('Merek'),
        'Model' => $this->post('Model'),
        'Tahun_Pembuatan' => trim($this->post('Tahun_Pembuatan')),
        'Nomor_Plat' => $this->post('Nomor_Plat')
    );
    
    $this->M_Kendaraan->insert_api($data);
    $last_row = $this->db->select('*')->order_by('ID_Kendaraan', 'desc')->limit(1)->get('kendaraan')->row();
    $response = array(
        'status' => 'success',
        'data' => $last_row,
        'status_code' => 200,
    );
    
    return $this->response($response);
    
}

function index_put()
{
    $id = $this->put('ID_Kendaraan');
    $check = $this->M_Kendaraan->check_data($id);
    
    if ($check == false) {
        $error = array(
            'status' => 'fail',
            'field' => 'ID_Kendaraan',
            'message' => 'Data tidak ditemukan!',
            'status_code' => 502
        );

        return $this->response($error);
    }
    if ($this->put('ID_Pelanggan') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'ID_Pelanggan',
            'message' => 'Isian ID_Pelanggan tidak boleh kosong',
            'status_code' => 502
        );

        return $this->response($response);
    }

    if ($this->put('Merek') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Merek',
            'message' => 'Isian Merek tidak boleh kosong',
            'status_code' => 502
        );

        return $this->response($response);
    }
    if ($this->put('Model') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Model',
            'message' => 'Isian Model  tidak boleh kosong',
            'status_code' => 502
        );

        return $this->response($response);
    }

    if ($this->put('Tahun_Pembuatan') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Tahun_Pembuatan',
            'message' => 'Isian Tahun Pembuatan tidak boleh kosong',
            'status_code' => 502
        );

        return $this->response($response);
    }
    if ($this->put('Nomor_Plat') == '') {
        $response = array(
            'status' => 'fail',
            'field' => 'Nomor_Plat',
            'message' => 'Isian Nomor Plat  tidak boleh kosong',
            'status_code' => 502
        );

        return $this->response($response);
    }

    $data = array(
        'ID_Pelanggan' => trim($this->put('ID_Pelanggan')),
        'Merek' => trim($this->put('Merek')),
        'Model' => $this->put('Model'),
        'Tahun_Pembuatan' => trim($this->put('Tahun_Pembuatan')),
        'Nomor_Plat' => $this->put('Nomor_Plat')
    );

    $this->M_Kendaraan->update_data($id, $data);
    $newData = $this->M_Kendaraan->fetch_single_data($id);
    $response = array(
        'status' => 'success',
        'data' => $newData,
        'status_code' => 200,
    );

    return $this->response($response);
}

function index_delete()
{
    $id = $this->delete('ID_Kendaraan');
    $check = $this->M_Kendaraan->check_data($id);
    if ($check == false) {
        $error = array(
            'status' => 'fail',
            'field' => 'id',
            'message' => 'Data tidak ditemukan!',
            'status_code' => 502
        );

        return $this->response($error);
    }

    $delete = $this->M_Kendaraan->delete_data($id);
    $response = array(
        'status' => 'success',
        'data' => null,
        'status_code' => 200,
    );

    return $this->response($response);
}

}
?>
