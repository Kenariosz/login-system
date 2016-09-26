<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author: Kenariosz
 * @date: 2016-09-17
 */
class Authentication_lib{

	/**
	 * __construct
	 */
	public function __construct()
	{
		// Loads
		$this->lang->load('authentication');
		// Init email
		$email_config   = $this->config->item('email_config');
		if(isset($email_config) && is_array($email_config))
		{
			$this->email->initialize($email_config);
		}
	}

	/**
	 * Register
	 *
	 * @param       $email
	 * @param       $password
	 * @param array $additional_data
	 *
	 * @return bool
	 */
	public function register($email, $password, $additional_data = array())
	{
		$id = $this->User->register($email, $password, $additional_data);

		if($this->config->item('auth_email_activation'))
		{
			if(!$id)
			{
				$this->set_error('account_creation_unsuccessful');
				return  FALSE;
			}
			// Deactivate the user -> activation flow
			$deactivate = $this->User->deactivate($id);
			// Reset message
			$this->User->reset_messages();

			if(!$deactivate)
			{
				$this->set_error('deactivate_unsuccessful');
				return  FALSE;
			}

			$activation_code    = $this->User->get_activation_code();
			$user               = $this->User->get(array('id'=>$id));

			$data = array(
				'id'        => $user->id,
				'name'      => $user->last_name.' '.$user->first_name,
				'email'     => $email,
				'activation'=> $activation_code,
			);
			// Set email message with template
			$message    = $this->load->view($this->config->item('email_templates').$this->config->item('email_activate'), $data, TRUE);
			// Init settings
			$this->email->clear();
			$this->email->from($this->config->item('email_no_replay'), $this->config->item('email_from_name'));
			$this->email->to($email);
			$this->email->subject($this->lang->line('email_activation_subject'));
			$this->email->message($message);
			// Send email
			if($this->email->send() == TRUE)
			{
				$this->set_message('activation_email_successful');
				return  $id;
			}

			$this->set_error('activation_email_unsuccessful');
			return  FALSE;
		}
		else
		{
			if($id !== FALSE)
			{
				$this->set_message('account_creation_successful');
				return  $id;
			}
			else
			{
				$this->set_error('account_creation_unsuccessful');
				return  FALSE;
			}
		}
	}

	/**
	 * __call
	 *
	 * Call models methods without loads of unnecessary aliases
	 *
	 * @param $method
	 * @param $arguments
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function __call($method, $arguments)
	{
		if(method_exists($this->Authentication, $method))
		{
			return  call_user_func_array( array($this->Authentication, $method), $arguments);
		}
		elseif(method_exists($this->User, $method))
		{
			return  call_user_func_array( array($this->User, $method), $arguments);
		}
		else
		{
			throw new Exception('Undefined method Authentication::' . $method . '() and User::' . $method . '() called');
		}
	}

	/**
	 * __get
	 *
	 * Enables the use of CI super-global without having to define an extra variable.
	 * I can't remember where I first saw this, so thank you if you are the original author. -Militis, Ben Edmunds
	 *
	 * @param $var
	 *
	 * @return mixed
	 */
	public function __get($var)
	{
		return  get_instance()->$var;
	}
}
