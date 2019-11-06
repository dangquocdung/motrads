<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modify the price format such as decimal place, round
 *
 * @param      <type>  $price  The price
 */
if ( !function_exists( 'price_format' )) 
{
	function price_format( $price )
	{
		// get ci instance
		$CI =& get_instance();

		$about = $CI->about->get_info(1);
		$decimal_place = $about->price_decimal_place;

		return number_format( round( $price, $decimal_place ), $decimal_place );
	}
}