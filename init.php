<?php
	if(!defined('KB_SITE')) die ("Go Away!");
	
	require_once("mods/mostexp/class.mostexpensive.php");
	event::register("home_assembling", "init_mostexpensive::handler");
	
	class init_mostexpensive
	{
		public static function handler(&$home)
		{
			$home->addBehind(config::get('mostexp_position'), "mostexpensive::display");
			$home->addBehind("start", "init_mostexpensive::headers");
		}
		public static function headers($home)
		{
			$home->page->addHeader("\t<link rel=\"stylesheet\" type=\"text/css\" href=\"mods/mostexp/css/mostexp.css\" />");
		}
	}
