<?php
class M_Pelanggan extends CI_Model{

    function fetch_all()
    {
        $this->db->order_by('ID_Pelanggan', 'DESC');
        $query = $this->db->get('pelanggan');
        return $query->result_array();
    }
    
    function fetch_single_data($id)
    {
        $this->db->where("ID_Pelanggan", $id);
        $query = $this->db->get('pelanggan');
        return $query->row();
    }

    function check_data($id)
{
    $this->db->where("ID_Pelanggan", $id);
    $query = $this->db->get('pelanggan');
    if ($query->row()) {
        return true;
    } else {
        return false;
    }
}

function insert_api($data)
{
    $this->db->insert('pelanggan', $data);
    if ($this->db->affected_rows() > 0) {
        return true;
    } else {
        return false;
    }
}

function update_data($id, $data){
    $this->db->where("ID_Pelanggan", $id);
    $this->db->update("pelanggan", $data);
}

function delete_data($id)

{
    $this->db->where("ID_Pelanggan", $id);
    $this->db->delete("pelanggan");
    if ($this->db->affected_rows() > 0) {
        return true;
    } else { return false; }

}
}

?>