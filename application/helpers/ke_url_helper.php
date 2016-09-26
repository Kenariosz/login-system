<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!class_exists('KE_URL_helper'))
{
	/**
	 * KE_URL helper static class
	 *
	 * @author: Kenariosz
	 * @date: 2016-06-09
	 */
	class KE_URL_helper{

		private function __construct(){}

		/**
		 * Get image path
		 *
		 * Return an image url depends on the active template directory.
		 *
		 * @param   string  $image_name     Image name
		 *
		 * @return  string                  Full url. For example: http://codeigniter-lang.hu/assets/default/images/logo.png
		 */
		public static function get_image_path($image_name='no-img.png')
		{
			return  get_instance()->config->base_url('assets'.DIRECTORY_SEPARATOR.get_instance()->config->item('template_dir').DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$image_name);
		}

		/**
		 * Get upload path
		 *
		 * Return an URL which refer to uploads directory.
		 *
		 * @param   string  $data_name      File name
		 *
		 * @return  string                  Full url. For example: http://codeigniter-lang.hu/assets/uploads/logo.png
		 */
		public static function get_upload_path($data_name='')
		{
			return  get_instance()->config->base_url('assets'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.$data_name);
		}

		/**
		 * Get script path
		 *
		 * Return script URL depends on the active template directory.
		 *
		 * @param   string  $script_name    Script name
		 *
		 * @return  string                  Full url. For example: http://codeigniter-lang.hu/assets/default/js/script.js
		 */
		public static function get_script_path($script_name)
		{
			return  get_instance()->config->base_url('assets'.DIRECTORY_SEPARATOR.get_instance()->config->item('template_dir').DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.$script_name);
		}

		/**
		 * Get style path
		 *
		 * Return style URL depends on the active template directory.
		 *
		 * @param   string  $style_name     Style name
		 *
		 * @return  string                  Full url. For example: http://codeigniter-lang.hu/assets/default/css/style.css
		 */
		public static function get_style_path($style_name)
		{
			return  get_instance()->config->base_url('assets'.DIRECTORY_SEPARATOR.get_instance()->config->item('template_dir').DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.$style_name);
		}
	}
}