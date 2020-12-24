<?php

include_once ("Main.php");

class Template extends Main {

	public function __construct(){
		parent::__construct();
	}
	


	//index
	public function index(){
		$this->load->view('template/submit_template');
	}

	//Submit template
	public function submit(){
		$this->load->view('template/submit_template');
	}


	public function submit_loading(){
		sleep(5);
		echo "success";
	}


	public function submit_status(){
		session_start();
		$_SESSION['progress'] = "Menerima pesan";
		session_write_close();
		sleep(3);
		session_start();
		$_SESSION['progress'] = "Mengirim Pesan";
		session_write_close();
		sleep(3);
		session_start();
		$_SESSION['progress'] = "Menyeduh teh";
		session_write_close();
		sleep(3);
		session_start();
		$_SESSION['progress'] = "Memproses pesanan";
		session_write_close();
		sleep(3);
		echo "success";
	}


	public function submit_upload(){
		$config['upload_path']          = './uploads/';
		$config['allowed_types']        = 'gif|jpg|png|pdf';

		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('userfile')){
			echo $this->upload->display_errors();
		}
		else{
			echo "upload success";
		}
	}


	public function check_progress(){
		session_start();
		echo $_SESSION['progress'];
	}


}