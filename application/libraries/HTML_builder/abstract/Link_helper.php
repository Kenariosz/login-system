<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Link helper class
 *
 * @author: Kenariosz
 * @date: 2015-11-16
 */
abstract class Link_helper extends View_helper{
	/**
	 * This contains style objects
	 *
	 * @var array
	 */
	private $styles = array();
	/**
	 * Append file to the end of styles array.
	 *
	 * @param        $href                                  style's href. If external use full path otherwise use absolute path to css's directory.
	 * @param bool   $external                              External href or not.
	 * @param array  $conditional_style_sheets  start|end   Add html condition: start: <!--[if IE]>  end: <![endif]-->
	 * @param string $media                                 Css media attribute. Default: all
	 * @param string $rel                                   Css rel attribute. Default: stylesheet
	 * @param string $type                                  Css type attribute. Default: text/css
	 */
	public function append_file($href, $external=FALSE, $conditional_style_sheets=array('start'=>'','end'=>''), $media='all', $rel='stylesheet', $type="text/css")
	{
		array_push($this->styles,$this->get_style_object($href, $external, $conditional_style_sheets, $media, $rel, $type, TRUE));
	}
	/**
	 * Add file to the first position of styles array.
	 *
	 * @param        $href                                  style's href. If external use full path otherwise use absolute path to css's directory.
	 * @param bool   $external                              External href or not.
	 * @param array  $conditional_style_sheets  start|end   Add html condition: start: <!--[if IE]>  end: <![endif]-->
	 * @param string $media                                 Css media attribute. Default: all
	 * @param string $rel                                   Css rel attribute. Default: stylesheet
	 * @param string $type                                  Css type attribute. Default: text/css
	 */
	public function prepend_file($href, $external=FALSE, $conditional_style_sheets=array('start'=>'','end'=>''), $media='all', $rel='stylesheet', $type="text/css")
	{
		array_unshift($this->styles,$this->get_style_object($href, $external, $conditional_style_sheets, $media, $rel, $type, TRUE));
	}
	/**
	 * Add file to the custom position of styles array
	 *
	 * @param        $href                                  style's href. If external use full path otherwise use absolute path to css's directory.
	 * @param bool   $external                              External href or not.
	 * @param array  $conditional_style_sheets  start|end   Add html condition: start: <!--[if IE]>  end: <![endif]-->
	 * @param string $media                                 Css media attribute. Default: all
	 * @param string $rel                                   Css rel attribute. Default: stylesheet
	 * @param string $type                                  Css type attribute. Default: text/css
	 * @param int    $index                                 custom position
	 */
	public function offset_file($href, $external=FALSE, $conditional_style_sheets=array('start'=>'','end'=>''), $media='all', $rel='stylesheet', $type="text/css", $index=3)
	{
		array_splice($this->styles, $index, 0, array($this->get_style_object($href, $external, $conditional_style_sheets, $media, $rel, $type, TRUE)));
	}
	/**
	 * Render styles to html.
	 */
	public function render()
	{
		foreach($this->styles as $style)
		{
			if($style->is_file)
			{
				echo $style->conditional_style_sheets['start']."\n";
				echo "<link rel=\"$style->rel\" type=\"$style->type\" href=\"$style->file\" media=\"$style->media\">\n";
				echo $style->conditional_style_sheets['end']."\n";
			}
			else
			{
				echo $style->conditional_style_sheets['start']."\n";
				echo "<style>$style->file</style>\n";
				echo $style->conditional_style_sheets['end']."\n";
			}
		}
	}
	/**
	 * Init and set style object
	 *
	 * @param        $href                                  style's href. If external use full path otherwise use absolute path to css's directory.
	 * @param bool   $external                              External href or not.
	 * @param array  $conditional_style_sheets  start|end   Add html condition: start: <!--[if IE]>  end: <![endif]-->
	 * @param string $media                                 Css media attribute. Default: all
	 * @param string $rel                                   Css rel attribute. Default: stylesheet
	 * @param string $type                                  Css type attribute. Default: text/css
	 * @param bool   $is_file
	 *
	 * @return stdClass
	 */
	private function get_style_object($href, $external, $conditional_style_sheets, $media, $rel, $type, $is_file)
	{
		// Init style object
		$style = new stdClass;
		// Set values
		$style->file                    = ($external ? $href : KE_URL_helper::get_style_path($href));
		$style->type                    = $type;
		$style->conditional_style_sheets= $conditional_style_sheets;
		$style->rel                     = $rel;
		$style->media                   = $media;
		$style->is_file                 = $is_file;

		return  $style;
	}
}