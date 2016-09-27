<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author: Kenariosz
 * @date: 2016-09-17
 */
class Users extends KE_Controller{

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

	/**
	 * Users::index
	 */
	public function index()
	{
		redirect('login');
    }

	/**
	 * Users::login
	 *
	 * User login page.
	 */
	public function login()
	{
		if($this->authentication_lib->logged_in())
		{
			redirect('home');
		}
		$this->data['captcha']  = FALSE;
		// Check captcha
		if($this->session->userdata('has_captcha_validation'))
		{
			$this->data['captcha']  = $this->Authentication->get_captcha();
		}
		// Set page data
		$this->set_page_title('Bejelentkezés');
		$this->set_page_id('login-page');
		// Loads
		$this->load->library(array('form_validation'));
		// Set page data
		$this->set_page_title('Regisztráció');
		$this->set_page_id('register-page');
		// Set validation rules
		$this->form_validation->set_rules($this->Authentication->get_rules('login'));
		// Run validation
		if($this->form_validation->run() == true)
		{
			// check for "remember me"
			$remember   = (bool) $this->input->post('remember');

			if($this->authentication_lib->login($this->input->post('email'), $this->input->post('password'), $remember))
			{
				$this->session->set_flashdata('message', $this->authentication_lib->get_messages());
				redirect('home');
			}
			else
			{
				$this->session->set_flashdata('message', $this->authentication_lib->get_errors());
				redirect('login');
			}
		}
		else
		{
			// Set the error message if there is one
			$this->data['message']  = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			// Render page
			$this->render('sites/user/login');
		}
    }

	/**
	 * Users::logout
	 *
	 * Logout page.
	 */
	public function logout()
	{
		// Set title
		$this->set_page_title('Kijelentkezés');
		// Logout
		$this->authentication_lib->logout();
		// Redirect login
		$this->session->set_flashdata('message', $this->Authentication->get_messages());
		redirect('login');
    }

	/**
	 * Users::registration
	 *
	 * User registration page.
	 */
	public function registration()
	{
		if($this->authentication_lib->logged_in())
		{
			redirect('home');
		}
		$this->data['has_submit']   = FALSE;
		if(!empty($_POST))
		{
			$this->data['has_submit']   = TRUE;
		}
		// Loads
		$this->load->library(array('form_validation'));
		// Set page data
		$this->set_page_title('Regisztráció');
		$this->set_page_id('register-page');
		// Set validation rules
		$this->form_validation->set_rules($this->User->get_rules('insert'));
		// Validation
		if($this->form_validation->run() === TRUE)
		{
			$email      = strtolower($this->input->post('email'));
			$password   = $this->input->post('password');

			$additional_data = array(
				'first_name'=> $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'username'  => $this->input->post('last_name').' '.$this->input->post('first_name'),
			);
		}
		// Save registration
		if($this->form_validation->run() === TRUE && $this->authentication_lib->register($email, $password, $additional_data))
		{
			// Set Successful registration
			$this->session->set_flashdata('message', $this->authentication_lib->get_messages());
			redirect('login');
		}
		else
		{
			// Set the error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->authentication_lib->get_errors() ? $this->authentication_lib->get_errors() : $this->session->flashdata('message')));
		}
		// Render page
		$this->render('sites/user/registration');
    }

	/**
	 * Users::activate
	 *
	 * @param      $id
	 * @param bool $code
	 */
	public function activate($id, $code)
	{
		$activation = $this->authentication_lib->activate($id, $code);

		if($activation)
		{
			$this->session->set_flashdata('message', $this->authentication_lib->get_messages());
			redirect("login");
		}
		else
		{
			$this->session->set_flashdata('message', $this->authentication_lib->get_errors());
			redirect("registration");
		}
	}
}