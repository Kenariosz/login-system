<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!class_exists('KE_GENERAL_helper'))
{
	/**
	 * KE_GENERAL helper static class
	 *
	 * It contains some useful methods.
	 *
	 * @author: Kenariosz
	 * @date: 2016-06-09
	 */
	class KE_GENERAL_helper{

		private function __construct(){}
		
		/**
		 * Get numbers
		 *
		 * Generate numbers array.
		 * For example: Need years for birth between 1900-today:
		 *              KE_GENERAL_helper::get_numbers(1900, (date('Y') - 1900), 'none', TRUE);
		 *
		 * @param int       $start_number   Starting point
		 * @param int       $count_number   The number ($start_number) increases
		 * @param string    $default_value  The first value if it is not empty.
		 * @param bool      $rsort          Descending order.
		 *
		 * @return array
		 */
		public static function get_numbers($start_number, $count_number, $default_value = '', $rsort = FALSE)
		{
			$temp   = array();
			// Generate numbers
			for($i = $start_number; $i <= ($start_number + $count_number); $i++ )
			{
				$temp[$i]  = $i;
			}
			// Sort, if TRUE
			if($rsort)
			{
				rsort($temp);
			}

			return  (''==$default_value ? $temp : array_merge(array($default_value=>$default_value),$temp));
		}
	}
}