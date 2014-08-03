<?php
  if(!defined('KB_SITE')) die ('Go Away!');

  class mostexpensive
  {
    public static $week = 0;
    public static $month = 0;
    public static $year = 0;

    public static function display()
    {
      global $smarty;

      $mostexp_display = config::get('mostexp_display');
      $mostexp_what = config::get('mostexp_what');
      $mostexp_period = config::get('mostexp_period');
      $mostexp_period_pods = config::get('mostexp_period_pods');
      $show_monthly = config::get('show_monthly');
      $mostexp_count = config::get('mostexp_count');
      $mostexp_count_pods = config::get('mostexp_count_pods');
      $mostexp_count_pods = config::get('mostexp_count_pods');

      $klist = new KillList();
      $klist->setOrdered(true);
      //$klist->setAPIKill(TRUE);
      $klist->setOrderBy('kll_isk_loss DESC');
      $klist->setPodsNoobShips(false);
      $klist->setLimit($mostexp_count);

      $plist = new KillList();
      $plist->setOrdered(true);
      //$klist->setAPIKill(TRUE);

      $plist->setOrderBy('kll_isk_loss DESC');
      $plist->addVictimShipClass(2);
      $plist->setLimit($mostexp_count_pods);

      self::$week = edkURI::getArg('w', 2);
      self::$month = edkURI::getArg('m', 2);
      self::$year = edkURI::getArg('y',1);
      self::setTime(self::$week, self::$year, self::$month);

      switch($mostexp_display)
      {
        case 'days':
          $klist->setStartDate(date('Y-m-d H:i', strtotime("- ${mostexp_period} days")));
          $klist->setEndDate(date('Y-m-d H:i'));
          $plist->setStartDate(date('Y-m-d H:i', strtotime("- ${mostexp_period_pods} days")));
          $plist->setEndDate(date('Y-m-d H:i'));
          $smarty->assign('displaylist', "the past ${mostexp_period} days");
          $smarty->assign('displaylistpods', "the past ${mostexp_period_pods} days");
        break;
        default:
          if($show_monthly)
          {
            $start = makeStartDate(0, self::$year, self::$month);
            $end = makeEndDate(0, self::$year, self::$month);
            $klist->setStartDate(gmdate('Y-m-d H:i',$start));
            $klist->setEndDate(gmdate('Y-m-d H:i',$end));
            $plist->setStartDate(gmdate('Y-m-d H:i',$start));
            $plist->setEndDate(gmdate('Y-m-d H:i',$end));
            $smarty->assign('displaylist', date('F', mktime(0,0,0,self::$month, 1,self::$year)) . ', ' . self::$year);
            $smarty->assign('displaylistpods', date('F', mktime(0,0,0,self::$month, 1,self::$year)) . ', ' . self::$year); 
          }
          else
          {
            $klist->setWeek(self::$week);
            $klist->setYear(self::$year);
            $plist->setWeek(self::$week);
            $plist->setYear(self::$year);
            $smarty->assign('displaylist', 'Week ' . self::$week . ', ' . self::$year);
            $smarty->assign('displaylistpods', 'Week ' . self::$week . ', ' . self::$year);
          }
        break;
      }
      switch($mostexp_what) {
        case 'combined':
          $smarty->assign('displaytype', 'Kills and Losses');
          break;
        case 'kill':
          $smarty->assign('displaytype', 'Kills');
          break;
      }

      involved::load($klist, $mostexp_what);
      involved::load($plist, $mostexp_what);

      $smarty->assign('killlist', self::getKills($klist));
      $smarty->assign('width', 100/$mostexp_count);
      $smarty->assign('widthpods', 100/$mostexp_count_pods);
      $smarty->assign('podlist', self::getKills($plist));

      return $smarty->fetch(get_tpl('./mods/mostexp/tpl/mostexpensive'));

    }
    public static function setTime($week = 0, $year = 0, $month = 0)
    {
      if ($week)
      {
        $w = $week;
      }
      else
      {
        $w = (int) kbdate('W');
      }
      if ($month)
      {
        $m = $month;
      }
      else
      {
        $m = (int) kbdate('m');
      }
      if ($year)
      {
        $y = $year;
      }
      else
      {
        $y = (int) kbdate('o');
      }
      if ($m < 10) $m = '0' . $m;
      if ($w < 10) $w = '0' . $w;
      self::$year = $y;
      self::$month = $m;
      self::$week = $w;
    }

    private function getKills($killList) {
      $allianceIDs = config::get('cfg_allianceid');
      $corpIDs = config::get('cfg_corpid');
      $pilotIDs = config::get('cfg_pilotid');
      $kills = array();

      while ($kill = $killList->getKill()) {
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
        $kll['victim'] = $kill->getVictimName();
        $kll['victimid'] = $kill->getVictimID();
        $kll['victimimage'] = $plt->getPortraitURL(64);
        $kll['victimdetails'] = $plt->getDetailsURL();
        $kll['victimship'] = $kill->getVictimShipName();
        $kll['victimshipid'] = $kill->getVictimShipExternalID();
        $kll['victimshipimage'] = $kill->getVictimShipImage(64);
        $kll['victimshipclass'] = $kill->getVictimShipClassName();
        $kll['victimcorp'] = $kill->getVictimCorpName();
        $kll['victimcorpid'] = $kill->getVictimCorpID();

        if ((int) number_format($kill->getISKLoss(), 0, '','')>1000000000) {
          $kll['isklost'] = number_format($kill->getISKLoss()/1000000000, 2, '.','') . ' Billion';
        }
        elseif ((int) number_format($kill->getISKLoss(), 0, '','')>1000000) {
          $kll['isklost'] = number_format($kill->getISKLoss()/1000000, 2, '.','') . ' Million';
        }
        else {
          $kll['isklost'] = number_format($kill->getISKLoss(), 0, '.',',');
        }

        if(in_array($kill->getVictimAllianceID(), $allianceIDs) || in_array($kill->getVictimCorpID(), $corpIDs) || in_array($kill->getVictimID(), $pilotIDs)) {
          $kll['class'] = 'kl-loss';
          $kll['classlink'] = '<span class="mostexp-loss">&bull;</span>';
        }
        else {
          $kll['class'] = 'kl-kill';
          $kll['classlink'] = '<span class="mostexp-kill">&bull;</span>';
        }

        $kills[] = $kll;
      }

      return $kills;
    }
  }
