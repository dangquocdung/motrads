<?php 
require_once(APPPATH.'/libraries/REST_Controller.php');

class Abouts extends REST_Controller
{
	function __construct()
	{
		parent::__construct();	
	}
	
	function index_get()
	{	
		
		$abouts = array();
		$abouts = $this->about->get_all()->result();
		
		$data = array();
		
		foreach ($abouts as $about) {
			$this->get_about_images($about);
			$data[] = $about;
		}
		
		$this->response(array(
			'status'=>'success',
			'data'	=>$data)
		);
		
	}
	
	function get_about_images(&$about)
	{
		//$about->added = $this->ago($about->added);
		$about->images = $this->image->get_all_by_type($about->id, 'about')->result();
	}
	
	function ago($time)
	{
		$time = mysql_to_unix($time);
		$now = mysql_to_unix($this->category->get_now());
		
	   $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	   $lengths = array("60","60","24","7","4.35","12","10");
	
	   $difference = $now - $time;
	  	$tense = "ago";
	
	   for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
	       $difference /= $lengths[$j];
	   }
	
	   $difference = round($difference);
	
	   if ($difference != 1) {
	       $periods[$j].= "s";
	   }
	
	   if ($difference==0) {
	   		return "Just Now";
	   } else {
	   		return "$difference $periods[$j] ago";
	   }
	}
}
?>