<?php
require_once('Main.php');

class Dashboard extends Main
{
	function __construct()
	{
		parent::__construct();
	}	
	
	function index($shop_id = false)
	{
		if ( !$shop_id ) {
		// if shop id is not passed, redirect to site url
			redirect( site_url() );
		}

		if ( ! $this->user->is_system_user() ) {
		// if the use is not system user
		
			// get allowed shop id
			$allowed_shop_id = $this->session->userdata('allow_shop_id');

			if ( $allowed_shop_id != $shop_id ) {
			// if the requested shop id is same with allowed shop id
				
				// show error message and redirect to allowed shop id
				$this->session->set_flashdata('error','Sorry, You don`t have permission to access that shop.');				
				redirect(site_url( "/dashboard/index/" . $allowed_shop_id ));
			}
		}

		// keep shop id  in session
		$this->session->set_userdata('shop_id', $shop_id);
		$this->session->set_userdata('action', 'shop_list');

		// load dashboard view
		$content['content'] = $this->load->view('dashboard', array(), true);
		
		$this->load_template($content);	
	}
	
	function profile()
	{
		$user_id = $this->user->get_logged_in_user_info()->user_id;
		$status = "";
		$message = "";
		
		if ($this->input->server('REQUEST_METHOD')=='POST') {
			$user_data = array(
				'user_name' => htmlentities($this->input->post('user_name'))
			);
							
			//If new user password exists,change password
			if ($this->input->post('user_password')!='') {
				$user_data['user_pass'] = md5($this->input->post('user_password'));
				$user_data['user_name'] = htmlentities( $this->input->post('user_name' ));
				$user_data['user_email'] = $this->input->post('user_email');
			}
			
			if ($this->user->update_profile($user_data,$user_id)) {
				$status = 'success';
				$message = 'User is successfully updated.';
			} else {
				$status = 'error';
				$message = 'Database error occured.Please contact your system administrator.';
			}
		}
		
		$data['user'] = $this->user->get_info($user_id);
		$data['status'] = $status;
		$data['message'] = $message;
		
		$content['content'] = $this->load->view('users/profile',$data,true);		
		
		$this->load_template($content);
	}
	
	//is exist
	function exists($user_id=null)
	{
		$user_name = $_REQUEST['user_name'];
		
		if (strtolower($this->user->get_info($user_id)->user_name) == strtolower($user_name)) {
			echo "true";
		} else if($this->user->exists(array('user_name'=>$_REQUEST['user_name']))) {
			echo "false";
		} else {
			echo "true";
		}
	}
	
	function backup()
	{
		// Load the DB utility class
		$this->load->dbutil();
		
		// Backup your entire database and assign it to a variable
		$backup =& $this->dbutil->backup();
		
		// Load the download helper and send the file to your desktop
		$this->load->helper('download');
		force_download('mokets.zip', $backup);
	}
}
?>