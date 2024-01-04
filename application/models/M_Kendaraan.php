<?php
class M_Kendaraan extends CI_Model{

    function fetch_all()
    {
        $this->db->order_by('ID_Kendaraan', 'DESC');
        $query = $this->db->get('kendaraan');
        return $query->result_array();
    }
    
    function fetch_single_data($id)
    {
        $this->db->where("ID_Kendaraan", $id);
        $query = $this->db->get('kendaraan');
        return $query->row();
    }

    function check_data($id)
{
    $this->db->where("ID_Kendaraan", $id);
    $query = $this->db->get('kendaraan');
    if ($query->row()) {
        return true;
    } else {
        return false;
    }
}

function insert_api($data)
{
    $this->db->insert('kendaraan', $data);
    if ($this->db->affected_rows() > 0) {
        return true;
    } else {
        return false;
    }
}

function update_data($id, $data){
    $this->db->where("ID_Kendaraan", $id);
    $this->db->update("kendaraan", $data);
}

function delete_data($id)

{
    $this->db->where("ID_Kendaraan", $id);
    $this->db->delete("kendaraan");
    if ($this->db->affected_rows() > 0) {
        return true;
    } else { return false; }

}
}

?>