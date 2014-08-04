<?php

if(!defined('KB_SITE')) die ('Go Away!');

require_once('mods/mostexp/class.mostexpensive.php');
event::register('home_assembling', 'init_mostexpensive::handler');

$modInfo['mostexp']['name'] = 'Most Expensive Kills';
$modInfo['mostexp']['abstract'] = '';
$modInfo['mostexp']['about'] = 'Created by <a href=\"http://babylonknights.com/\">Khi3l</a>. Patched by Boris Blade Artrald.<br />
<a href="https://github.com/6RUN0/mostexp">Get Most Expensive Kills</a>';

class init_mostexpensive {

  public static function handler($home) {
    $home->addBehind(config::get('mostexp_position'), 'mostexpensive::display');
    $home->addBehind('start', 'init_mostexpensive::headers');
  }

  public static function headers($home) {
    $home->page->addHeader('<link rel="stylesheet" type="text/css" href="' . KB_HOST . '/mods/mostexp/style.css" />');
    $home->page->addHeader('<script type="text/javascript" src="' . KB_HOST . '/mods/mostexp/script.js"></script>');
  }

}
