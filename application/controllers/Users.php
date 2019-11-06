<?php
require_once('Main.php');

class Users extends Main
{
	function __construct()
	{
		parent::__construct('users');
	}

	//create
	function add()
	{
		if(!$this->session->userdata('is_shop_admin')) {
		      $this->check_access('add');
		}
		
		if ($this->input->server('REQUEST_METHOD')=='POST') {

			// server side validation
			if ( ! $this->is_valid_input()) {
				redirect( site_url( 'users/add' ));
			}
			
			if($this->input->post('role_id') == 4){
				$is_shop_admin = 1;
				$shop_id = $this->input->post('shop_id');
			} else {
				$is_shop_admin = 0;
				$shop_id = 0;
			}
			
			$user_data = array(
				'user_name' => htmlentities( $this->input->post('user_name') ),
				'user_email' => $this->input->post('user_email'),
				'user_pass'=> md5($this->input->post('user_password')),
				'role_id'=>$this->input->post('role_id'),
				'shop_id'       => $shop_id,
				'is_shop_admin' => $is_shop_admin
			);
			
			$permissions = $this->input->post('permissions')!=false? $this->input->post('permissions'): array();
			
			if ($this->user->save($user_data,$permissions)) {
				$this->session->set_flashdata('success','User is successfully added.');
			} else {
				$this->session->set_flashdata('error','Database error occured.Please contact your system administrator.');
			}

			redirect(site_url('users'));
		}	
		
		$content['content'] = $this->load->view('users/add',array(),true);		
		
		$this->load_template($content);
	}
	
	//retrieve
	function index()
	{
		$this->session->unset_userdata('searchterm');
	
		$pag = $this->config->item('pagination');
		$pag['base_url'] = site_url('users/index');
		$pag['total_rows'] = $this->user->count_all();
		
		$data['users'] = $this->user->get_all($pag['per_page'],$this->uri->segment(3));
		$data['pag'] = $pag;
		
		$content['content'] = $this->load->view('users/view',$data,true);		
		
		$this->load_template($content);
	}
	
	function search()
	{
		$search_term = $this->searchterm_handler($this->input->post('searchterm'));
		
		$pag = $this->config->item('pagination');
		
		$pag['base_url'] = site_url('users/search');
		$pag['total_rows'] = $this->user->count_all_by(array('searchterm'=>$search_term));
		
		$data['searchterm'] = $search_term;
		$data['users'] = $this->user->get_all_by(array('searchterm'=>$search_term),$pag['per_page'],$this->uri->segment(3));
		$data['pag'] = $pag;
		
		$content['content'] = $this->load->view('users/search',$data,true);		
		
		$this->load_template($content);
	}
	
	function searchterm_handler($searchterm)
	{
	    if ($searchterm) {
	        $this->session->set_userdata('searchterm', $searchterm);
	        return $searchterm;
	    } elseif ($this->session->userdata('searchterm')) {
	        $searchterm = $this->session->userdata('searchterm');
	        return $searchterm;
	    } else {
	        $searchterm ="";
	        return $searchterm;
	    }
	}
	
	//update
	function edit($user_id=0)
	{
		if(!$this->session->userdata('is_shop_admin')) {
		    $this->check_access('edit');
		}
	
		if ($this->input->server('REQUEST_METHOD')=='POST') {

			// server side validation
			if ( ! $this->is_valid_input( $user_id )) {
				redirect( site_url( 'users/edit/'. $user_id ));
			}

			if ($this->user->get_logged_in_user_info()->user_id != $user_id &&
				$this->user->get_info($user_id)->is_owner == 1) {
					$this->session->set_flashdata('error','You can\'t edit site owner.');
			} else {
				
				if($this->input->post('role_id') == 4){
					$is_shop_admin = 1;
					$shop_id = $this->input->post('shop_id');
				} else {
					$is_shop_admin = 0;
					$shop_id = 0;
				}
				
				$user_data = array(
					'user_name'     => htmlentities( $this->input->post('user_name') ),
					'user_email'    => $this->input->post('user_email'),
					'role_id'       => $this->input->post('role_id'),
					'shop_id'       => $shop_id,
					'is_shop_admin' => $is_shop_admin
				);
				$permissions = $this->input->post('permissions')!=false? $this->input->post('permissions'): array();
				
				
								
				//If new user password exists,change password
				if ($this->input->post('user_password')!='') {
					$user_data['user_pass'] = md5($this->input->post('user_password'));
				}
				
				if ($this->user->save($user_data,$permissions,$user_id)) {
					$this->session->set_flashdata('success','User is successfully updated.');
				} else {
					$this->session->set_flashdata('error','Database error occured.Please contact your system administrator.');
				}
			}
			redirect(site_url('users'));
		}
		
		$data['user'] = $this->user->get_info($user_id);
		
		$content['content'] = $this->load->view('users/edit',$data,true);		
		
		$this->load_template($content);
	}
	
	//delete
	function delete($user_id=0)
	{
		if(!$this->session->userdata('is_shop_admin')) {
		     $this->check_access('delete');
		}
		
		if ($this->user->get_logged_in_user_info()->user_id == $user_id) {
			$this->session->set_flashdata('error','You can\'t delete yourself.');
		} else if ($this->user->get_info($user_id)->is_owner == 1) {
			$this->session->set_flashdata('error','You can\'t delete site owner.');
		} else {
			if ($this->user->delete($user_id)) {
				$this->session->set_flashdata('success','The user is successfully deleted.');
			} else {
				$this->session->set_flashdata('error','Database error occured.Please contact your system administrator.');
			}
		}
		redirect(site_url('users'));
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

	/**
	 * Determines if valid input.
	 *
	 * @param      integer|string  $user_id  The user identifier
	 *
	 * @return     boolean         True if valid input, False otherwise.
	 */
	function is_valid_input( $user_id = 0 )
	{
		$email_rule = 'required|min_length[4]|callback_is_valid_email['. $user_id  .']';
		$rule = 'required|min_length[4]';

		$this->form_validation->set_rules( 'user_email', 'Email Address', $email_rule );
		$this->form_validation->set_rules( 'user_name', 'user_name', $rule );
		if ( $user_id == 0 ) {
		// password is required if new user
			$this->form_validation->set_rules( 'user_password', 'user_password', $rule );
			$this->form_validation->set_rules( 'conf_password', 'conf_password', $rule );	
		}

		if ( $this->form_validation->run() == FALSE ) {
			$this->session->set_flashdata('error', validation_errors());
			return false;
		}

		return true;
	}

	/**
	 * Determines if valid email.
	 *
	 * @param      <type>   $user_email  The user email
	 * @param      integer  $user_id     The user identifier
	 *
	 * @return     boolean  True if valid email, False otherwise.
	 */
	function is_valid_email( $user_email, $user_id = 0 )
	{		
		if ( strtolower( $this->user->get_info( $user_id )->user_email ) == strtolower( $user_email )) {

			return true;
		} else if ( $this->user->exists( array( 'user_email' => $_REQUEST['user_email'] ))) {

			$this->form_validation->set_message('is_valid_email', 'Email Address is already existed in the system');
			return false;
		} else {

			return true;
		}
	}
}
?>