<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author: Kenariosz
 * @date: 2015-11-22
 */
class HTML_Builder_Footer extends CI_Driver{
	/**
	 * Footer script object
	 *
	 * @var
	 */
	private $footer_script;
	/**
	 * Footer link object
	 *
	 * @var
	 */
	private $footer_link;


	public function __construct()
	{
		$this->footer_script= Footer_script::get_instance();
		$this->footer_link  = Footer_link::get_instance();
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
					$this->footer_script->prepend_file($script['file'], (isset($script['external'])?$script['external']:FALSE), (isset($script['conditional_style_sheets'])?$script['conditional_style_sheets']:array('start'=>'','end'=>'')), (isset($script['type'])?$script['type']:'text/javascript'));
					break;
				case 'offset':
					$this->footer_script->offset_file($script['file'], (isset($script['external'])?$script['external']:FALSE), (isset($script['conditional_style_sheets'])?$script['conditional_style_sheets']:array('start'=>'','end'=>'')), (isset($script['type'])?$script['type']:'text/javascript'), $script['index']);
					break;
				case 'append':
				default:
					$this->footer_script->append_file($script['file'], (isset($script['external'])?$script['external']:FALSE), (isset($script['conditional_style_sheets'])?$script['conditional_style_sheets']:array('start'=>'','end'=>'')), (isset($script['type'])?$script['type']:'text/javascript'));
					break;
			}
		}
	}
	/**
	 * Render script to html
	 */
	public function render_script()
	{
		$this->footer_script->render();
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
					$this->footer_link->prepend_file($style['file'], (isset($style['external'])?$style['external']:FALSE), (isset($style['conditional_style_sheets'])?$style['conditional_style_sheets']:array('start'=>'','end'=>'')), (isset($style['media'])?$style['media']:'all'), (isset($style['rel'])?$style['rel']:'stylesheet'), (isset($style['type'])?$style['type']:'text/css'));
					break;
				case 'offset':
					$this->footer_link->offset_file($style['file'], (isset($style['external'])?$style['external']:FALSE), (isset($style['conditional_style_sheets'])?$style['conditional_style_sheets']:array('start'=>'','end'=>'')), (isset($style['media'])?$style['media']:'all'), (isset($style['rel'])?$style['rel']:'stylesheet'), (isset($style['type'])?$style['type']:'text/css'), $style['index']);
					break;
				case 'append':
				default:
					$this->footer_link->append_file($style['file'], (isset($style['external'])?$style['external']:FALSE), (isset($style['conditional_style_sheets'])?$style['conditional_style_sheets']:array('start'=>'','end'=>'')), (isset($style['media'])?$style['media']:'all'), (isset($style['rel'])?$style['rel']:'stylesheet'), (isset($style['type'])?$style['type']:'text/css'));
					break;
			}
		}
	}
	/**
	 * Render style to html
	 */
	public function render_style()
	{
		$this->footer_link->render();
	}
}