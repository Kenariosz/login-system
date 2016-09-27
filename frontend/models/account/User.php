<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author: Kenariosz
 * @date: 2016-09-18
 */
class User extends KE_Model {

	/**
	 * Activation code
	 *
	 * @var string
	 */
	public $activation_code;

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
    * User constructor
    */
    public function __construct()
    {
        parent::__construct();
	    // Set validation rules
	    $this->set_rules(
		    array(
			    'last_name'         => array('field'=>'last_name','label'=>'Vezetéknév','rules'=>'trim|required|min_length[3]|max_length[128]|xss_clean'),
			    'first_name'        => array('field'=>'first_name','label'=>'Keresztnév','rules'=>'trim|required|min_length[3]|max_length[128]|xss_clean'),
			    'email'             => array('field'=>'email','label'=>'Email cím','rules'=>'trim|required|valid_email|min_length[8]|max_length[32]|is_unique['.$this->get_table_name().'.email]|xss_clean'),
			    'password'          => array('field'=>'password','label'=>'Jelszó','rules'=>'required|max_length[32]|matches[password_confirm]|xss_clean|strong_password'),
			    'password_confirm'  => array('field'=>'password_confirm','label'=>'Jelszó újra','rules'=>'required'),
		    ),
		    'insert');

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
	 * Register
	 *
	 * @param       $email
	 * @param       $password
	 * @param array $additional_data
	 *
	 * @return bool|mixed
	 */
	public function register($email, $password, $additional_data = array())
	{
		$manual_activation  = $this->config->item('auth_manual_activation');

		if ($this->identity_check($email))
		{
			$this->set_error('account_creation_duplicate_email');
			return  FALSE;
		}
		// IP Address
		$ip_address = $this->input->ip_address();
		$salt       = $this->store_salt ? $this->salt() : FALSE;
		$password   = $this->hash_password($password, $salt);
		// Users table.
		$data   = array(
			'password'  => $password,
			'email'     => $email,
			'ip_address'=> $ip_address,
			'created_on'=> time(),
			'active'    => ($manual_activation === FALSE ? 1 : 0)
		);

		if($this->store_salt)
		{
			$data['salt']   = $salt;
		}
		// filter out any data passed that doesnt have a matching column in the users table and merge the set user data and the additional data
		$user_data  = array_merge($this->filter_data($additional_data), $data);
		// Insert
		$this->insert($user_data);
		// Get last insert id
		$id = $this->insert_id();

		return  (isset($id) AND $id) ? $id : FALSE;
	}

	/**
	 * Activate
	 *
	 * @param   $id
	 * @param   $code
	 *
	 * @return  bool
	 */
	public function activate($id, $code)
	{
		// Get user
		$result = $this->get(array('id'=>$id,'activation_code' => $code));
		// Check result
		if(empty($result))
		{
			$this->set_error('activate_unsuccessful');
			return  FALSE;
		}

		$data   = array(
			'activation_code'   => NULL,
			'active'            => 1
		);

		$return = $this->where(array('id' => $id))->update($data) == 1;

		if($return)
		{
			$this->set_message('activate_successful');
		}
		else
		{
			$this->set_error('activate_unsuccessful');
		}

		return $return;
	}

	/**
	 * Deactivate
	 *
	 * @param null $id
	 *
	 * @return bool
	 */
	public function deactivate($id = NULL)
	{
		if(!isset($id))
		{
			$this->set_error('deactivate_unsuccessful');
			return FALSE;
		}

		$activation_code       = sha1(md5(microtime()));
		$this->activation_code = $activation_code;

		$data   = array(
			'activation_code'   => $activation_code,
			'active'            => 0
		);

		$result = $this->where(array('id' => $id))->update($data);

		if($result)
		{
			$this->set_message('deactivate_successful');
		}
		else
		{
			$this->set_error('deactivate_unsuccessful');
		}

		return $result;
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
	 * Get activation code
	 *
	 * @return string
	 */
	public function get_activation_code()
	{
		return  $this->activation_code;
	}

	/**
	 * Identity check
	 *
	 * Check if identity is exists.
	 *
	 * @param string $identity
	 *
	 * @return bool
	 */
	private function identity_check($identity = '')
	{
		if(empty($identity))
		{
			return  FALSE;
		}

		return $this->where(array('email' => $identity))->count_all_results() > 0;
	}

	/**
	 * Filter data
	 *
	 * @param $data
	 *
	 * @return array
	 */
	private function filter_data($data)
	{
		$filtered_data  = array();
		$columns        = $this->list_fields();

		if(is_array($data))
		{
			foreach($columns as $column)
			{
				if(array_key_exists($column, $data))
				{
					$filtered_data[$column] = $data[$column];
				}
			}
		}

		return $filtered_data;
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

	/**
	 * Hash code
	 *
	 * Generates a random salt value for forgotten passwords or any other keys. Uses SHA1.
	 *
	 * @param $password
	 *
	 * @return bool|string
	 * @author Mathew
	 */
	private function hash_code($password)
	{
		return  $this->hash_password($password, FALSE, TRUE);
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
}