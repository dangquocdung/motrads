<?php
require_once('Main.php');
class Abouts extends Main
{
	function __construct()
	{
		parent::__construct('abouts');
		$this->load->library('uploader');
	}
	
	function index()
	{
		$this->session->unset_userdata('searchterm');
		
		$pag['base_url'] = site_url('about/index');
		$data['about'] = $this->about->get_info(1);
		$content['content'] = $this->load->view('about/add',$data,true);		
		$this->load_template($content, false);
	}
	
	function add()
	{
	 		if(!$this->session->userdata('is_city_admin')) {
	 		      $this->check_access('add');
	 		}
	 		$action = "save";
	 		unset($_POST['save']);
	 		if (htmlentities($this->input->post('gallery'))) {
	 			$action = "gallery";
	 			unset($_POST['gallery']);
	 		}
	 		
	 		if ($this->input->server('REQUEST_METHOD')=='POST') {
	 			$feed_data = $this->input->post();
	 
	 			$temp = array();
	 			foreach ( $feed_data as $key=>$value ) {
	 				$temp[$key] = $value;
	 			}
	 			
	 			$about_data = $temp;
	 			$about_data['id'] = 1;
	 			if ($this->about->save($about_data,1)) {			
	 				$this->session->set_flashdata('success','About Information is successfully added.');
	 			} else {
	 				$this->session->set_flashdata('error','Database error occured.Please contact your system administrator.');
	 			}
	 			
	 			if ($action == "gallery") {
	 				redirect(site_url('abouts/gallery/'.$about_data['id']));
	 			} else {
	 				redirect(site_url('abouts'));
	 			}
	 		}
	 		$data['about'] = $this->about->get_info(1);
	 		$content['content'] = $this->load->view('abouts/add',array(),true);
	 		$this->load_template($content);
	 
	}
		
		
	function edit($feed_id=0)
	{
		
	}
	
	function gallery($id)
	{
		session_start();
		$_SESSION['parent_id'] = $id;
		$_SESSION['type'] = 'about';
    	$content['content'] = $this->load->view('about/gallery', array('id' => $id), true);
    	
    	$this->load_template($content);
	}
	
	function upload($feed_id=0)
	{
		if(!$this->session->userdata('is_city_admin')) {
		    $this->check_access('edit');
		}
		
		$upload_data = $this->uploader->upload($_FILES);
		
		if (!isset($upload_data['error'])) {
			foreach ($upload_data as $upload) {
				$image = array(
								'item_id'=> $feed_id,
								'type' => 'about',
								'path' => $upload['file_name'],
								'width'=>$upload['image_width'],
								'height'=>$upload['image_height']
							);
				$this->image->save($image);
			}
		} else {
			$data['error'] = $upload_data['error'];
		}
		
		$data['about'] = $this->about->get_info($feed_id);
		
		$content['content'] = $this->load->view('abouts/add',$data,true);		
		
		$this->load_template($content);
	}
	
	
	function delete_image($feed_id,$image_id,$image_name)
	{
		if(!$this->session->userdata('is_city_admin')) {
		    $this->check_access('edit');
		}
		
		if ($this->image->delete($image_id)) {
			unlink('./uploads/'.$image_name);
			$this->session->set_flashdata('success','Image is successfully deleted.');
		} else {
			$this->session->set_flashdata('error','Database error occured.Please contact your system administrator.');
		}
		redirect(site_url('feeds/edit/'.$feed_id));
	}
}
?>