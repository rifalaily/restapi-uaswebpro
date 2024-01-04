<?php
class M_Detail_Pembelian extends CI_Model{

    function fetch_all()
    {
        $this->db->order_by('ID_Detail', 'DESC');
        $query = $this->db->get('detail_pembelian');
        return $query->result_array();
    }
    
    function fetch_single_data($id)
    {
        $this->db->where("ID_Detail", $id);
        $query = $this->db->get('detail_pembelian');
        return $query->row();
    }

    function check_data($id)
{
    $this->db->where("ID_Detail", $id);
    $query = $this->db->get('detail_pembelian');
    if ($query->row()) {
        return true;
    } else {
        return false;
    }
}

function insert_api($data)
{
    $this->db->insert('detail_pembelian', $data);
    if ($this->db->affected_rows() > 0) {
        return true;
    } else {
        return false;
    }
}

function update_data($id, $data){
    $this->db->where("ID_Barang", $id);
    $this->db->update("barang", $data);
}

function get_stock($id){
    $this->db->select('stok'); 
    $this->db->where("ID_Barang", $id); 
    $query = $this->db->get('barang'); 
    $result = $query->row();
    return $result->stok;
}

function delete_data($id)

{
    $this->db->where("ID_Detail", $id);
    $this->db->delete("detail_pembelian");
    if ($this->db->affected_rows() > 0) {
        return true;
    } else { return false; }

}

}

?>