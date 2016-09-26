<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!class_exists('KE_LANGUAGE_helper'))
{
	/**
	 * KE_LANGUAGE helper static class
	 *
	 * @author: Kenariosz
	 * @date: 2016-06-09
	 */
	class KE_LANGUAGE_helper{

		private function __construct(){}

		/**
		 * Lang
		 *
		 * Fetches a language variable and optionally outputs a form label.
		 * Add an optional parameter, to manage return value. If message has value and line not found, return message value.
		 *
		 * @param	string	$line		The language line
		 * @param	mixed	$message	Optional text. If $line was not found, $message will be use.
		 * @param	string	$for		The "for" value (id of the form element)
		 * @param	array	$attributes	Any additional HTML attributes
		 *
		 * @return	string
		 */
		public static function lang($line, $message = FALSE, $for = '', $attributes = array())
		{
			$line = get_instance()->lang->line($line, FALSE, $message);

			if ($for !== '')
			{
				$line = '<label for="'.$for.'"'._stringify_attributes($attributes).'>'.$line.'</label>';
			}

			return $line;
		}
	}
}