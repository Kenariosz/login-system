<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author: Kenariosz
 * @date: 2015-11-16
 */
abstract class Singleton {

	/**
	 * This contains children classes data.
	 *
	 * @var array
	 */
	private static $map = array();

	protected function __construct(){}

	public static function get_instance()
	{
		//Get class's name
		$class = get_called_class();
		if (!isset(self::$map[$class]))
		{
			self::$map[$class] = new $class();
		}
		return self::$map[$class];
	}
}