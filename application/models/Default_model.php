<?php
class Default_model extends CI_Model {

	public function __construct(){
		$this->load->database();
	}

	//GET DATABASE
	public function get_data_admin($filter = NULL){
		$this->db->select('*');
		$this->db->from('admin');
		if ($filter != NULL){
			$this->db->where($filter);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_count_admin($filter = NULL){
		$this->db->from('admin');
		if ($filter != NULL){
			$this->db->where($filter);
		}
		$count = $this->db->count_all_results();
		return $count;
	}


	//INSERT DATABASE
	public function insert_admin($data){
		$filter = array('username'=> $data['username']);
		if ($this->get_count_admin($filter)==0) {
			$this->db->insert('admin', $data);
			if ($this->db->affected_rows() > 0 ) {
				$return_message = 'success';
			}else{
				$return_message = 'failed';
			}
		}else{
			$return_message = 'already exist';
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