<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author: Kenariosz
 * @date: 2015-11-16
 */
abstract class View_helper extends Singleton{

	abstract protected function render();
}