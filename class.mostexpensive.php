<?php
	if(!defined("KB_SITE")) die ("Go Away!");

	class mostexpensive
	{
		public static $week = 0;
		public static $month = 0;
		public static $year = 0;

		public static function display()
		{
			global $smarty;
			$klist = new KillList();
			$klist->setOrdered(true);
			$klist->setOrderBy("kll_isk_loss DESC");
			$klist->setPodsNoobShips(false);
			$klist->setLimit(config::get("mostexp_count"));
			
			$plist = new KillList();
			$plist->setOrdered(true);
			$plist->setOrderBy("kll_isk_loss DESC");
			$plist->addVictimShipClass(2);
			$plist->setLimit(config::get("mostexp_count_pods"));
			
			if(isset($_GET["w"])) self::$week = intval($_GET["w"]);
			if(isset($_GET["m"])) self::$month = intval($_GET["m"]);
			if(isset($_GET["y"])) self::$year = intval($_GET["y"]);
			self::setTime(self::$week, self::$year, self::$month);
			$view = preg_replace('/[^a-zA-Z0-9_-]/','',$_GET['view']);
			
			switch(config::get("mostexp_display"))
			{
				case "days":
					$klist->setStartDate(date("Y-m-d H:i", strtotime("- " . config::get("mostexp_period") . " days")));
					$klist->setEndDate(date("Y-m-d H:i"));
					$plist->setStartDate(date("Y-m-d H:i", strtotime("- " . config::get("mostexp_period_pods") . " days")));
					$plist->setEndDate(date("Y-m-d H:i"));
					$smarty->assign("displaylist", "the past " . config::get("mostexp_period") . " days");
					$smarty->assign("displaylistpods", "the past " . config::get("mostexp_period_pods") . " days");
				break;
				default:
					if(config::get('show_monthly'))
					{
						$start = makeStartDate(0, self::$year, self::$month);
						$end = makeEndDate(0, self::$year, self::$month);
						$klist->setStartDate(gmdate('Y-m-d H:i',$start));
						$klist->setEndDate(gmdate('Y-m-d H:i',$end));
						$plist->setStartDate(gmdate('Y-m-d H:i',$start));
						$plist->setEndDate(gmdate('Y-m-d H:i',$end));
						$smarty->assign("displaylist", date('F', mktime(0,0,0,self::$month, 1,self::$year)) . ", " . self::$year);
						$smarty->assign("displaylistpods", date('F', mktime(0,0,0,self::$month, 1,self::$year)) . ", " . self::$year);			
					}
					else
					{
						$klist->setWeek(self::$week);
						$klist->setYear(self::$year);
						$plist->setWeek(self::$week);
						$plist->setYear(self::$year);
						$smarty->assign("displaylist", "Week " . self::$week . ", " . self::$year);
						$smarty->assign("displaylistpods", "Week " . self::$week . ", " . self::$year);
					}
				break;
			}
			if(config::get("mostexp_what") == "combined")
			{
				$smarty->assign("displaytype", "Kills and Losses");
			}
			else if(config::get("mostexp_what")=="kill")
			{
				$smarty->assign("displaytype", "Kills");
			}
			involved::load($klist, config::get("mostexp_what"));
			involved::load($plist, config::get("mostexp_what"));
			
			$kills = array();
			while ($kill = $klist->getKill())
			{
				$kll = array();
				$plt = new Pilot($kill->getVictimID());
				if ($kill->isClassified() && !Session::isAdmin())
				{
					$kll['systemsecurity'] = "-";
					$kll['system'] = Language::get("classified");
				}
				else
				{
					$kll['systemsecurity'] = $kill->getSolarSystemSecurity();
					$kll['system'] = $kill->getSolarSystemName();
				}
				$kll["id"] = $kill->getID();
				$kll["victim"] = $kill->getVictimName();
				$kll["victimid"] = $kill->getVictimID();
				$kll["victimimage"] = $plt->getPortraitURL(64);
				$kll["victimship"] = $kill->getVictimShipName();
				$kll["victimshipid"] = $kill->getVictimShipExternalID();
				$kll["victimshipimage"] = $kill->getVictimShipImage(64);
				$kll["victimshipclass"] = $kill->getVictimShipClassName();
				$kll["victimcorp"] = $kill->getVictimCorpName();
				$kll["victimcorpid"] = $kill->getVictimCorpID();
				
				if ((int) number_format($kill->getISKLoss(), 0, "","")>1000000000)
				{
					$kll["isklost"] = number_format($kill->getISKLoss()/1000000000, 2, ".","") . " Billion";
				}
				elseif ((int) number_format($kill->getISKLoss(), 0, "","")>1000000)
				{
					$kll["isklost"] = number_format($kill->getISKLoss()/1000000, 2, ".","") . " Million";
				}
				else
				{
					$kll["isklost"] = number_format($kill->getISKLoss(), 0, ".",",");
				}
				
				if (config::get("mostexp_allianceid") && $kill->getVictimAllianceID() == config::get("mostexp_allianceid"))
				{
					$kll["class"] = "kl-loss";
					$kll["classlink"] = '<font color="#AA0000">&bull;</font>';
				}
				elseif (config::get("mostexp_corpid") && $kill->getVictimCorpID() == config::get("mostexp_corpid"))
				{
					$kll["class"] = "kl-loss";
					$kll["classlink"] = '<font color=\"#AA0000\">&bull;</font>';
				}
				elseif (config::get("mostexp_pilotid") && $kill->getVictimID() == config::get("mostexp_pilotid"))
				{
					$kll["class"] = "kl-loss";
					$kll["classlink"] = '<font color="#AA0000">&bull;</font>';
				}
				else
				{
					$kll["class"] = "kl-kill";
					$kll["classlink"] = '<font color="#00AA00">&bull;</font>';
				}
				
				$kills[] = $kll;
			}
			
			$pods = array();
			while ($pod = $plist->getKill())
			{
				$pll = array();
				$plt = new Pilot($pod->getVictimID());
				if ($pod->isClassified() && !Session::isAdmin()) {
					$pll['systemsecurity'] = "-";
					$pll['system'] = Language::get("classified");
				}
				else
				{
					$pll['systemsecurity'] = $pod->getSolarSystemSecurity();
					$pll['system'] = $pod->getSolarSystemName();
				}
				$pll["id"] = $pod->getID();
				$pll["victim"] = $pod->getVictimName();
				$pll["victimid"] = $pod->getVictimID();
				$pll["victimimage"] = $plt->getPortraitURL(64);
				$pll["victimship"] = $pod->getVictimShipName();
				$pll["victimshipid"] = $pod->getVictimShipExternalID();
				$pll["victimshipimage"] = $pod->getVictimShipImage(64);
				$pll["victimshipclass"] = $pod->getVictimShipClassName();
				$pll["victimcorp"] = $pod->getVictimCorpName();
				$pll["victimcorpid"] = $pod->getVictimCorpID();
				
				if ((int) number_format($pod->getISKLoss(), 0, "","")>1000000000)
				{
					$pll["isklost"] = number_format($pod->getISKLoss()/1000000000, 2, ".","") . " Billion";
				}
				elseif ((int) number_format($pod->getISKLoss(), 0, "","")>1000000)
				{
					$pll["isklost"] = number_format($pod->getISKLoss()/1000000, 2, ".","") . " Million";
				}
				else
				{
					$pll["isklost"] = number_format($pod->getISKLoss(), 0, ".",",");
				}
				
				if (config::get("mostexp_allianceid") && $pod->getVictimAllianceID() == config::get("mostexp_allianceid"))
				{
					$pll["class"] = "kl-loss";
					$pll["classlink"] = '<font color="#AA0000">&bull;</font>';
				}
				elseif (config::get("mostexp_corpid") && $pod->getVictimCorpID() == config::get("mostexp_corpid"))
				{
					$pll["class"] = "kl-loss";
					$pll["classlink"] = '<font color="#AA0000">&bull;</font>';
				}
				elseif (config::get("mostexp_pilotid") && $pod->getVictimID() == config::get("mostexp_pilotid"))
				{
					$pll["class"] = "kl-loss";
					$pll["classlink"] = '<font color="#AA0000">&bull;</font>';
				}
				else
				{
					$pll["class"] = "kl-kill";
					$pll["classlink"] = '<font color="#00AA00">&bull;</font>';
				}
				
				$pods[] = $pll;
			}
			
			$smarty->assign("killlist", $kills);
			$smarty->assign("width", 100/config::get("mostexp_count"));
			$smarty->assign("widthpods", 100/config::get("mostexp_count_pods"));
			$smarty->assign("podlist", $pods);
			
			return $smarty->fetch(getcwd() . "/mods/mostexp/tpl/mostexpensive.tpl");
	
		}
		public static function setTime($week = 0, $year = 0, $month = 0)
		{
			if ($week)
			{
				$w = $week;
			}
			else
			{
				$w = (int) kbdate("W");
			}
			if ($month)
			{
				$m = $month;
			}
			else
			{
				$m = (int) kbdate("m");
			}
			if ($year)
			{
				$y = $year;
			}
			else
			{
				$y = (int) kbdate("o");
			}
			if ($m < 10) $m = "0" . $m;
			if ($w < 10) $w = "0" . $w;
			self::$year = $y;
			self::$month = $m;
			self::$week = $w;
		}
	}
