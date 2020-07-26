<?php

include_once ("Loadview.php");

class Default_controller extends Loadview {
	public function __construct(){
		parent::__construct();
		$this->load->model('Default_model');
		$this->load->helper('url_helper');
		date_default_timezone_set('Asia/Jakarta');
	}

	
	//GET DATA

	//ambil data admin
	//note: password tidak diambil
	//parameter 1: true bila ingin return array, kosongi bila ingin Json
	public function get_all_admin($return_var = NULL){
		$data = $this->Default_model->get_data_admin_nopassword();
		if (empty($data)){
			$data = [];
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
		$data = $this->Default_model->get_data_admin_nopassword($id);
		if (empty($data)){
			$data = [];
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
				'username' => $this->input->post('username'),
				'password' => md5($this->input->post('password'))
			);
			$insertStatus = $this->Default_model->insert_admin($data);
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
			$oldpassword = md5($this->input->post('oldpassword'));
			$newpassword = md5($this->input->post('newpassword'));
			$filter = array('username'=> $id);
			$data = $this->Default_model->get_data_admin($filter);
			if (empty($data)){
				echo "id not found";
			}else{
				foreach ($data as $row){
					if ($oldpassword == $row['password']){
						$update_data = array(
							'password' => $newpassword
						);
						$updateStatus = $this->Default_model->update_admin($id,$update_data);
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
			$deleteStatus = $this->Default_model->delete_admin($id);
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
		$username = $this->input->post('username');
		$password = md5($this->input->post('password'));
		$data = $this->Default_model->get_data_admin();
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
			return true;
		}else{
			return false;
		}
	}

	//Logout
	//note: menghapus cookie dan langsung redirect ke halaman login
	public function logoutadmin(){
		$this->load->helper('cookie');
		delete_cookie("adminCookie");
		header("Location: ".base_url()."index.php/login");
		die();
	}

	//untuk membuat cookie
	//parameter 1: nama cookie (opsional)
	//parameter 2: value cookie (opsional)
	//parameter 3: expire (opsional)
	//input: form POST seperti di bawah (opsional bila tidak bisa menggunakan parameter)
	//output: -
	public function create_cookie($name = NULL, $value = NULL, $expire = NULL){
		if ($name == NULL) {
			$name = $this->input->post('name');
		}
		if ($value == NULL) {
			$value = $this->input->post('value');
		}
		if ($expire == NULL) {
			$expire = 0;
		}
		$this->load->helper('cookie');
		$cookie= array(
			'name'   => $name,
			'value'  => $value,
			'expire' => $expire
		);
		$this->input->set_cookie($cookie);
		// echo "cookie created";
	}

	//untuk mengambil cookie
	//note: JANGAN GUNAKAN INI UNTUK MENGAMBIL COOKIE USER (karena sudah di encrypt), gunakan get_cookie_decrypt() di bawah
	//parameter 1: nama cookie
	//output: no cookie / $cookie
	public function get_cookie($name){
		$this->load->helper('cookie');
		if ($this->input->cookie($name,true)!=NULL) {
			echo $this->input->cookie($name,true);
		}else{
			echo "no cookie";
		}
	}

	//untuk membuat cookie yang diencrypt
	//parameter 1: nama cookie (opsional)
	//parameter 2: value cookie (opsional)
	//parameter 3: expire (opsional)
	//input: form POST seperti di bawah (opsional bila tidak bisa menggunakan parameter)
	//output: -
	public function create_cookie_encrypt($name = NULL, $value = NULL, $expire = NULL){
		if ($name == NULL) {
			$name = $this->input->post('name');
		}
		if ($value == NULL) {
			$value = $this->input->post('value');
		}
		if ($expire == NULL) {
			$expire = 0;
		}
		$this->load->helper('cookie');
		$cookie= array(
			'name'   => $name,
			'value'  => str_rot13($value), //Not really encrypt anything, just jumble text :P
			'expire' => $expire
		);
		$this->input->set_cookie($cookie);
		// echo "cookie created";
	}

	//untuk mengambil cookie yang di decrypt dari fungsi create_cookie_encrypt
	//parameter 1: nama cookie
	//output: no cookie / $cookie
	public function get_cookie_decrypt($name){
		$this->load->helper('cookie');
		if ($this->input->cookie($name,true)!=NULL) {
			echo str_rot13($this->input->cookie($name,true));
		}else{
			echo "no cookie";
		}
	}


}