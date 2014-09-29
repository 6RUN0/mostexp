<?php

require_once('mods/mostexp/class.mostexpensive.php');
event::register('home_assembling', 'init_mostexpensive::handler');

$modInfo['mostexp']['name'] = 'Most Expensive Kills';
$modInfo['mostexp']['abstract'] = '';
$modInfo['mostexp']['about'] = 'Created by <a href="http://babylonknights.com/">Khi3l</a>.<br />
Patched by <a href="https://github.com/6RUN0/">boris_t</a>.<br />
<a href="https://github.com/6RUN0/mostexp">Get Most Expensive Kills</a>';

class init_mostexpensive {

  public static function handler($pHome) {
    $options = config::get('mostexp_options');
    if(!isset($options['position'])) {
      $options['position'] = 'summaryTable';
    }
    $pHome->addBehind($options['position'], 'mostexpensive::display');
    $pHome->addBehind('start', 'init_mostexpensive::headers');
  }

  public static function headers($pHome) {
    $pHome->page->addHeader('<link rel="stylesheet" type="text/css" href="' . KB_HOST . '/mods/mostexp/style.css" />');
    $pHome->page->addHeader('<script type="text/javascript" src="' . KB_HOST . '/mods/mostexp/script.js"></script>');
  }

}
