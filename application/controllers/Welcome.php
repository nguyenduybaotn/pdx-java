<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->library('session');
		$this->load->model('tool_model');
		$this->load->helper('url');
        date_default_timezone_set("Asia/Ho_Chi_Minh");
    }
	
	public function index()
	{
		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']){
			header("location: ".base_url()."welcome/dashboard");
		}else{
			$this->load->view('welcome_message');
		}
		
	}
	public function kiemtradangnhap()
	{
		// user la bien truyen tu ajax trong file admin.js qua: user{ tendangnhap, matkhau }
		$data = $this->input->post('user');
		// API URL
		$url = 'http://localhost:8080/api/checkLogin';

		// User account info
		$userData = array(
			'userName' => $data['tendangnhap'],
			'userPass' => $data['matkhau']
		);

		// Create a new cURL resource
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/json"));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));

		$result = curl_exec($ch);
		$json_result = json_decode($result);
		if($json_result->message == "success"){
			$newdata = array(
				'code' 			=> $json_result->code,
				'idnhanvien'   	=> $json_result->idnhanvien,
				'tendangnhap'   => $data['tendangnhap'],
				'tinhtrang'     => $data['matkhau'],
				'logged_in' 	=> TRUE
			);
			// dua thong tin dang nhap vao $_SESSION
			$this->session->set_userdata($newdata);
			echo "success";
		}else{
			echo "error";
		} 
		// Close cURL resource
		curl_close($ch);		
	}
	public function dashboard(){
		//unset($_SESSION['logged_in']);
		// neu dang dang nhap roi thi qua dashboard, nguoc lai thi dang nhap
		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']){

			$data['title'] 	= "Dashboard";
			$data['view'] 	= "dashboard";
			$this->load->view( "dashboard",$data);
		}else{
			header("location: ".base_url()."welcome");
		}
	}
	public function logout()
	{
		unset($_SESSION['logged_in']);
		unset($_SESSION['code']);
		unset($_SESSION['tendangnhap']);
		unset($_SESSION['tinhtrang']);
		header("location: ".base_url()."welcome");
	}
	public function uphinh($idvanban)
	{
		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']){

			$data['title'] 		= "Dashboard";
			$data['view'] 		= "dashboard";
			$data['idvanban'] 	= $idvanban;
			$this->load->view( "uphinh",$data);
		}else{
			header("location: ".base_url()."welcome");
		}
	}
	public function upload($idvanban){
		if (isset($_POST) && !empty($_FILES['file'])) {
				$duoi = explode('.', $_FILES['file']['name']); // tách chuỗi khi gặp dấu .
				$duoi = $duoi[(count($duoi) - 1)]; //lấy ra đuôi file
				// Kiểm tra xem có phải file ảnh không
				if ($duoi === 'jpg' || $duoi === 'png' || $duoi === 'gif') {
					// tiến hành upload
					//echo $this->compress_image($_FILES['file']['tmp_name'],$_FILES['file']['tmp_name'],10);
					if (copy($_FILES['file']['tmp_name'], "\\\\192.168.0.69\\documents$\\pdx\\" . $idvanban.".".$duoi)) {
						// Nếu thành công
						die('Upload thành công file: ' . $idvanban.".".$duoi); //in ra thông báo + tên file
						//print_r($_FILES['file']);
					} else { // nếu không thành công
						die('Lỗi');
						//print_r($_FILES['file']); // in ra thông báo
					}
				} else { // nếu không phải file ảnh
					die('Chỉ được upload ảnh'); // in ra thông báo
				}
			} else {
				die('Lock'); // nếu không phải post method
			}
	}
}
