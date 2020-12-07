<?php

include_once ("Main.php");

class Admin extends Main {

	public function __construct(){
		parent::__construct();
		$this->load->model('Admin_model');
	}
	


	//login
	public function loginadmin(){
		$this->load->view('admin/login');
	}

	//Dashboard
	public function dashboardadmin(){
		if ($this->checkcookieadmin()) {
			$this->load->view('admin/dashboard');
		}else{
			header("Location: ".base_url()."index.php/admin/loginadmin");
			die();
		}
	}

	
	//GET DATA

	//ambil data admin
	//note: password tidak diambil
	//parameter 1: true bila ingin return array, kosongi bila ingin Json
	public function get_all_admin($return_var = NULL){
		$data = $this->Admin_model->get_data_admin();
		if (empty($data)){
			$data = [];
		}else{
			foreach ($data as &$row){
				unset($row['password']);
			}
		}
		if ($return_var == true) {
			return $data;
		}else{
			echo json_encode($data);
		}
	}

	//ambil data admin berdasarkan username
	//note: ambil data admin dari database berdasarkan username
	//parameter 1: username
	//parameter 1: true bila ingin return array, kosongi bila ingin Json
	public function get_admin_by_id($id, $return_var = NULL){
		$filter = array('username'=> $id);
		$data = $this->Admin_model->get_data_admin($filter);
		if (empty($data)){
			$data = [];
		}else{
			foreach ($data as &$row){
				unset($row['password']);
			}
		}
		if ($return_var == true) {
			return $data;
		}else{
			echo json_encode($data);
		}
	}


	//INSERT

	//Tambah data admin
	//note: API hanya bisa diakses saat ada cookie admin
	//input: form POST seperti di bawah
	//output: success/failed/access denied
	public function insert_admin(){
		if ($this->checkcookieadmin()) {
			$data = array(
				'username' => $this->input->post('username',true),
				'password' => $this->openssl('encrypt',$this->input->post('password',true))
			);
			$insertStatus = $this->Admin_model->insert_admin($data);
			echo $insertStatus;
		}else{
			echo "access denied";
		}
	}


	//UPDATE

	//Ubah password admin
	//note: API hanya bisa diakses saat ada cookie admin
	//parameter 1: username
	//input: form POST seperti di bawah
	//output: success/failed/id not found/wrong old password/access denied
	public function update_password_admin($id){
		if ($this->checkcookieadmin()) {
			$oldpassword = $this->openssl('encrypt',$this->input->post('oldpassword',true));
			$newpassword = $this->openssl('encrypt',$this->input->post('newpassword',true));
			$filter = array('username'=> $id);
			$data = $this->Admin_model->get_data_admin($filter);
			if (empty($data)){
				echo "id not found";
			}else{
				foreach ($data as $row){
					if ($oldpassword == $row['password']){
						$update_data = array(
							'password' => $newpassword
						);
						$updateStatus = $this->Admin_model->update_admin($id,$update_data);
						echo $updateStatus;
					}else{
						echo "wrong old password";
					}
				}
			}
		}else{
			echo "access denied";
		}
	}


	//DELETE

	//Delete admin
	//note: API hanya bisa diakses saat ada cookie admin
	//parameter 1: username
	//output: success/failed/access denied
	public function delete_admin($id){
		if ($this->checkcookieadmin()) {
			$deleteStatus = $this->Admin_model->delete_admin($id);
			echo $deleteStatus;
		}else{
			echo "access denied";
		}
	}


	//OTHER

	//Login admin
	//note: -
	//input: form POST seperti di bawah
	//Output: berhasil login / gagal login
	public function cekloginadmin(){
		$username = $this->input->post('username',true);
		$password = $this->openssl('encrypt',$this->input->post('password',true));
		$filter = array('username'=> $username);
		$data = $this->Admin_model->get_data_admin($filter);
		$is_login = false;
		foreach ($data as $row){
			if ($username == $row['username'] && $password == $row['password']) {
				$this->create_cookie_encrypt("adminCookie",$username);
				$is_login = true;
				break;
			}
		}
		if ($is_login) {
			echo "berhasil login";
		}else{
			echo "gagal login";
		}
	}


	//Check cookie
	//note: tidak untuk front end
	public function checkcookieadmin(){
		$this->load->helper('cookie');
		if ($this->input->cookie('adminCookie',true)!=NULL) {
			$value = $this->openssl('decrypt',$this->input->cookie('adminCookie',true)); //decrypt first
			if (empty($value) || empty($this->get_admin_by_id($value,true))) {
				return false;
			}else{
				return true;
			}
		}else{
			return false;
		}
	}

	//Logout
	//note: menghapus cookie dan langsung redirect ke halaman login
	public function logoutadmin(){
		$this->load->helper('cookie');
		delete_cookie("adminCookie");
		header("Location: ".base_url()."index.php/admin/loginadmin");
		die();
	}


}