<?php

class mostexpensive {

  private static $week;
  private static $year;
  private static $month;
  private static $scl_id;
  private static $view;

  private static $options = array();
  private static $monthly;

  private static $all;
  private static $corp;
  private static $pilot;


  /**
   *  Define self variables.
   *  @param $pHome - pHome object, see ../../common/home.php
   */
  private static function define_vars($pHome) {

    self::$week = $pHome->getWeek();
    self::$year = $pHome->getYear();
    self::$month = $pHome->getMonth();
    self::$scl_id = edkURI::getArg('scl_id');
    self::$view = $pHome->getView();

    self::$options = config::get('mostexp_options');

    self::$monthly = config::get('show_monthly');
    self::$all = config::get('cfg_allianceid');
    self::$corp = config::get('cfg_corpid');
    self::$pilot = config::get('cfg_pilotid');

  }

  /**
   *  Display most extensive kills.
   *  @param $pHome - pHome object, see ../../common/home.php
   *  @return string, html markup
   */
  public static function display($pHome) {

    self::define_vars($pHome);

    $result = '';
    $result .= self::render_tpl(self::$options['period'], self::$options['count']);
    if(self::$options['viewpods'] == 'yes') {
      $result .= self::render_tpl(self::$options['periodpods'], self::$options['countpods'], TRUE);
    }

    return $result;

  }

  /**
   *  Rendering template 'mostexpensive.tpl'.
   *  @param $period, period of kills
   *  @param $count, count of kills
   *  @return string, html markup
   */
  private static function render_tpl($period, $count, $pod = FALSE) {

    global $smarty;

    $klist = new KillList();
    $klist->setOrdered(true);
    $klist->setOrderBy('kll_isk_loss DESC');
    if(self::$options['only_verified'] == 'yes') {
      $klist->setAPIKill(TRUE);
    }
    $prefix = '';
    if($pod) {
      self::$scl_id = 2;
      $prefix = 'Pod ';
    }
    // If capsule, noob ship or shuttle
    if(self::$scl_id == 2 || self::$scl_id == 3 || self::$scl_id == 11) {
      $pod = TRUE;
    }
    if(self::$scl_id) {
      $klist->addVictimShipClass(self::$scl_id);
    }
    $klist->setPodsNoobShips($pod);
    $klist->setLimit($count);

    switch(self::$options['display']) {

      case 'days':
        $klist->setStartDate(date('Y-m-d H:i', strtotime("- ${period} days")));
        $klist->setEndDate(date('Y-m-d H:i'));
        $smarty->assign('displaylist', "the past ${period} days");
      break;

      default:
        if(self::$monthly) {
          $start = makeStartDate(0, self::$year, self::$month);
          $end = makeEndDate(0, self::$year, self::$month);
          $klist->setStartDate(gmdate('Y-m-d H:i', $start));
          $klist->setEndDate(gmdate('Y-m-d H:i', $end));
          $date = date_format(date_create(self::$year . '-' . self::$month), 'F, Y');
          $smarty->assign('displaylist', $date);
        }
        else {
          $klist->setWeek(self::$week);
          $klist->setYear(self::$year);
          $date = 'Week ' . self::$week . ', ' .  self::$year;
          $smarty->assign('displaylist', $date);
        }
      break;

    }

    switch(self::$view) {
      case 'kills':
        self::$options['what'] = 'kill';
        break;
      case 'losses':
        self::$options['what'] = 'loss';
        break;
    }

    switch(self::$options['what']) {
      case 'combined':
        $smarty->assign('displaytype', "${prefix}Kills and Losses");
        break;
      case 'kill':
        $smarty->assign('displaytype', "${prefix}Kills");
        break;
      case 'loss':
        $smarty->assign('displaytype', "${prefix}Losses");
        break;
    }

    involved::load($klist, self::$options['what']);

    $kills = array();

    while ($kill = $klist->getKill()) {
      $kll = array();
      $plt = new Pilot($kill->getVictimID());
      if ($kill->isClassified() && !Session::isAdmin()) {
        $kll['systemsecurity'] = '-';
        $kll['system'] = Language::get('classified');
      }
      else {
        $kll['systemsecurity'] = $kill->getSolarSystemSecurity();
        $kll['system'] = $kill->getSolarSystemName();
      }
      $kll['id'] = $kill->getID();
      $kll['kill_detail'] = edkURI::page('kill_detail',  $kll['id'], 'kll_id');
      $kll['name'] = $kill->getVictimName();
      $kll['details'] = $plt->getDetailsURL();
      $kll['shipname'] = $kill->getVictimShipName();
      $isk_loss = $kill->getISKLoss();
      if(self::$scl_id == 2) {
        $kll['img'] = '<img class="mostexp-img" src="' . $plt->getPortraitURL(64) . '" alt="' . $kll['name'] . '"/>';
      }
      else {
        $kll['img'] = '<img class="mostexp-img" src="' . $kill->getVictimShipImage(64) . '" alt="' . $kll['shipname'] . '"/>';
      }
      if ($isk_loss > 1000000000) {
        $kll['isklost'] = number_format($isk_loss/1000000000, 2, '.','') . ' Billion';
      }
      elseif ($isk_loss > 1000000) {
        $kll['isklost'] = number_format($isk_loss/1000000, 2, '.','') . ' Million';
      }
      else {
        $kll['isklost'] = number_format($isk_loss, 0, '.','');
      }

      if(in_array($kill->getVictimAllianceID(), self::$all) || in_array($kill->getVictimCorpID(), self::$corp) || in_array($kill->getVictimID(), self::$pilot)) {
        $kll['class'] = 'kl-loss';
        $kll['classlink'] = '<span class="mostexp-loss">&bull;</span>';
      }
      else {
        $kll['class'] = 'kl-kill';
        $kll['classlink'] = '<span class="mostexp-kill">&bull;</span>';
      }

      $kills[] = $kll;
    }
    $smarty->assign('killlist', $kills);
    $smarty->assign('width', 100/$count);
    return $smarty->fetch(get_tpl('./mods/mostexp/mostexpensive'));

  }

}
