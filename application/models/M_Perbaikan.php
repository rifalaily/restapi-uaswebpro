<?php
class M_Perbaikan extends CI_Model{

    function fetch_all()
    {
        $this->db->order_by('ID_Perbaikan', 'DESC');
        $query = $this->db->get('perbaikan');
        return $query->result_array();
    }
    
    function fetch_single_data($id)
    {
        $this->db->where("ID_Perbaikan", $id);
        $query = $this->db->get('perbaikan');
        return $query->row();
    }

    function check_data($id)
{
    $this->db->where("ID_Perbaikan", $id);
    $query = $this->db->get('perbaikan');
    if ($query->row()) {
        return true;
    } else {
        return false;
    }
}

function insert_api($data)
{
    $this->db->insert('perbaikan', $data);
    if ($this->db->affected_rows() > 0) {
        return true;
    } else {
        return false;
    }
}

function update_data($id, $data){
    $this->db->where("ID_Perbaikan", $id);
    $this->db->update("perbaikan", $data);
}

function delete_data($id)

{
    $this->db->where("ID_Perbaikan", $id);
    $this->db->delete("perbaikan");
    if ($this->db->affected_rows() > 0) {
        return true;
    } else { return false; }

}
}

?>