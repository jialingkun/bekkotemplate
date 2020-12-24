<?php
class Main_model extends CI_Model {

	public function __construct(){
		$this->load->database();
	}

	//GET DATABASE

	public function get_data_error_log($orderby = NULL, $sort = "asc", $limit = NULL){
		$this->db->select('*');
		$this->db->from('error_log');
		if ($orderby != NULL) {
			$this->db->order_by($orderby, $sort);
		}
		if ($limit != NULL) {
			$this->db->limit($limit);
		}
		$query = $this->db->get();
		return $query->result_array();
	}


	//INSERT DATABASE

	public function insert_error_log($data){
		$this->db->insert('error_log', $data);
		if ($this->db->affected_rows() > 0 ) {
			$return_message = 'success';
		}else{
			$return_message = 'failed';
		}
		return $return_message;
	}




	//UPDATE DATABASE


	//DELETE DATABASE



	//OTHER
	//Insert or Update
	public function insertOrUpdate($table, $data) {
		if (empty($table) || empty($data)) return false;
		$duplicate_data = array();
		foreach($data AS $key => $value) {
			if ($value!=NULL) {
				$duplicate_data[] = sprintf("%s='%s'", $key, $value);
			}else{
				$duplicate_data[] = sprintf("%s=NULL", $key);
			}
			
		}

		$sql = sprintf("%s ON DUPLICATE KEY UPDATE %s", $this->db->insert_string($table, $data), implode(',', $duplicate_data));
		$this->db->query($sql);
		if ($this->db->affected_rows() > 0 ) {
			$return_message = 'success';
		}else{
			$return_message = 'failed';
		}
		return $return_message;
	}

	public function customUpdate($table, $columnid, $id, $data){
		$this->db->where($columnid, $id);
		$this->db->update($table, $data);
		if ($this->db->affected_rows() > 0 ) {
			$return_message = 'success';
		}else{
			$return_message = 'failed';
		}
		return $return_message;
	}



	//filter: array
	//orderby: 'title DESC, name ASC'
	//groupby: string or array
	//select: 'title, content, date'
	//limit: int
	//limitoffset: int
	public function customGet($table, $filter = NULL, $orderby = NULL, $groupby = NULL, $select = NULL, $limit = NULL, $limitoffset = 0){
		if ($select != NULL){
			$this->db->select($select);
		}else{
			$this->db->select('*');
		}
		$this->db->from($table);
		if ($filter != NULL){
			$this->db->where($filter);
		}
		if ($orderby != NULL) {
			$this->db->order_by($orderby);
		}
		if ($groupby != NULL) {
			$this->db->group_by($groupby);
		}
		if ($limit != NULL) {
			$this->db->limit($limit,$limitoffset);
		}
		$query = $this->db->get();
		return $query->result_array();
	}

	
	
}