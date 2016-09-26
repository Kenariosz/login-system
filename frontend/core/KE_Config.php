<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * KE_Config Class
 *
 * Extended class for more flexible usage.
 *
 * @author: Kenariosz
 * @date: 2016-06-07
 */
class KE_Config extends CI_Config {

	/**
	 * KE_Config constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		log_message('info', 'KE_Config Class Initialized');
	}

	/**
	 * Load settings from database
	 */
	public function load_settings_from_db()
	{
		// CI object init.
		$CI =& get_instance();
		// Setting Model
		$CI->load->model('settings/Setting');
		// Get settings from DB.
		$query  = $CI->Setting->fields('code, key, value')->get_all();
		// Check result
		if($query)
		{
			// Parse result
			foreach ($query as $row)
			{
				// If code's value is equal to db_config_type's value, we cut that prefix. That's whay we can override default CI config settings.
				if($this->item('db_config_type')===$row->code)
				{
					$row->key   = substr($row->key, (strlen($this->item('db_config_type')) + 1));
				}
				// Set config item
				$this->set_item($row->key,$row->value);
			}
			// Debug log
			log_message('debug', 'Settings loaded to config from settings\'s table.');
		}
		else
		{
			show_error('The settings\'s table is empty.');
		}
	}
}