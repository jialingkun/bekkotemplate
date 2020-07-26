<?php
class Default_model extends CI_Model {

	public function __construct(){
		$this->load->database();
	}

	//GET DATABASE
	public function get_data_admin($id = NULL){
		$this->db->select('username,password');
		$this->db->from('admin');
		if ($id != NULL){
			$this->db->where('username',$id);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_data_admin_nopassword($id = NULL){
		$this->db->select('username');
		$this->db->from('admin');
		if ($id != NULL){
			$this->db->where('username',$id);
		}
		$query = $this->db->get();
		return $query->result_array();
	}


	//INSERT DATABASE
	public function insert_admin($data){
		$this->db->insert('admin', $data);
		if ($this->db->affected_rows() > 0 ) {
			$return_message = 'success';
		}else{
			$return_message = 'failed';
		}
		return $return_message;
	}




	//UPDATE DATABASE
	public function update_admin($id, $data){
		$this->db->where('username', $id);
		$this->db->update('admin', $data);
		if ($this->db->affected_rows() > 0 ) {
			$return_message = 'success';
		}else{
			$return_message = 'failed';
		}
		return $return_message;
	}


	//DELETE DATABASE
	public function delete_admin($id){
		$this->db->where('username', $id);
		$this->db->delete('admin');
		if ($this->db->affected_rows() > 0 ) {
			$return_message = 'success';
		}else{
			$return_message = 'failed';
		}
		return $return_message;
	}
	
}