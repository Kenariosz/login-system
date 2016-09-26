<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * KE_Loader class
 *
 * Add template management.
 *
 * @author: Kenariosz
 * @date: 2016-06-09
 */
class KE_Loader extends CI_Loader{

	/**
	 * List of paths to load views from
	 *
	 * @var	array
	 */
	protected $_ci_view_paths;

    public function __construct()
    {
		parent::__construct();
	    $this->set_ci_view_paths();
	}

	/**
	 * Set ci view paths
	 *
	 * Set view path with template directory.
	 *
	 * TODO: Syn with database settings. Use settings config at the moment.
	 */
	public function set_ci_view_paths()
	{
		$CI=& get_instance();
		$CI->config->load('settings');
		$this->_ci_view_paths   = array(VIEWPATH.$CI->config->item('template_dir').DIRECTORY_SEPARATOR	=> TRUE);
	}
}