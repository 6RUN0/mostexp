<?php
/**
 * $Id$
 *
 * @category Settings
 * @package  Mostexp
 * @author   Khi3l, boris_t <boris@talovikov.ru>
 * @license  http://opensource.org/licenses/MIT MIT
 */

class MostExpensiveInitSettings extends pageAssembly
{
    public $page;
    private $_opt = array();
    private $_msg = array();

    /**
     * Constructor methods for this classes.
     */
    function __construct()
    {
        parent::__construct();
        $this->queue('start');
        $this->queue('form');
    }

    /**
     * Preparation of the form.
     *
     * @return none
     */
    function start()
    {
        $this->page = new Page();
        $this->page->setTitle('Most Expensive Kills');
        $this->page->addHeader('<link rel="stylesheet" type="text/css" href="' . KB_HOST . '/mods/mostexp/settings.css" />');
        $default_options = array(
            'display' => 'board',
            'period' => '7',
            'count' => '5',
            'viewpods' => 'yes',
            'periodpods' => '7',
            'countpods' => '5',
            'what' => 'kill',
            'position' => 'summaryTable',
            'only_verified' => 'yes',
        );
        $this->_opt = config::get('mostexp_options');
        if (empty($this->_opt)) {
            $this->_opt = $default_options;
            config::set('mostexp_options', $this->_opt);
        }
        $this->_msg['text'] = '';
        if (isset($_POST['submit'])) {
            $this->_opt = $_POST['settings'];
            $this->_opt['period'] = $this->_is_natural($this->_opt['period']);
            if (!$this->_opt['period']) {
                $this->_opt['period'] = '';
                $this->_msg['text'] .= 'Day period is natural number<br />';
                $this->_msg['period'] = 'mostexp-err';
            }
            $this->_opt['count'] = $this->_is_natural($this->_opt['count']);
            if (!$this->_opt['count']) {
                $this->_opt['count'] = '';
                $this->_msg['text'] .= 'Kill count is natural number<br />';
                $this->_msg['count'] = 'mostexp-err';
            }
            $this->_opt['periodpods'] = $this->_is_natural($this->_opt['periodpods']);
            if (!$this->_opt['periodpods']) {
                $this->_opt['periodpods'] = '';
                $this->_msg['text'] .= 'Pods Day period is natural number<br />';
                $this->_msg['periodpods'] = 'mostexp-err';
            }
            $this->_opt['countpods'] = $this->_is_natural($this->_opt['countpods']);
            if (!$this->_opt['countpods']) {
                $this->_opt['countpods'] = '';
                $this->_msg['text'] .= 'Pods Counts is natural number<br />';
                $this->_msg['countpods'] = 'mostexp-err';
            }
            config::set('mostexp_options', $this->_opt);
        }
    }

    /**
     * Render setting form.
     *
     * @return string html
     */
    function form()
    {
        global $smarty;
        $smarty->assign('mostexp_options', $this->_opt);
        $smarty->assign('mostexp_msg', $this->_msg);
        return $smarty->fetch(get_tpl('./mods/mostexp/mostexp_settings'));
    }


    /**
     * Build context.
     *
     * @return none
     */
    function context()
    {
        parent::__construct();
        $this->queue('menu');
    }

    /**
     * Render of admin menu.
     *
     * @return string html
     */
    function menu()
    {
        include 'common/admin/admin_menu.php';
        return $menubox->generate();
    }

    /**
     * Finds whether the type of the given variable is natural number.
     *
     * @param string|int $num - number
     *
     * @return unsigned integer|false natular number or false
     */
    private function _is_natural($num)
    {
        $num = intval($num);
        if (is_int($num) && $num > 0) {
            return $num;
        }
        return false;
    }

}

$pageAssembly = new MostExpensiveInitSettings();
event::call('MostExpensiveInitSettings_assembling', $pageAssembly);
$html = $pageAssembly->assemble();
$pageAssembly->page->setContent($html);

$pageAssembly->context();
event::call('MostExpensiveInitSettings_context_assembling', $pageAssembly);
$context = $pageAssembly->assemble();
$pageAssembly->page->addContext($context);

$pageAssembly->page->generate();
