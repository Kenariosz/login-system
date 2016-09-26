<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author: Kenariosz
 * @date: 2015-11-22
 */
class HTML_Builder_Head extends CI_Driver{
	/**
	 * Head script object
	 *
	 * @var
	 */
	private $head_script;
	/**
	 * Head link object
	 *
	 * @var
	 */
	private $head_link;


	public function __construct()
	{
		$this->head_script  = Head_script::get_instance();
		$this->head_link    = Head_link::get_instance();
	}
	/**
	 * Set scripts
	 *
	 * @param array $script_array
	 */
	public function set_script(array $script_array)
	{
		foreach($script_array as $script)
		{
			$action = (isset($script['action'])?$script['action']:'');
			switch($action){
				case 'prepend':
					$this->head_script->prepend_file($script['file'], (isset($script['external'])?$script['external']:FALSE), (isset($script['conditional_style_sheets'])?$script['conditional_style_sheets']:array('start'=>'','end'=>'')), (isset($script['type'])?$script['type']:'text/javascript'));
					break;
				case 'offset':
					$this->head_script->offset_file($script['file'], (isset($script['external'])?$script['external']:FALSE), (isset($script['conditional_style_sheets'])?$script['conditional_style_sheets']:array('start'=>'','end'=>'')), (isset($script['type'])?$script['type']:'text/javascript'), $script['index']);
					break;
				case 'append':
				default:
					$this->head_script->append_file($script['file'], (isset($script['external'])?$script['external']:FALSE), (isset($script['conditional_style_sheets'])?$script['conditional_style_sheets']:array('start'=>'','end'=>'')), (isset($script['type'])?$script['type']:'text/javascript'));
					break;
			}
		}
	}
	/**
	 * Render script to html
	 */
	public function render_script()
	{
		$this->head_script->render();
	}
	/**
	 * Set styles
	 *
	 * @param array $styles
	 */
	public function set_style(array $styles)
	{
		foreach($styles as $style)
		{
			$action = (isset($style['action'])?$style['action']:'');
			switch($action){
				case 'prepend':
					$this->head_link->prepend_file($style['file'], (isset($style['external'])?$style['external']:FALSE), (isset($style['conditional_style_sheets'])?$style['conditional_style_sheets']:array('start'=>'','end'=>'')), (isset($style['media'])?$style['media']:'all'), (isset($style['rel'])?$style['rel']:'stylesheet'), (isset($style['type'])?$style['type']:'text/css'));
					break;
				case 'offset':
					$this->head_link->offset_file($style['file'], (isset($style['external'])?$style['external']:FALSE), (isset($style['conditional_style_sheets'])?$style['conditional_style_sheets']:array('start'=>'','end'=>'')), (isset($style['media'])?$style['media']:'all'), (isset($style['rel'])?$style['rel']:'stylesheet'), (isset($style['type'])?$style['type']:'text/css'), $style['index']);
					break;
				case 'append':
				default:
					$this->head_link->append_file($style['file'], (isset($style['external'])?$style['external']:FALSE), (isset($style['conditional_style_sheets'])?$style['conditional_style_sheets']:array('start'=>'','end'=>'')), (isset($style['media'])?$style['media']:'all'), (isset($style['rel'])?$style['rel']:'stylesheet'), (isset($style['type'])?$style['type']:'text/css'));
					break;
			}
		}
	}
	/**
	 * Render style to html
	 */
	public function render_style()
	{
		$this->head_link->render();
	}

}