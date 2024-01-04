<?php
class M_Pegawai extends CI_Model{

    function fetch_all()
    {
        $this->db->order_by('ID_Pegawai', 'DESC');
        $query = $this->db->get('pegawai');
        return $query->result_array();
    }
    
    function fetch_single_data($id)
    {
        $this->db->where("ID_Pegawai", $id);
        $query = $this->db->get('pegawai');
        return $query->row();
    }

    function check_data($id)
{
    $this->db->where("ID_Pegawai", $id);
    $query = $this->db->get('pegawai');
    if ($query->row()) {
        return true;
    } else {
        return false;
    }
}

function insert_api($data)
{
    $this->db->insert('pegawai', $data);
    if ($this->db->affected_rows() > 0) {
        return true;
    } else {
        return false;
    }
}

function update_data($id, $data){
    $this->db->where("ID_Pegawai", $id);
    $this->db->update("pegawai", $data);
}

function delete_data($id)

{
    $this->db->where("ID_Pegawai", $id);
    $this->db->delete("pegawai");
    if ($this->db->affected_rows() > 0) {
        return true;
    } else { return false; }

}
}

?>