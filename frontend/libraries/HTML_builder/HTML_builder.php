<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author: Kenariosz
 * @date: 2015-11-16
 */
class HTML_builder extends CI_Driver_Library {

	/**
	 * Valid drivers
	 *
	 * @var array
	 */
	public $valid_drivers   = array("Head","Footer");
	/**
	 * Have to load these classes
	 *
	 * @var array
	 */
	public $abstract_classes= array("Singleton.php","View_helper.php","Script_helper.php","Link_helper.php","Head_script.php","Head_link.php","Footer_script.php","Footer_link.php",);
	/**
	 * CI object
	 *
	 * @var CI_Controller
	 */
	public $CI;

    public function __construct()
    {
	    $this->CI =& get_instance();
	    //Load
	    $this->load_abstract_model();
	    //Log
	    log_message('info', 'HTML_Builder driver Initialized');
	}

	/**
	 * Load all php files from abstract directory
	 */
	private function load_abstract_model()
	{
		foreach($this->abstract_classes as $abstract_class)
		{
			require_once APPPATH.'libraries/HTML_builder/abstract/'.$abstract_class;
		}
	}
}