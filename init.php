<?php
/**
 * $Id$
 *
 * @category Init
 * @package  Mostexp
 * @author   Khi3l, boris_t <boris@talovikov.ru>
 * @license  http://opensource.org/licenses/MIT MIT
 */

require_once 'mods/mostexp/class.mostexpensive.php' ;
event::register('home_assembling', 'MostExpensiveInit::handler');

$modInfo['mostexp']['name'] = 'Most Expensive Kills';
$modInfo['mostexp']['abstract'] = '';
$modInfo['mostexp']['about'] = 'Created by <a href="http://babylonknights.com/">Khi3l</a>.<br />
Patched by <a href="https://github.com/6RUN0/">boris_t</a>.<br />
<a href="https://github.com/6RUN0/mostexp">Get Most Expensive Kills</a>';

/**
 * Provides callback for event::register.
 */
class MostExpensiveInit
{
    /**
     * Adds a callbacks in the queue.
     *
     * @param pHome $pHome object of pHome class
     *
     * @return none
     */
    public static function handler($pHome)
    {
        $options = config::get('mostexp_options');
        if (!isset($options['position'])) {
            $options['position'] = 'summaryTable';
        }
        $pHome->addBehind('start', 'MostExpensiveInit::headers');
        $pHome->addBehind($options['position'], 'MostExpensive::display');
    }

    /**
     * Adds styles and scripts.
     *
     * @param pHome $pHome object of pHome class
     *
     * @return none
     */
    public static function headers($pHome)
    {
        $pHome->page->addHeader('<link rel="stylesheet" type="text/css" href="' . KB_HOST . '/mods/mostexp/style.css" />');
        $pHome->page->addHeader('<script type="text/javascript" src="' . KB_HOST . '/mods/mostexp/script.js"></script>');
    }
}
