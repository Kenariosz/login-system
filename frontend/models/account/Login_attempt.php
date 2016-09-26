<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author: Kenariosz
 * @date: 2016-09-18
 */
class Login_attempt extends KE_Model {
	
    /**
    * Authentication constructor
    */
    public function __construct()
    {
        parent::__construct();
	    // Init
	    // Check attempts count
	    if($this->is_time_to_captcha(''))
	    {
		    $this->session->set_userdata('has_captcha_validation', TRUE);
	    }
	    else
	    {
		    $this->session->set_userdata('has_captcha_validation', FALSE);
	    }
	    // Summarise
	    $this->summarise_faild_logins();
	    // Delete old login attempts
	    //$this->clear_login_attempts();
    }

	/**
	 * Increase login attempts
	 *
	 * @param   string  $email
	 * @param   string  $type       user|ip_address|ip_16|ip_24
	 *
	 * @return  bool
	 */
	public function increase_login_attempts($email,$type)
	{
		if($this->config->item('auth_track_login_attempts'))
		{
			$ip_address = $this->input->ip_address();
			$ip_16      = $this->cidr_converter($this->input->ip_address().'/16');
			$ip_24      = $this->cidr_converter($this->input->ip_address().'/24');
			$return     = $this->insert(array('ip_address' => $ip_address, 'ip_16' => $ip_16, 'ip_24' => $ip_24, 'login' => $email, 'time' => time(), 'type' => $type));

			return  $return;
		}

		return  FALSE;
	}

	/**
	 * Is time to captcha
	 *
	 * Validate login attempts
	 *
	 * @param   string  $email
	 *
	 * @return  bool
	 */
    public function is_time_to_captcha($email)
    {
	    if($this->is_captcha('login',$email,'user'))
	    {
	    	return  TRUE;
	    }
	    elseif($this->is_captcha('ip_address',$this->input->ip_address(),'ip_address'))
	    {
	    	return  TRUE;
	    }
	    elseif($this->is_captcha('ip_24',$this->cidr_converter($this->input->ip_address().'/24'),'ip_24','auth_max_login_attempts_ip_24'))
	    {
	    	return  TRUE;
	    }
	    elseif($this->is_captcha('ip_16',$this->cidr_converter($this->input->ip_address().'/16'),'ip_16','auth_max_login_attempts_ip_16'))
	    {
	    	return  TRUE;
	    }
	    else
	    {
	    	return FALSE;
	    }
    }

	/**
	 * Is captcha
	 *
	 * @param   string  $column
	 * @param   string  $value
	 * @param   string  $type
	 * @param   string  $config_key
	 *
	 * @return  bool
	 */
	private function is_captcha($column,$value,$type,$config_key = 'auth_max_login_attempts')
	{
		return  $this->is_max_login_attempts_exceeded($column,$value,$type,$config_key) && $this->get_last_attempt_time($column,$value) > time() - $this->config->item('auth_captcha_time');
	}

	/**
	 * Get last attempt time
	 *
	 * @param   string  $column     Table column' name
	 * @param   string  $value      Table column' value
	 *
	 * @return  int
	 */
	private function get_last_attempt_time($column, $value)
	{
		if($this->config->item('auth_track_login_attempts'))
		{
			$result = $this->fields('time')->order_by('id', 'desc')->get(array($column => $value));

			if($result)
			{
				return  $result->time;;
			}
		}

		return 0;
	}

	/**
	 * Is max login attempts exceeded
	 * s
	 * @param   string  $column         Table column' name
	 * @param   string  $value          Table column' value
	 * @param   string  $type           Type
	 * @param   string  $config_key     Config array key' name
	 *
	 * @return  bool
	 */
	private function is_max_login_attempts_exceeded($column,$value,$type,$config_key = 'auth_max_login_attempts')
	{
		if($this->config->item('auth_track_login_attempts'))
		{
			$max_attempts   = $this->config->item($config_key);
			if($max_attempts > 0)
			{
				$result = $this->count_all_results(array($column => $value, 'type' => $type));

				return  $result >= $max_attempts;
			}
		}
		return FALSE;
	}

	/**
	 * Cidr converter
	 *
	 * @param $ip       IP to check in IPV4 format eg. 127.0.0.1
	 *
	 * @return string
	 */
	private function cidr_converter($ip)
	{
		$start  = strtok($ip,"/");
		$n      = 3 - substr_count($ip, ".");
		if($n > 0)
		{
			for($i = $n;$i > 0; $i--)
			{
				$start .= ".0";
			}
		}
		$bits1  = str_pad(decbin(ip2long($start)), 32, "0", STR_PAD_LEFT);
		$ip     = (1 << (32 - substr(strstr($ip, "/"), 1))) - 1;
		$bits2  = str_pad(decbin($ip), 32, "0", STR_PAD_LEFT);
		$final  = "";
		for($i = 0; $i < 32; $i++)
		{
			if ($bits1[$i] == $bits2[$i]) $final .= $bits1[$i];
			if ($bits1[$i] == 1 and $bits2[$i] == 0) $final .= $bits1[$i];
			if ($bits1[$i] == 0 and $bits2[$i] == 1) $final .= $bits2[$i];
		}

		return  long2ip(bindec($final));
	}

	/**
	 * Ip in range
	 *
	 * @param $ip       IP to check in IPV4 format eg. 127.0.0.1
	 * @param $range    IP/CIDR netmask eg. 127.0.0.0/24, also 127.0.0.1 is accepted and /32 assumed
	 *
	 * @return bool
	 */
	private function ip_in_range($ip, $range)
	{
		if(strpos( $range, '/' ) == FALSE)
		{
			$range .= '/32';
		}
		// $range is in IP/CIDR format eg 127.0.0.1/24
		list( $range, $netmask ) = explode( '/', $range, 2 );
		$range_decimal      = ip2long( $range );
		$ip_decimal         = ip2long( $ip );
		$wildcard_decimal   = pow( 2, ( 32 - $netmask ) ) - 1;
		$netmask_decimal    = ~ $wildcard_decimal;

		return  ( ( $ip_decimal & $netmask_decimal ) == ( $range_decimal & $netmask_decimal ) );
	}

	/**
	 * Clear login attempts
	 *
	 * @return bool
	 */
	private function clear_login_attempts()
	{
		if($this->config->item('auth_track_login_attempts'))
		{
			return  $this->where(array('time <' => time() - $this->config->item('auth_captcha_time')))->delete();
		}
		return FALSE;
	}

	/**
	 * Summarise faild login
	 *
	 * @return bool
	 */
	private function summarise_faild_logins()
	{
		$data   = array(
			'all'       => $this->count_all_results(),
			'user'      => $this->where(array('type'=>'user'))->count_all_results(),
			'ip_address'=> $this->where(array('type'=>'ip_address'))->count_all_results(),
			'ip_16'     => $this->where(array('type'=>'ip_16'))->count_all_results(),
			'ip_24'     => $this->where(array('type'=>'ip_24'))->count_all_results(),
			'time'      => time(),
		);
		// Set summarise table
		$this->set_table_name('summarised_login_attempts');

		$result = $this->fields('time')->order_by('time','DESC')->get();

		if( (time() - $result->time) > 300 )
		{
			if($this->insert($data))
			{
				// Set attempts table table
				$this->set_table_name('login_attempts');

				return  TRUE;
			}
		}
		// Set attempts table table
		$this->set_table_name('login_attempts');

		return  FALSE;
	}
}