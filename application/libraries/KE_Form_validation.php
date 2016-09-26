<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author: Kenariosz
 * @date: 2016-09-17
 */
class KE_Form_validation extends CI_Form_validation {


	public function __construct($rules = array())
	{
		parent::__construct();

		log_message('info', 'KE Form Validation Class Initialized');
	}

	/**
	 * Strong password
	 *
	 * @param $password
	 *
	 * @return bool
	 */
	public function strong_password($password)
	{
		if(empty($password))
		{
			return TRUE;
		}
		// The regex looks ahead for at least one lowercase letter, one uppercase letter and a number.
		return (bool) preg_match("#.*^(?=.{8,32})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$#", $password);
	}

	/**
	 * Captcha
	 *
	 * @param $string
	 *
	 * @return bool
	 */
	public function captcha($string)
	{
		// First, delete old captchas
		$expiration = time() - 1800; // Half hour limit
		$this->db->where('captcha_time < ', $expiration)->delete('ke_captcha');
		// Then see if a captcha exists:
		$sql    = 'SELECT COUNT(*) AS count FROM ke_captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?';
		$binds  = array($string, $this->input->ip_address(), $expiration);
		$query  = $this->db->query($sql, $binds);
		$row    = $query->row();

		return (bool) $row->count == 1;
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
