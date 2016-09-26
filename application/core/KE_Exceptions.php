<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * KE_Exception class
 *
 * Extended class to manage template system.
 *
 * @author: Kenariosz
 * @date: 2016-06-07
 */
class KE_Exceptions extends CI_Exceptions {

	/**
	 * Nesting level of the output buffering mechanism
	 *
	 * @var	int
	 */
	public $config;

	/**
	 * KE_Exceptions constructor
	 *
	 * Load config class and settings config file.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->config   = load_class('Config', 'core');
		$this->config->load('settings');
		// Note: Do not log messages from this constructor.
	}

	/**
	 * General Error Page
	 *
	 * Takes an error message as input (either as a string or an array)
	 * and displays it using the specified template.
	 *
	 * @param string          $heading      Page heading
	 * @param string|string[] $message      Error message
	 * @param string          $template     Template name
	 * @param int             $status_code  (default: 500)
	 *
	 * @return string                       Error page output
	 */
	public function show_error($heading, $message, $template = 'error_general', $status_code = 500)
	{
		// Load template directory name from settings config.
		$templates_dir  = config_item('template_dir');
		$templates_path = config_item('error_views_path');
		if(empty($templates_path))
		{
			$templates_path = VIEWPATH.$templates_dir.DIRECTORY_SEPARATOR.'errors'.DIRECTORY_SEPARATOR;
		}

		if (is_cli())
		{
			$message = "\t".(is_array($message) ? implode("\n\t", $message) : $message);
			$template = 'cli'.DIRECTORY_SEPARATOR.$template;
		}
		else
		{
			set_status_header($status_code);
			$message = '<p>'.(is_array($message) ? implode('</p><p>', $message) : $message).'</p>';
			$template = 'html'.DIRECTORY_SEPARATOR.$template;
		}

		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();
		}
		ob_start();
		include($templates_path.$template.'.php');
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}

	/**
	 * Show exception page
	 *
	 * @param   object    $exception
	 *
	 * @return  string  Exception page output
	 */
	public function show_exception($exception)
	{
		// Load template directory name from settings config.
		$templates_dir  = config_item('template_dir');
		$templates_path = config_item('error_views_path');
		if(empty($templates_path))
		{
			$templates_path = VIEWPATH.$templates_dir.DIRECTORY_SEPARATOR.'errors'.DIRECTORY_SEPARATOR;
		}

		$message        = $exception->getMessage();
		if(empty($message))
		{
			$message    = '(null)';
		}

		if (is_cli())
		{
			$templates_path .= 'cli'.DIRECTORY_SEPARATOR;
		}
		else
		{
			set_status_header(500);
			$templates_path .= 'html'.DIRECTORY_SEPARATOR;
		}

		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();
		}

		ob_start();
		include($templates_path.'error_exception.php');
		$buffer = ob_get_contents();
		ob_end_clean();
		echo $buffer;
	}

	/**
	 * Native PHP error handler
	 *
	 * @param int    $severity  Error level
	 * @param string $message   Error message
	 * @param string $filepath  File path
	 * @param int    $line      Line number
	 *
	 * @return	string	Error page output
	 */
	public function show_php_error($severity, $message, $filepath, $line)
	{
		// Load template directory name from settings config.
		$templates_dir  = config_item('template_dir');
		$templates_path = config_item('error_views_path');
		if(empty($templates_path))
		{
			$templates_path = VIEWPATH.$templates_dir.DIRECTORY_SEPARATOR.'errors'.DIRECTORY_SEPARATOR;
		}

		$severity   = isset($this->levels[$severity]) ? $this->levels[$severity] : $severity;

		// For safety reasons we don't show the full file path in non-CLI requests
		if(! is_cli())
		{
			$filepath   = str_replace('\\', '/', $filepath);
			if(FALSE !== strpos($filepath, '/'))
			{
				$x  = explode('/', $filepath);
				$filepath   = $x[count($x)-2].'/'.end($x);
			}

			$template   = 'html'.DIRECTORY_SEPARATOR.'error_php';
		}
		else
		{
			$template   = 'cli'.DIRECTORY_SEPARATOR.'error_php';
		}

		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();
		}
		ob_start();
		include($templates_path.$template.'.php');
		$buffer = ob_get_contents();
		ob_end_clean();
		echo $buffer;
	}
}
