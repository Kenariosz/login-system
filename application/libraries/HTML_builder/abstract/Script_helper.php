<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author: Kenariosz
 * @date: 2015-11-16
 */
abstract class Script_helper extends View_helper{
	/**
	 * This contains script objects
	 *
	 * @var array
	 */
	private $scripts = array();

	/**
	 * Append file to the end of scripts array.
	 *
	 * @param        $src                                   Source path. If external use full path otherwise use absolute path to js's directory.
	 * @param bool   $external                              External file or not.
	 * @param array  $conditional_style_sheets  start|end   Add html condition: start: <!--[if IE]>  end: <![endif]-->
	 * @param string $type                                  Js type attribute. Default: text/javascript
	 */
	public function append_file($src, $external=FALSE, $conditional_style_sheets=array('start'=>'','end'=>''), $type="text/javascript")
	{
		array_push($this->scripts,$this->get_script_object($src, $external, $conditional_style_sheets, $type, TRUE));
	}
	/**
	 * Add file to the first position of scripts array.
	 *
	 * @param        $src                                   Source path. If external use full path otherwise use absolute path to js's directory.
	 * @param bool   $external                              External file or not.
	 * @param array  $conditional_style_sheets  start|end   Add html condition: start: <!--[if IE]>  end: <![endif]-->
	 * @param string $type                                  Js type attribute. Default: text/javascript
	 */
	public function prepend_file($src, $external=FALSE, $conditional_style_sheets=array('start'=>'','end'=>''), $type="text/javascript")
	{
		array_unshift($this->scripts,$this->get_script_object($src, $external, $conditional_style_sheets, $type, TRUE));
	}
	/**
	 * Add file to the custom position of scripts array
	 *
	 * @param        $src                                   Source path. If external use full path otherwise use absolute path to js's directory.
	 * @param bool   $external                              External file or not.
	 * @param array  $conditional_style_sheets  start|end   Add html condition: start: <!--[if IE]>  end: <![endif]-->
	 * @param string $type                                  Js type attribute. Default: text/javascript
	 * @param int    $index                                 custom position
	 */
	public function offset_file($src, $external=FALSE, $conditional_style_sheets=array('start'=>'','end'=>''), $type="text/javascript",$index)
	{
		array_splice($this->scripts, $index, 0, array($this->get_script_object($src, $external, $conditional_style_sheets, $type, TRUE)));
	}
	/**
	 * Render scripts to html
	 */
	public function render()
	{
		foreach($this->scripts as $script)
		{
			if($script->is_file)
			{
				echo $script->conditional_style_sheets['start']."\n";
				echo "<script src=\"$script->src\" type=\"$script->type\"></script>\n";
				echo $script->conditional_style_sheets['end']."\n";
			}
			else
			{
				echo $script->conditional_style_sheets['start']."\n";
				echo "<script type=\"$script->type\">$script->inline_script</script>\n";
				echo $script->conditional_style_sheets['end']."\n";
			}
		}
	}
	/**
	 * Init and set script object
	 *
	 * @param        $src                                   Source path. If external use full path otherwise use absolute path to js's directory.
	 * @param bool   $external                              External file or not.
	 * @param array  $conditional_style_sheets  start|end   Add html condition: start: <!--[if IE]>  end: <![endif]-->
	 * @param string $type                                  Js type attribute. Default: text/javascript
	 * @param bool   $is_file
	 *
	 * @return stdClass
	 */
	private function get_script_object($src, $external, $conditional_style_sheets, $type, $is_file)
	{
		// Init script object
		$script = new stdClass;
		// Set values
		$script->src                        = ($external ? $src : KE_URL_helper::get_script_path($src));
		$script->conditional_style_sheets   = $conditional_style_sheets;
		$script->type                       = $type;
		$script->is_file                    = $is_file;

		return  $script;
	}
}