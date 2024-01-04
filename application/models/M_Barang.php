<?php
class M_Barang extends CI_Model{

    function fetch_all()
    {
        $this->db->order_by('ID_Barang', 'DESC');
        $query = $this->db->get('barang');
        return $query->result_array();
    }
    
    function fetch_single_data($id)
    {
        $this->db->where("ID_Barang", $id);
        $query = $this->db->get('barang');
        return $query->row();
    }

    function check_data($id)
{
    $this->db->where("ID_Barang", $id);
    $query = $this->db->get('barang');
    if ($query->row()) {
        return true;
    } else {
        return false;
    }
}

function insert_api($data)
{
    $this->db->insert('barang', $data);
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

function delete_data($id)

{
    $this->db->where("ID_Barang", $id);
    $this->db->delete("barang");
    if ($this->db->affected_rows() > 0) {
        return true;
    } else { return false; }

}
}

?>