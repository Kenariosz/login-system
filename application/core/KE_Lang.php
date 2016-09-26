<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * KE_Lang class
 *
 * @author: Kenariosz
 * @date: 2016-06-07
 */
class KE_Lang extends CI_Lang {

	/**
	 * CI main object
	 *
	 * @object
	 */
	private $CI;

	/**
	 * Deafult language dir
	 *
	 * @var string
	 */
	private $default_language_dir;

	/**
	 * Top level domain.
	 *
	 * Store the current url top level part. (hu,com,org...)
	 *
	 * @string
	 */
	private $top_level_domain;

	/**
	 * Exceptions array
	 *
	 * Store some top level domain. It should be treated by other means
	 *
	 * @string array
	 */
	private $exceptions     = array('dev','com');

	/**
	 * Necessary columns list. These will save in session.
	 *
	 * @var string
	 */
	private $language_data  = 'code, country_code, directory, internal_name';

	/**
	 * Selected language object
	 *
	 * @object
	 */
	private $language_object;

	/**
	 * KE_Lang constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Language initialization
	 */
	public function init()
	{
		// CI object init.
		$this->CI=& get_instance();
		// Load language model
		$this->CI->load->model('localisation/Language');
		// Sets
		$this->set_default_language_dir();
		$this->set_top_level_domain();
		$this->set_language();
		// Load helper
		$this->CI->load->helper('language');
		// Load language files
		$this->CI->lang->load('common/general', $this->CI->session->userdata('language')->directory);
		$this->CI->lang->load('common/menu', $this->CI->session->userdata('language')->directory);
		$this->CI->lang->load('common/uri', $this->CI->session->userdata('language')->directory);
		$this->CI->lang->load('components/quick_links', $this->CI->session->userdata('language')->directory);
		$this->CI->lang->load('components/facebook', $this->CI->session->userdata('language')->directory);
	}

	/**
	 * Get language object
	 *
	 * @return object
	 */
	public function get_language_object()
	{
		return  (object)$this->language_object;
	}

	/**
	 * Load a language file, with fallback to config|hungarian.
	 *
	 * @param	mixed	$langfile	Language file name
	 * @param	string	$idiom		Language name (english, etc.)
	 * @param	bool	$return		Whether to return the loaded array of translations
	 * @param 	bool	$add_suffix	Whether to add suffix to $langfile
	 * @param 	string	$alt_path	Alternative path to look for the language file
	 *
	 * @return	void|string[]	Array containing translations, if $return is set to TRUE
	 */
	public function load($langfile, $idiom = '', $return = FALSE, $add_suffix = TRUE, $alt_path = '')
	{
		if(is_array($langfile))
		{
			foreach($langfile as $value)
			{
				$this->load($value, $idiom, $return, $add_suffix, $alt_path);
			}

			return;
		}

		$langfile   = str_replace('.php', '', $langfile);

		if($add_suffix === TRUE)
		{
			$langfile   = preg_replace('/_lang$/', '', $langfile) . '_lang';
		}

		$langfile .= '.php';

		if(empty($idiom) OR ! preg_match('/^[a-z_-]+$/i', $idiom))
		{
			$config = & get_config();
			$idiom  = empty($config['language']) ? $this->get_default_language_dir() : $config['language'];
		}

		if($return === FALSE && isset($this->is_loaded[$langfile]) && $this->is_loaded[$langfile] === $idiom)
		{
			return;
		}

		// load the default language first, if necessary
		// only do this for the language files under Application/
		$basepath   = APPPATH . 'language/' . $this->get_default_language_dir() . '/' . $langfile;
		if(($found_default = file_exists($basepath)) === TRUE)
		{
			include($basepath);
		}
		// Load the base file, so any others found can override it
		$basepath   = BASEPATH . 'language/' . $idiom . '/' . $langfile;
		if(($found = file_exists($basepath)) === TRUE)
		{
			include($basepath);
		}

		// Do we have an alternative path to look in?
		if($alt_path !== '')
		{
			$alt_path .= 'language/' . $idiom . '/' . $langfile;
			if(file_exists($alt_path))
			{
				include($alt_path);
				$found  = TRUE;
			}
		}
		else
		{
			foreach(get_instance()->load->get_package_paths(TRUE) as $package_path)
			{
				$package_path .= 'language/' . $idiom . '/' . $langfile;
				if($basepath !== $package_path && file_exists($package_path))
				{
					include($package_path);
					$found  = TRUE;
					break;
				}
			}
		}

		if($found !== TRUE AND $found_default !== TRUE)
		{
			show_error('Unable to load the requested language file: language/' . $idiom . '/' . $langfile);
		}

		if(!isset($lang) OR ! is_array($lang))
		{
			log_message('error', 'Language file contains no data: language/' . $idiom . '/' . $langfile);

			if($return === TRUE)
			{
				return  array();
			}
			return;
		}

		if($return === TRUE)
		{
			return  $lang;
		}

		$this->is_loaded[$langfile] = $idiom;
		$this->language             = array_merge($this->language, $lang);

		log_message('info', 'Language file loaded: language/' . $idiom . '/' . $langfile);
		return  TRUE;
	}

	/**
	 * Language line
	 *
	 * Fetches a single line of text from the language array.
	 * Add an optional parameter, to manage return value. If message has value and line not found, return message.
	 *
	 * @param	string	$line		Language line key
	 * @param	bool	$log_errors	Whether to log an error message if the line is not found
	 * @param   mixed   $message    Return message
	 *
	 * @return	string	Translation
	 */
	public function line($line, $log_errors = TRUE, $message = FALSE)
	{
		$value  = isset($this->language[$line]) ? $this->language[$line] : FALSE;

		// Because killer robots like unicorns!
		if($value === FALSE && $log_errors === TRUE && ($message === FALSE || $message == ''))
		{
			log_message('error', 'Could not find the language line "'.$line.'"');
		}

		return ((!$value AND $message) ? $message : $value);
	}

	/**
	 * Get base language
	 * 
	 * Return the default language directory
	 *
	 * @return string
	 */
	public function get_default_language_dir()
	{
		return  $this->default_language_dir;
	}

	/**
	 * Identify by domain
	 *
	 * Language identify by domain name. If language found, we set the language object and return TRUE.
	 *
	 * @return bool
	 * @throws Exception
	 */
	private function identify_by_domain()
	{
		// Check available languages
		if(in_array($this->get_top_level_domain(),$this->get_available_language_codes(TRUE)))
		{
			$this->set_language_object($this->get_language($this->get_top_level_domain()));
			return  TRUE;
		}

		return  FALSE;
	}

	/**
	 * Identify by slug
	 *
	 * Language identify by domain' first segment. If language found, we set the language object and return TRUE.
	 *
	 * @return bool
	 * @throws Exception
	 */
	private function identify_by_slug()
	{
		// Check available languages
		if(in_array($this->CI->uri->segment(1),$this->get_available_language_codes(TRUE)))
		{
			$this->set_language_object($this->get_language($this->CI->uri->segment(1)));
			return  TRUE;
		}
		return  FALSE;
	}

	/**
	 * Check session
	 *
	 * Is Language object exists in session and if yes, compare the current url language.
	 *
	 * @return bool
	 */
	private function check_session()
	{
		return ( ($this->CI->session->userdata('language') AND is_object($this->CI->session->userdata('language'))) AND (($this->CI->session->userdata('language')->code == $this->get_top_level_domain()) OR ((in_array($this->get_top_level_domain(),$this->exceptions) AND $this->CI->session->userdata('language')->code == $this->CI->uri->segment(1)) OR (in_array($this->get_top_level_domain(),$this->exceptions) AND !$this->CI->uri->segment(1) AND $this->CI->session->userdata('language')->directory == $this->get_default_language_dir()))) );
	}

	/**
	 * Get top level domain
	 *
	 * @return string
	 */
	private function get_top_level_domain()
	{
		return  $this->top_level_domain;
	}

	/**
	 * Get active language codes
	 *
	 * Return all active language codes.
	 *
	 * @param   bool        $single: If TRUE the result will be transformed simple array.
	 *
	 * @return  mixed
	 * @throws  Exception
	 */
	private function get_available_language_codes($single = FALSE)
	{
		// Get default language
		$language   = $this->CI->Language->as_array()->fields('code')->get_all(array('status'=>1));
		// Set back as object
		$this->CI->Language->as_object();
		// Check result
		if($language)
		{
			if($single)
			{
				return  iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($language)), 0);
			}
			else
			{
				return  $language;
			}
		}
		else
		{
			throw new Exception('There is no active language.');
		}
	}

	/**
	 * Get language
	 *
	 * Return language object by code if not empty.
	 *
	 * @param $code
	 *
	 * @return object
	 * @throws Exception
	 */
	private function get_language($code)
	{
		// Get default language
		$language   = $this->CI->Language->fields($this->language_data)->get(array('status' =>1, 'code' => $code));
		// Check result
		if($language)
		{
			return  $language;
		}
		else
		{
			throw new Exception('The language not found. Language code: ' . $code);
		}
	}

	/**
	 * Get default language
	 *
	 * Return default language object if not empty.
	 *
	 * @return object
	 * @throws Exception
	 */
	private function get_default_language()
	{
		// Get default language
		$language   = $this->CI->Language->fields($this->language_data)->get(array('status' => 1, 'directory' => $this->get_default_language_dir()));
		// Check result
		if($language)
		{
			return  $language;
		}
		else
		{
			throw new Exception('Default language not found. ' . $this->get_default_language_dir());
		}
	}

	/**
	 * Set language
	 *
	 * Add current language to session if if's not exist.
	 *
	 * @throws Exception
	 */
	private function set_language()
	{
		if(!$this->check_session())
		{
			if($this->identify_by_domain())
			{
				// Set language
				$this->CI->session->set_userdata('language',$this->get_language_object());
			}
			elseif(!$this->identify_by_domain() AND in_array($this->get_top_level_domain(),$this->exceptions) AND $this->identify_by_slug())
			{
				// Set exception language
				$this->CI->session->set_userdata('language',$this->get_language_object());
			}
			else
			{
				// Set default language
				$this->set_language_object($this->get_default_language());
				$this->CI->session->set_userdata('language',$this->get_language_object());
			}
		}
	}

	/**
	 * Set default language dir
	 */
	private function set_default_language_dir()
	{
		$this->default_language_dir = ($this->CI->config->item('language') ? $this->CI->config->item('language') : 'hungarian');
	}

	/**
	 * Set language object
	 *
	 * @param stdClass $language
	 */
	private function set_language_object(stdClass $language)
	{
		$this->language_object  = $language;
	}

	/**
	 * Set top level domain
	 *
	 * Set the top level domain of current url, if input variable is NULL.
	 *
	 * @param null  $top_level_domain
	 */
	private function set_top_level_domain($top_level_domain = NULL)
	{
		if($top_level_domain)
		{
			$this->top_level_domain = $top_level_domain;
		}
		else
		{
			$domains    = explode('.',$_SERVER['HTTP_HOST']);
			$this->top_level_domain = end($domains);
		}
	}
}