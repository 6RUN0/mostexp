<?php
/**
 * $Id$
 *
 * @category Classes
 * @package  Mostexp
 * @author   Khi3l, boris_t <boris@talovikov.ru>
 * @license  http://opensource.org/licenses/MIT MIT
 */

/**
 * Render most expensive kills.
 */
class MostExpensive
{
    static $week;
    static $year;
    static $month;
    static $scl_id;
    static $view;

    static $options = array();
    static $monthly;

    static $all;
    static $corp;
    static $pilot;

    /**
     * Define self variables.
     *
     * @param pHome $pHome pHome object, see ../../common/home.php
     *
     * @return none
     */
    private static function _init($pHome)
    {
        self::$week = $pHome->getWeek();
        self::$year = $pHome->getYear();
        self::$month = $pHome->getMonth();
        self::$scl_id = edkURI::getArg('scl_id');
        self::$view = $pHome->getView();

        self::$options = config::get('mostexp_options');
        if (empty(self::$options)) {
            self::$options = array(
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
        }

        self::$monthly = config::get('show_monthly');
        self::$all = config::get('cfg_allianceid');
        self::$corp = config::get('cfg_corpid');
        self::$pilot = config::get('cfg_pilotid');
    }

    /**
     * Display most extensive kills.
     *
     * @param pHome $pHome pHome object, see ../../common/home.php
     *
     * @return string html
     */
    public static function display($pHome)
    {
        self::_init($pHome);

        $result = '';
        $result .= self::render(self::$options['period'], self::$options['count']);
        if (self::$options['viewpods'] == 'yes') {
            $result .= self::render(self::$options['periodpods'], self::$options['countpods'], true);
        }
        return $result;
    }

    /**
     * Rendering template 'mostexpensive.tpl'.
     *
     * @param string|int $period period of kills
     * @param string|int $count  count of kills
     * @param bool       $pod    show pods
     *
     * @return string html
     */
    static function render($period, $count, $pod = false)
    {
        global $smarty;

        $klist = new KillList();
        $klist->setOrdered(true);
        $klist->setOrderBy('kll_isk_loss DESC');
        if (self::$options['only_verified'] == 'yes') {
            $klist->setAPIKill(true);
        }
        $prefix = '';
        if ($pod) {
            self::$scl_id = 2;
            $prefix = 'Pod ';
        }
        // If capsule, noob ship or shuttle
        if (self::$scl_id == 2 || self::$scl_id == 3 || self::$scl_id == 11) {
            $pod = true;
        }
        if (self::$scl_id) {
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
            if (self::$monthly) {
                $start = makeStartDate(0, self::$year, self::$month);
                $end = makeEndDate(0, self::$year, self::$month);
                $klist->setStartDate(gmdate('Y-m-d H:i', $start));
                $klist->setEndDate(gmdate('Y-m-d H:i', $end));
                $date = date_format(date_create(self::$year . '-' . self::$month), 'F, Y');
                $smarty->assign('displaylist', $date);
            } else {
                $klist->setWeek(self::$week);
                $klist->setYear(self::$year);
                $date = 'Week ' . self::$week . ', ' . self::$year;
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
            } else {
                $kll['systemsecurity'] = $kill->getSolarSystemSecurity();
                $kll['system'] = $kill->getSolarSystemName();
            }
            $kll['id'] = $kill->getID();
            $kll['kill_detail'] = edkURI::page('kill_detail', $kll['id'], 'kll_id');
            $kll['name'] = $kill->getVictimName();
            $kll['details'] = $plt->getDetailsURL();
            $kll['shipname'] = $kill->getVictimShipName();
            $isk_loss = $kill->getISKLoss();
            if (self::$scl_id == 2) {
                $kll['img'] = '<img class="mostexp-img" src="' . $plt->getPortraitURL(64) . '" alt="' . $kll['name'] . '"/>';
            } else {
                $kll['img'] = '<img class="mostexp-img" src="' . $kill->getVictimShipImage(64) . '" alt="' . $kll['shipname'] . '"/>';
            }
            if ($isk_loss > 1000000000) {
                $kll['isklost'] = self::_format($isk_loss/1000000000) . ' Billion';
            } elseif ($isk_loss > 1000000) {
                $kll['isklost'] = self::_format($isk_loss/1000000) . ' Million';
            } else {
                $kll['isklost'] = self::_format($isk_loss, 0);
            }

            if (in_array($kill->getVictimAllianceID(), self::$all) || in_array($kill->getVictimCorpID(), self::$corp) || in_array($kill->getVictimID(), self::$pilot)) {
                $kll['class'] = 'kl-loss';
                $kll['classlink'] = '<span class="mostexp-loss">&bull;</span>';
            } else {
                $kll['class'] = 'kl-kill';
                $kll['classlink'] = '<span class="mostexp-kill">&bull;</span>';
            }
            $kills[] = $kll;
        }
        $smarty->assign('killlist', $kills);
        $smarty->assign('width', 100/$count);
        return $smarty->fetch(get_tpl('./mods/mostexp/mostexpensive'));
    }

    /**
     * Format a number.
     *
     * @param float $num the number being formatted
     * @param int   $dec sets the number of decimal points
     *
     * @return string
     */
    private static function _format($num, $dec = 2)
    {
        return number_format($num, $dec, '.', '');
    }

}
