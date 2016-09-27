<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author: Kenariosz
 * @date: 2016-01-11
 */

class Data extends KE_Controller{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('file');
	}

	public function pdf($file_name)
	{
		if(!empty($file_name) && file_exists('assets/uploads/pdf/' . $file_name))
		{
			$this->output('assets/uploads/pdf/', $file_name, $file_name);
		}
	}

	public function captcha($file_name)
	{
		if(!empty($file_name) && file_exists('assets/uploads/captcha/' . $file_name))
		{
			$this->output('assets/uploads/captcha/', $file_name, $file_name);
		}
		exit;
	}

	private function output($path, $file, $name)
	{
		$filetype   = get_mime_by_extension($file);
		ob_start();
		ob_clean();
		header('Content-Description: File Transfer');
		header('Content-Type: '. $filetype);
		header('Content-Disposition: inline; filename='.$name);
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($path . $file));
		flush();
		ob_flush();
		readfile($path . $file);
		exit;
	}
}
