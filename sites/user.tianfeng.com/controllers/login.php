<?php

class Login extends MY_Controller{
	function __construct()
	{
		parent::__construct();
		$this ->load->database();
		$this->load->helper(array('url','get_user'));
	
	}
	
	/**
	 * 登录首页
	 */
	public function index(){
	    if(isset($_SESSION['user']) && $_SESSION['user']['status'] == 0){
	        redirect('user/pay_register/');
	    }
		$this->load->view('login');
	}
	
	
	/**
	 * 登录提交
	 */
	public function signin(){
		$phone    = trim(strval($this->input->post('admin_user',TRUE))) ;
		$password = trim(strval($this->input->post('admin_psd',TRUE))) ;
		
		$salt = $this->db->select('salt')->where(array('phone'=>$phone))->get('user')->row_array();
		if(isset($salt) && !empty($salt)){
		    $password = en_pass($password, $salt['salt']);
			$user = $this->db->where(array('phone'=>$phone,'password'=>$password))->get('user')->row_array();
		}else{
		    go('该用户不存在','/login/index');
		}

		if (isset($user) && !empty($user)) {
			#成功，将用户信息保存至session
			$_SESSION['user'] = $user;
			redirect('index/index');
		} else {
			# error
			go('密码不正确','/login/index');
		}
	}
	
	public function loginout(){
		unset($_SESSION['user']);
		redirect('login');
	}
	
	
}
?>
