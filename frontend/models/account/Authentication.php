<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author: Kenariosz
 * @date: 2016-09-18
 */
class Authentication extends KE_Model {

	/**
	 * Messages
	 *
	 * @var string
	 */
	protected $messages;

	/**
	 * Message start delimeter
	 *
	 * @var null|string
	 */
	protected $message_start_delimiter;

	/**
	 * Message end delimeter
	 *
	 * @var null|string
	 */
	protected $message_end_delimiter;

	/**
	 * Errors
	 *
	 * Lang file code
	 *
	 * @var
	 */
	protected $errors   = array();

	/**
	 * Error start delimeter
	 *
	 * @var null|string
	 */
	protected $error_start_delimiter;

	/**
	 * Error end delimeter
	 *
	 * @var null|string
	 */
	protected $error_end_delimiter;
	
    /**
    * Authentication constructor
    */
    public function __construct()
    {
        parent::__construct();
	    // Load model
	    $this->load->model('account/Login_attempt');
	    // Set user table name
	    $this->set_table_name('users');
	    // Set validation rules
	    $rules  = array(
		    'email'     => array('field'=>'email','label'=>'Email cím','rules'=>'required'),
		    'password'  => array('field'=>'password','label'=>'Jelszó','rules'=>'required'),
	    );
	    if($this->session->userdata('has_captcha_validation'))
	    {
	    	$rules['captcha']   = array('field'=>'captcha','label'=>'Captcha','rules'=>'required|captcha');
	    }
	    $this->set_rules($rules,'login');
	    //initialize data
	    $this->store_salt       = $this->config->item('auth_store_salt');
	    $this->salt_length      = $this->config->item('auth_salt_length');
	    // initialize hash method options (Bcrypt)
	    $this->hash_method      = $this->config->item('hash_method');
	    $this->default_rounds   = $this->config->item('default_rounds');
	    $this->random_rounds    = $this->config->item('random_rounds');
	    $this->min_rounds       = $this->config->item('min_rounds');
	    $this->max_rounds       = $this->config->item('max_rounds');
	    // use delimiters from config
	    $this->message_start_delimiter = $this->config->item('message_start_delimiter');
	    $this->message_end_delimiter   = $this->config->item('message_end_delimiter');
	    $this->error_start_delimiter   = $this->config->item('error_start_delimiter');
	    $this->error_end_delimiter     = $this->config->item('error_end_delimiter');
    }

	/**
	 * Logged in
	 *
	 * @return bool
	 */
	public function logged_in()
	{
		return (bool) $this->session->userdata('user_id');
	}

	/**
	 * Logout
	 *
	 * @return bool
	 */
	public function logout()
	{
		// Save captcha status
		if($this->session->userdata('has_captcha_validation'))
		{
			$has_captcha    = TRUE;
		}
		else
		{
			$has_captcha    = FALSE;
		}
		$this->session->unset_userdata( array('email', 'id', 'user_id') );
		// delete cookies if they exist
		if(get_cookie($this->config->item('auth_identity_cookie')))
		{
			delete_cookie($this->config->item('auth_identity_cookie'));
		}
		if(get_cookie($this->config->item('auth_remember_cookie')))
		{
			delete_cookie($this->config->item('auth_remember_cookie'));
		}
		// Destroy the session
		$this->session->sess_destroy();
		//Recreate the session
		if(version_compare(PHP_VERSION, '7.0.0') >= 0)
		{
			session_start();
		}
		$this->session->sess_regenerate(TRUE);
		$this->session->set_userdata('has_captcha_validation', $has_captcha);
		$this->set_message('logout_successful');

		return  TRUE;
	}

	/**
	 * Login
	 *
	 * @param      $email
	 * @param      $password
	 * @param bool $remember
	 *
	 * @return bool
	 */
	public function login($email, $password, $remember=FALSE)
	{
		if(empty($email) || empty($password))
		{
			$this->set_error('login_unsuccessful');
			return  FALSE;
		}
		// Check attempts count
		if($this->Login_attempt->is_time_to_captcha($email))
		{
			$this->session->set_userdata('has_captcha_validation', TRUE);
		}
		else
		{
			$this->session->set_userdata('has_captcha_validation', FALSE);
		}

		$user   = $this->fields('email, id, last_name, first_name, password, active, last_login')->where(array('email' => $email))->get();

		if(!empty($user))
		{
			$password   = $this->hash_password_db($user->id, $password);

			if($password === TRUE)
			{
				if($user->active == 0)
				{
					$this->set_error('login_unsuccessful_not_active');

					return  FALSE;
				}
				// One login
				if($user->last_login !== NULL && $this->config->item('auth_one_login_only'))
				{
					$this->set_error('login_no_more');

					return  FALSE;
				}
				// Set session
				$this->set_session($user);
				// Update user last login
				$this->where(array('id' => $user->id))->update(array('last_login' => time()));
				// Set remember cookie if true
				if($remember && $this->config->item('auth_remember_users'))
				{
					$this->remember_user($user->id);
				}
				// Set msg
				$this->set_message('login_successful');

				return  TRUE;
			}
		}
		// Increase faild login.
		$this->Login_attempt->increase_login_attempts($email,'user');
		$this->Login_attempt->increase_login_attempts($email,'ip_address');
		if(!$this->session->userdata('has_captcha_validation'))
		{
			$this->Login_attempt->increase_login_attempts($email,'ip_24');
			$this->Login_attempt->increase_login_attempts($email,'ip_16');
		}
		// Set error msg
		$this->set_error('login_unsuccessful');

		return  FALSE;
	}

	/**
	 * Get captcha
	 *
	 * Generate a captcha and save in DB.
	 *
	 * @return string
	 */
	public function get_captcha()
	{
		// Load helper
		$this->load->helper('captcha');
		// Init
		$init   = array(
			'img_path'      => 'assets/uploads/captcha/',
			'img_url'       => site_url('captcha'),
		);
		$captcha    = create_captcha($init);
		// DB data
		$data       = array(
			'captcha_time'  => $captcha['time'],
			'ip_address'    => $this->input->ip_address(),
			'word'          => $captcha['word'],
		);
		// Set captcha table name
		$this->set_table_name('captcha');
		$insert_id  = $this->insert($data);
		// Set user table name
		$this->set_table_name('users');

		return  $captcha;
	}

	/**
	 * Remember user
	 *
	 * Set cookie to remember the users
	 *
	 * @param $id
	 *
	 * @return bool
	 */
	public function remember_user($id)
	{
		if(!$id)
		{
			return  FALSE;
		}

		$user   = $this->get(array('id'=>$id));
		$salt   = $this->salt();

		$return = $this->where( array('id' => $id))->update(array('remember_code' => $salt));

		if($return)
		{
			// if the user_expire is set to zero we'll set the expiration two years from now.
			if($this->config->item('auth_user_expire') === 0)
			{
				$expire = (60*60*24*365*2);
			}
			else
			{
				$expire = $this->config->item('auth_user_expire');
			}

			set_cookie(array(
				'name'  => $this->config->item('auth_identity_cookie'),
				'value' => $user->{$this->identity_column},
				'expire'=> $expire
			));

			set_cookie(array(
				'name'  => $this->config->item('auth_remember_cookie'),
				'value' => $salt,
				'expire'=> $expire
			));

			return  TRUE;
		}

		return  FALSE;
	}

	/**
	 * Set session
	 *
	 * @param $user
	 *
	 * @return bool
	 */
	public function set_session($user)
	{
		$session_data = array(
			'name'          => $user->last_name.' '.$user->first_name,
			'email'         => $user->email,
			'user_id'       => $user->id,
			'old_last_login'=> $user->last_login
		);

		$this->session->set_userdata($session_data);

		return  TRUE;
	}

	/**
	 * Get messages
	 *
	 * @return string
	 */
	public function get_messages()
	{
		$_output    = '';
		foreach ($this->messages as $message)
		{
			$translated = $this->lang->line($message) ? $this->lang->line($message) : '##' . $message . '##';
			$_output    .= $this->message_start_delimiter . $translated . $this->message_end_delimiter;
		}

		return $_output;
	}

	/**
	 * Set message
	 *
	 * @param $message
	 */
	public function set_message($message)
	{
		$this->messages[]   = $message;
	}

	/**
	 * Reset messages
	 */
	public function reset_messages()
	{
		$this->messages = array();
	}

	/**
	 * Get errors
	 *
	 * @return string
	 */
	public function get_errors()
	{
		$_output    = '';
		foreach($this->errors as $error)
		{
			$translated = $this->lang->line($error) ? $this->lang->line($error) : '##' . $error . '##';
			$_output    .= $this->error_start_delimiter . $translated . $this->error_end_delimiter;
		}

		return $_output;
	}

	/**
	 * Set error
	 *
	 * Set an error message
	 *
	 * @param $error
	 */
	public function set_error($error)
	{
		$this->errors[] = $error;
	}

	/**
	 * Reset errors
	 */
	public function reset_errors()
	{
		$this->errors   = array();
	}

	/**
	 * Hash password db
	 *
	 * This function takes a password and validates it against an entry in the users table.
	 *
	 * @param      $id
	 * @param      $password
	 * @param bool $use_sha1_override
	 *
	 * @return bool
	 */
	public function hash_password_db($id, $password, $use_sha1_override=FALSE)
	{
		if(empty($id) || empty($password))
		{
			return  FALSE;
		}

		$hash_password_db   = $this->fields('password, salt')->where(array('id' => $id))->get();

		if(empty($hash_password_db))
		{
			return  FALSE;
		}
		// bcrypt
		if($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
		{
			if($this->bcrypt->verify($password,$hash_password_db->password))
			{
				return  TRUE;
			}

			return  FALSE;
		}
		// sha1
		if($this->store_salt)
		{
			$db_password    = sha1($password . $hash_password_db->salt);
		}
		else
		{
			$salt       = substr($hash_password_db->password, 0, $this->salt_length);
			$db_password= $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}

		if($db_password == $hash_password_db->password)
		{
			return  TRUE;
		}
		else
		{
			return  FALSE;
		}
	}

	/**
	 * Generates a random salt value.
	 *
	 * Salt generation code taken from https://github.com/ircmaxell/password_compat/blob/master/lib/password.php
	 *
	 * @return void
	 * @author Anthony Ferrera
	 **/
	private function salt()
	{

		$raw_salt_len   = 16;

		$buffer         = '';
		$buffer_valid   = FALSE;

		if(function_exists('mcrypt_create_iv') && !defined('PHALANGER'))
		{
			$buffer = mcrypt_create_iv($raw_salt_len, MCRYPT_DEV_URANDOM);
			if($buffer)
			{
				$buffer_valid   = TRUE;
			}
		}

		if(!$buffer_valid && function_exists('openssl_random_pseudo_bytes'))
		{
			$buffer = openssl_random_pseudo_bytes($raw_salt_len);
			if($buffer)
			{
				$buffer_valid   = TRUE;
			}
		}

		if(!$buffer_valid && @is_readable('/dev/urandom'))
		{
			$f      = fopen('/dev/urandom', 'r');
			$read   = strlen($buffer);
			while($read < $raw_salt_len)
			{
				$buffer .= fread($f, $raw_salt_len - $read);
				$read   = strlen($buffer);
			}
			fclose($f);
			if($read >= $raw_salt_len)
			{
				$buffer_valid = TRUE;
			}
		}

		if(!$buffer_valid || strlen($buffer) < $raw_salt_len)
		{
			$bl = strlen($buffer);
			for($i = 0; $i < $raw_salt_len; $i++)
			{
				if($i < $bl)
				{
					$buffer[$i] = $buffer[$i] ^ chr(mt_rand(0, 255));
				}
				else
				{
					$buffer .= chr(mt_rand(0, 255));
				}
			}
		}

		$salt   = $buffer;
		// encode string with the Base64 variant used by crypt
		$base64_digits  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
		$bcrypt64_digits= './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$base64_string  = base64_encode($salt);
		$salt           = strtr(rtrim($base64_string, '='), $base64_digits, $bcrypt64_digits);

		$salt           = substr($salt, 0, $this->salt_length);

		return $salt;
	}

	/**
	 * Hash password
	 *
	 * Hashes the password to be stored in the database.
	 *
	 * @param      $password
	 * @param bool $salt
	 * @param bool $use_sha1_override
	 *
	 * @return bool|string
	 * @author Mathew
	 */
	private function hash_password($password, $salt=FALSE, $use_sha1_override=FALSE)
	{
		if(empty($password))
		{
			return  FALSE;
		}

		// bcrypt
		if($use_sha1_override === FALSE && $this->hash_method == 'bcrypt')
		{
			return  $this->bcrypt->hash($password);
		}


		if($this->store_salt && $salt)
		{
			return  sha1($password . $salt);
		}
		else
		{
			$salt   = $this->salt();
			return  $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}
	}
}