<?php defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('session.use_trans_sid', 1);
header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
/**
 * KE_Controller class
 *
 * Central controller class.
 *
 * @author: Kenariosz
 * @date: 2016-06-07
 */
class KE_Controller extends CI_Controller {

	protected $data = array(
		"keys"              => "",
		"description"       => "",
		"page_title"        => "",
		"page_id"           => "",
		"controller_name"   => "",
	);

	public function __construct()
	{
		parent::__construct();
		// Load settings
		$this->config->load_settings_from_db();
		// Languge
//		$this->lang->init();
		// Log
		log_message('info', 'KE_Controller Class Initialized');
	}

	/**
	 * Render views. Default: header.php + *.php + footer.php
	 *
	 * @param $view         file name in view directory (without ext).
	 * @param bool $header
	 * @param bool $footer
	 *
	 * @return void
	 */
	protected function render($view, $header = TRUE, $footer = TRUE)
	{
		if($header) $this->load->view('common/header', $this->data);
		$this->load->view($view, $this->data);
		if($footer) $this->load->view('common/footer' ,$this->data);
	}
	/**
	 * Render view to string.
	 *
	 * @param $view         file name in view directory (with ext).
	 * @param array $data
	 *
	 * @return string
	 */
	protected function render_to_string( $view, array $data )
	{
		return $this->load->view($view, $data, TRUE);
	}
	/**
	 * Set Page title in Head
	 *
	 * @param   $page_title
	 *
	 * @return	void
	 */
	protected function set_page_title($page_title)
	{
		$this->data['page_title']   = $page_title;
	}
	/**
	 * Set Page id
	 *
	 * @param   $page_id
	 *
	 * @return	void
	 */
	protected function set_page_id($page_id)
	{
		$this->data['page_id']  = $page_id;
	}
	/**
	 * Set Controller name
	 *
	 * @param   $name
	 *
	 * @return	void
	 */
	protected function set_controller_name($name)
	{
		$this->data['controller_name']  = $name;
	}
	/**
	 * Set metakeys
	 *
	 * @param $keys
	 */
	protected function set_meta_keys($keys)
	{
		$this->data['keys'] = $keys;
	}
	/**
	 * Set meta description
	 *
	 * @param $description
	 */
	protected function set_meta_description($description)
	{
		$this->data['description']  = $description;
	}
}