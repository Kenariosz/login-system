<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author: Kenariosz
 * @date: 2016-09-01
 */
class Home extends KE_Controller{

    public function __construct()
    {
	    parent::__construct();
	    // Set styles
	    $styles = array(
		    array('file'=>'bootstrap.min.css','action'=>'append'),
		    array('file'=>'animate.min.css','action'=>'append'),
		    array('file'=>'style.css','action'=>'append'),
	    );
	    $this->html_builder->Head->set_style($styles);
	    // Set scripts
	    $scripts = array(
		    array('file'=>'jquery-1.11.3.min.js','action'=>'append'),
		    array('file'=>'tether.min.js','action'=>'append'),
		    array('file'=>'bootstrap.min.js','action'=>'append'),
		    array('file'=>'bootstrap.offcanvas.js','action'=>'append'),
		    array('file'=>'http://html5shiv.googlecode.com/svn/trunk/html5.js','action'=>'append','external'=>TRUE,'conditional_style_sheets'=>array('start'=>'<!--[if lt IE 9]>','end'=>'<![endif]-->')),
		    array('file'=>'https://oss.maxcdn.com/respond/1.4.2/respond.min.js','action'=>'append','external'=>TRUE,'conditional_style_sheets'=>array('start'=>'<!--[if lt IE 9]>','end'=>'<![endif]-->')),
	    );
	    $this->html_builder->Footer->set_script($scripts);
	}
	
	public function index()
	{
		if(!$this->authentication_lib->logged_in())
		{
			redirect('login', 'refresh');
		}
		else
		{
			// Set page data
			$this->set_page_title('Home');
			$this->set_page_id('home');
			// Render
			$this->render('common/home');
		}
    }
}