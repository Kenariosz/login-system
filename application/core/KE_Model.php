<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * KE_Model class
 *
 * Basic cental CRUD.
 *
 * @author: Kenariosz
 * @date: 2016-06-07
 */
class KE_Model extends CI_Model {

	/**
	 * Table name
	 *
	 * @var null
	 */
	private $table_name             = NULL;

	/**
	 * Select the database connection from the group names defined inside the database.php configuration file or an
	 * array.
	 *
	 * @var null
	 */
	protected $_database_connection = NULL;

	/**
	 * Store DB connection object
	 *
	 * @var
	 */
	protected $_database;

	/**
	 * SQL result type: object|array
	 *
	 * @var string
	 */
	protected $result_type          = 'object';

	/**
	 * Use table prefix.
	 * If TRUE load settings config and set prefix
	 *
	 * @var bool
	 */
	protected $use_table_prefix     = TRUE;

	/**
	 * Rules container
	 *
	 * @var array
	 */
	protected $rules = array(
		'insert' => array(),
		'update' => array(),
	);

	public function __construct()
	{
		parent::__construct();
		// Load helpers
		$this->load->helper('inflector');
		// Set connection and model data
		$this->_set_connection();
		$this->set_table_name();
		// Log message
		log_message('info', 'KE_Model Class Initialized');
	}

	/**
	 * Get all
	 *
	 * Return all sql result. If result is zero, return value is NULL
	 *
	 * @param mixed $where
	 *
	 * @return mixed
	 */
	public function get_all($where = NULL)
	{
		$this->where($where);
		$query  = $this->_database->get($this->table_name);
		if($query->num_rows() > 0)
		{
			return  $query->{$this->_get_result_type(TRUE)}();
		}
		else
		{
			return  NULL;
		}
	}

	/**
	 * Get
	 *
	 * Return one sql result. If result zero or more than one, return value is NULL.
	 *
	 * @param mixed $where
	 *
	 * @return mixed
	 */
	public function get($where = NULL)
	{
		$this->where($where);
		$this->limit(1);
		$query  = $this->_database->get($this->table_name);
		if($query->num_rows() == 1)
		{
			return  $query->{$this->_get_result_type(FALSE)}();
		}
		return  NULL;
	}

	/**
	 * Insert
	 *
	 * @param null $data
	 *
	 * @return bool
	 */
	public function insert($data = NULL)
	{
		if(!isset($data) || empty($data))
		{
			return  FALSE;
		}

		return  $this->_database->insert($this->get_table_name(),$data);
	}

	/**
	 * Insert id
	 *
	 * Get last insert id
	 *
	 * @return mixed
	 */
	public function insert_id()
	{
		return  $this->_database->insert_id();
	}

	/**
	 * Update
	 *
	 * @param null $data
	 *
	 * @return bool
	 */
	public function update($data = NULL)
	{
		if(!isset($data) || empty($data))
		{
			return  FALSE;
		}

		if($this->_database->update($this->get_table_name(),$data))
		{
			return  $this->_database->affected_rows();
		}

		return  FALSE;
	}

	/**
	 * Delete
	 *
	 * @return bool
	 */
	public function delete()
	{
		$affected_rows  = 0;
		if($this->_database->delete($this->get_table_name()))
		{
			return  $this->_database->affected_rows();
		}
		return  FALSE;
	}

	/**
	 * Count all results
	 *
	 * @param null $where
	 *
	 * @return int
	 */
	public function count_all_results($where = NULL)
	{
		$this->where($where);
		return  $this->_database->count_all_results($this->table_name);
	}

	/**
	 * List fields
	 *
	 * @return int
	 */
	public function list_fields( $table_name = '' )
	{
		if($table_name==='')
		{
			return  $this->_database->list_fields($this->table_name);
		}
		else
		{
			return  $this->_database->list_fields($table_name);
		}
	}

	/**
	 * Fields
	 *
	 * Set a specific table columns name to select method.
	 * for example:
	 *  string: name, email, phone
	 *  array: array('name','email','phone')
	 *
	 * @param mixed $fields
	 *
	 * @return $this
	 */
	public function fields($fields = NULL)
	{
		if(isset($fields))
		{
			$fields = (is_array($fields)) ? implode(',',$fields) : $fields;
			$this->_database->select($fields);
		}
		return  $this;
	}

	/**
	 * Where
	 *
	 * SQL where condition
	 *
	 * @param null $where
	 *
	 * @return $this
	 */
	public function where($where = NULL)
	{
		if($where)
		{
			$this->_database->where($where);
		}
		return  $this;
	}

	/**
	 * As array
	 *
	 * Return the next call as an array rather than an object
	 *
	 * @return $this
	 */
	public function as_array()
	{
		$this->_set_result_type('array');
		return  $this;
	}

	/**
	 * As object
	 *
	 * Return the next call as an object rather than an array
	 *
	 * @return $this
	 */
	public function as_object()
	{
		$this->_set_result_type('object');
		return  $this;
	}

	/**
	 * Get result type
	 *
	 * Return result type.
	 *
	 * @return string
	 */
	public function get_result_type()
	{
		return $this->result_type;
	}

	/**
	 * Get rules
	 *
	 * Return rules
	 *
	 * @param string $type     We can filter the rule by key. (insert, update...)
	 *
	 * @return array|mixed
	 */
	public function get_rules($type='')
	{
		if(!empty($type) && isset($this->rules[$type]))
		{
			return  $this->rules[$type];
		}
		return  $this->rules;
	}

	/**
	 * Get table name
	 *
	 * Return table name.
	 *
	 * @return string
	 */
	public function get_table_name()
	{
		return  $this->table_name;
	}

	/**
	 * Get new connection
	 *
	 * Create a new DB connection and return KE_Model object. You must a new connection config in db_config.php and pass the variable name to this function.
	 * New connection doesn't override the default connection.
	 *
	 * @param   string      $name
	 *
	 * @return  KE_Model    $KE_Model_object
	 */
	public function get_new_connection($name)
	{
		$KE_Model_object    = $this;
		$KE_Model_object->_database = $this->load->database($name, TRUE);
		return  $KE_Model_object;
	}

	/**
	 * Object to array
	 *
	 * Convert simple object to array.
	 *
	 * @param $object
	 *
	 * @return array
	 */
	protected function object_to_array($object)
	{
		$array  = array();
		foreach($object as $key => $value)
		{
			if(is_object($value))
			{
				$array[$key]    = $this->object_to_array($value);
			}
			else
			{
				$array[$key]    = $value;
			}
		}

		return  $array;
	}

	/**
	 * Array to object
	 *
	 * Convert simple array to object.
	 *
	 * @param $array
	 *
	 * @return object
	 */
	protected function array_to_object($array)
	{
		$object = new stdClass();
		foreach($array as $key => $value)
		{
			if(is_array($value))
			{
				$object->{$key} = $this->array_to_object($value);
			}
			else
			{
				$object->{$key} = $value;
			}
		}

		return  $object;
	}

	/**
	 * Add new rules
	 *
	 * @link http://www.codeigniter.com/user_guide/libraries/form_validation.html#setting-rules-using-an-array
	 *
	 * @param array $rules
	 * @param string $type
	 */
	protected function set_rules(array $rules,$type="insert")
	{
		if(!empty($rules) OR !empty($type) OR array_key_exists($type, $this->rules))
		{
			$this->rules[$type] = $rules;
		}
		else
		{
			// Log message
			log_message('error', 'KE_Model - set_rules: Error, rules were not added.');
		}
	}

	/**
	 * Set table name
	 *
	 * Set the table name with prefix, if prefix is enabled
	 */
	protected function set_table_name($table_name = NULL)
	{
		// Set table if NULL
		if(!$this->table_name)
		{
			$this->table_name   = $this->_get_table_name(get_class($this));
		}
		elseif($table_name!==NULL)
		{
			$this->table_name   = $table_name;
		}
		// Set table prefix if TRUE.
		if($this->use_table_prefix)
		{
			$this->table_name   = $this->config->item('db_prefix').$this->table_name;
		}
	}

	/**
	 * Return result type string.
	 *
	 * @param bool $all
	 *
	 * @return string
	 */
	private function _get_result_type($all = FALSE)
	{
		$method = ($all) ? 'result' : 'row';
		return  ($this->result_type === 'array') ? $method . '_array' : $method;
	}

	/**
	 * Set result type
	 *
	 * Set sql result type: array|object
	 *
	 * @param string $result_type
	 */
	private function _set_result_type($result_type = 'object')
	{
		$this->result_type  = $result_type;
	}

	/**
	 * Set connection
	 */
	private function _set_connection()
	{
		isset($this->_database_connection) ? $this->load->database($this->_database_connection) : $this->load->database();
		$this->_database    = $this->db;
	}

	/**
	 * Get table name
	 *
	 * Set table name if NULL. Use pular model name.
	 *
	 * @param $model_name
	 *
	 * @return string
	 */
	private function _get_table_name($model_name)
	{
		return  plural(preg_replace('/(_m|_model)?$/', '', strtolower($model_name)));
	}

	/**
	 * Call php magic method
	 *
	 * Magic method to catch undefined methods and check whether there is in CI database library.
	 *
	 * @param $method
	 * @param $arguments
	 *
	 * @return $this
	 * @throws Exception
	 */
	public function __call($method, $arguments)
	{
		if(!method_exists($this, $method) AND method_exists($this->_database, $method))
		{
			call_user_func_array( array($this->_database, $method), $arguments);
			return  $this;
		}
		else
		{
			throw new Exception('Undefined method KE_Model::' . $method . '() called');
		}
	}
}