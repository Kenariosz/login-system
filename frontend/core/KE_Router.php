<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Test class at the moment
 *
 * @author: Kenariosz
 * @date: 2016-06-10
 */
class KE_Router extends CI_Router{

    public function __construct()
    {
		parent::__construct();

	    /*if(!in_array($this->uri->segment(1),$this->enabled_segments))
	    {
	    // TODO: Add $_SERVER protocol
			$this->redirect('http://'.$_SERVER['HTTP_HOST'].DIRECTORY_SEPARATOR.'hu'.DIRECTORY_SEPARATOR.$this->uri->uri_string(), NULL);
	    }*/
	}
}