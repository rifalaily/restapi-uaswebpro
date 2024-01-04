<?php
class M_Pembelian_Barang extends CI_Model{

    function fetch_all()
    {
        $this->db->order_by('ID_Pembelian', 'DESC');
        $query = $this->db->get('pembelian_barang');
        return $query->result_array();
    }
    
    function fetch_single_data($id)
    {
        $this->db->where("ID_Pembelian", $id);
        $query = $this->db->get('pembelian_barang');
        return $query->row();
    }

    function check_data($id)
{
    $this->db->where("ID_Pembelian", $id);
    $query = $this->db->get('pembelian_barang');
    if ($query->row()) {
        return true;
    } else {
        return false;
    }
}

function insert_api($data)
{
    $this->db->insert('pembelian_barang', $data);
    if ($this->db->affected_rows() > 0) {
        return true;
    } else {
        return false;
    }
}

function update_data($id, $data){
    $this->db->where("ID_Pembelian", $id);
    $this->db->update("pembelian_barang", $data);
}

function delete_data($id)

{
    $this->db->where("ID_Pembelian", $id);
    $this->db->delete("pembelian_barang");
    if ($this->db->affected_rows() > 0) {
        return true;
    } else { return false; }

}
}

?>