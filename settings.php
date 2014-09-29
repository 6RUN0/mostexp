<?php

class pMostExpSettings extends pageAssembly {

  public $page;
  private $options = array();

  function __construct() {
    parent::__construct();
    $this->queue('start');
    $this->queue('form');
  }

  function start() {
    $this->page = new Page();
    $this->page->setTitle('Most Expensive Kills');
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
    $this->options = config::get('mostexp_options');
    if(empty($this->options)) {
      $this->options = $default_options;
      config::set('mostexp_options', $this->options);
    }
    if(isset($_POST['submit'])) {
      $this->options = $_POST['settings'];
      config::set('mostexp_options', $this->options);
    }
  }

  function form() {
    global $smarty;
    $smarty->assign('mostexp_options', $this->options);
    return $smarty->fetch(get_tpl('./mods/mostexp/mostexp_settings'));
  }

  function context() {
    parent::__construct();
    $this->queue('menu');
  }

  function menu() {
    require_once('common/admin/admin_menu.php');
    return $menubox->generate();
  }

}

$pageAssembly = new pMostExpSettings();
event::call('pMostExpSettings_assembling', $pageAssembly);
$html = $pageAssembly->assemble();
$pageAssembly->page->setContent($html);

$pageAssembly->context();
event::call('pMostExpSettings_context_assembling', $pageAssembly);
$context = $pageAssembly->assemble();
$pageAssembly->page->addContext($context);

$pageAssembly->page->generate();
