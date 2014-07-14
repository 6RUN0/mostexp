<?php
	if(!defined('KB_SITE')) die ("Go Away!");

	require_once("common/admin/admin_menu.php");
	$module = "Most Expensive Kills";
	$page = new Page("$module");
	$version = "1.4";
	$versiondb = config::get('mostexp_ver');
	$display = config::get('mostexp_display');
	if ($version != $versiondb)
	{
			config::set("mostexp_ver", $version);
			config::set("mostexp_display", "board");
			config::set("mostexp_what", "kill");
			config::set("mostexp_viewpods", "yes");
			config::set("mostexp_position", "summaryTable");
			config::set("mostexp_period", "7");
			config::set("mostexp_period_pods", "7");
			config::set("mostexp_count", "5");
			config::set("mostexp_count_pods", "5");
			config::set("mostexp_allianceid", "0");
			config::set("mostexp_corpid", "0");
			config::set("mostexp_pilotid", "0");
			$html .= "<div><strong>Version Updated!</strong></div>";
	}
	if (empty($display))
	{
			config::set("mostexp_display", "board");
			config::set("mostexp_what", "kill");
			config::set("mostexp_viewpods", "yes");
			config::set("mostexp_position", "summaryTable");
			config::set("mostexp_period", "7");
			config::set("mostexp_period_pods", "7");
			config::set("mostexp_count", "5");
			config::set("mostexp_count_pods", "5");
			config::set("mostexp_allianceid", "0");
			config::set("mostexp_corpid", "0");
			config::set("mostexp_pilotid", "0");
			$html .= "<div><strong><em>First run</em>. Loaded default values!</strong></div>";
	}
	if ($_SERVER['REQUEST_METHOD'] == "POST")
	{
			$display	= (string) $_POST["display"];
			$what	= (string) $_POST["what"];
			$viewpods	= (isset($_POST["viewpods"])==true) ? "yes" : "no";
			$position	= (string) $_POST["position"];
			$period	= (int) $_POST["period"];
			$periodpods	= (int) $_POST["periodpods"];
			$count	= (int) $_POST["count"];
			$countpods	= (int) $_POST["countpods"];
			$allianceid	= (int) $_POST["allianceid"];
			$corpid	= (int) $_POST["corpid"];
			$pilotid	= (int) $_POST["pilotid"];
			config::set("mostexp_display", $display);
			config::set("mostexp_what", $what);
			config::set("mostexp_viewpods", $viewpods);
			config::set("mostexp_position", $position);
			config::set("mostexp_period", $period);
			config::set("mostexp_period_pods", $periodpods);
			config::set("mostexp_count", $count);
			config::set("mostexp_count_pods", $countpods);
			config::set("mostexp_allianceid", $allianceid);
			config::set("mostexp_corpid", $corpid);
			config::set("mostexp_pilotid", $pilotid);
			$html .= "<div><strong>Settings Updated.</strong></div>";
	}
	
	$display	= config::get("mostexp_display");
	$what	= config::get("mostexp_what");
	$viewpods	= config::get("mostexp_viewpods");
	$position	= config::get("mostexp_position");
	$period	= config::get("mostexp_period");
	$periodpods	= config::get("mostexp_period_pods");
	$count	= config::get("mostexp_count");
	$countpods	= config::get("mostexp_count_pods");
	$allianceid	= config::get("mostexp_allianceid");
	$corpid	= config::get("mostexp_corpid");
	$pilotid	= config::get("mostexp_pilotid");
	
	$html .=<<<HTML
	<div class="block-header2">Settings</div>
	<form name="update" id="update" method="post">
	<table class="kb-subtable">
		<tr>
			<td width="160"><strong>Display Mode</strong></td>
			<td><select name="display">
HTML;

	$html .= "<option value=\"board\"";
	$html .= ($display == "board") ? " selected>" : ">" ;
	$html .= "Use Killboard settings</option>";

	$html .= "<option value=\"days\"";
	$html .= ($display == "days") ? " selected>" : ">" ;
	$html .= "Day period</option>";
	
	$html .=<<<HTML
			</select></td>
		</tr>
		<tr>
			<td width="160"></td>
			<td></td>
		</tr>
		<tr>
			<td width="160"><strong>Alliance ID (0 if not alliance board)</strong></td>
			<td><input type="text" name="allianceid" value="$allianceid" maxlength="80" size="4" /></td>
		</tr>
		<tr>
HTML;
	$html .=<<<HTML
			</td>
		</tr>
		<tr>
			<td width="160"></td>
			<td></td>
		</tr>
		<tr>
			<td width="160"><strong>Corp ID (0 if not corporation board)</strong></td>
			<td><input type="text" name="corpid" value="$corpid" maxlength="80" size="4" /></td>
		</tr>
		<tr>
HTML;
	$html .=<<<HTML
			</td>
		</tr>
		<tr>
			<td width="160"></td>
			<td></td>
		</tr>
		<tr>
			<td width="160"><strong>Pilot ID (0 if not pilot board)</strong></td>
			<td><input type="text" name="pilotid" value="$pilotid" maxlength="80" size="4" /></td>
		</tr>
		<tr>
HTML;
	$html .=<<<HTML
			</td>
		</tr>
		<tr>
			<td width="160"></td>
			<td></td>
		</tr>
		<tr>
			<td width="160"><strong>Day period</strong></td>
			<td><input type="text" name="period" value="$period" maxlength="80" size="4" /></td>
		</tr>
		<tr>
HTML;
	$html .=<<<HTML
			</td>
		</tr>
		<tr>
			<td width="160"></td>
			<td></td>
		</tr>
		<tr>
			<td width="160"><strong>Kill Count</strong></td>
			<td><input type="text" name="count" value="$count" maxlength="80" size="4" /></td>
		</tr>
		<tr>
			<td width="160"></td>
			<td></td>
		</tr>
		<tr>
			<td width="160"><strong>Show Pods</strong></td>
			<td><input type="checkbox" name="viewpods" value="yes"
HTML;
	$html .= ($viewpods == "yes") ? " checked>" : ">" ;
	$html .=<<<HTML
	</td>
		</tr>
		<tr>
HTML;
	$html .=<<<HTML
			</td>
		</tr>
		<tr>
			<td width="160"></td>
			<td></td>
		</tr>
		<tr>
			<td width="160"><strong>Pods Day period</strong></td>
			<td><input type="text" name="periodpods" value="$periodpods" maxlength="80" size="4" /></td>
		</tr>
		<tr>
HTML;
	$html .=<<<HTML
			</td>
		</tr>
		<tr>
			<td width="160"></td>
			<td></td>
		</tr>
		<tr>
			<td width="160"><strong>Pods Count</strong></td>
			<td><input type="text" name="countpods" value="$countpods" maxlength="80" size="4" /></td>
		</tr>
		<tr>
			<td width="160"><strong>Kill Type</strong></td>
			<td><select name="what">
HTML;

	$html .= "<option value=\"kill\"";
	$html .= ($what == "kill") ? " selected>" : ">" ;
	$html .= "Kills only</option>";

	$html .= "<option value=\"combined\"";
	$html .= ($what == "combined") ? " selected>" : ">" ;
	$html .= "Kills and Losses</option>";
	
	$html .=<<<HTML
			</select></td>
		</tr>
		<tr>
			<td width="160"><strong>Display Position</strong></td>
			<td><select name="position">
HTML;

	$html .= "<option value=\"start\"";
	$html .= ($position == "start") ? " selected>" : ">" ;
	$html .= "On Top</option>";
	
	$html .= "<option value=\"summaryTable\"";
	$html .= ($position == "summaryTable") ? " selected>" : ">" ;
	$html .= "After Summary Table</option>";
	
	$html .= "<option value=\"campaigns\"";
	$html .= ($position == "campaigns") ? " selected>" : ">" ;
	$html .= "After Caimpaigns</option>";
	
	$html .= "<option value=\"contracts\"";
	$html .= ($position == "contracts") ? " selected>" : ">" ;
	$html .= "After Contracts</option>";
	
	$html .= "<option value=\"killList\"";
	$html .= ($position == "killList") ? " selected>" : ">" ;
	$html .= "At Bottom</option>";
	
	$html .=<<<HTML
			</select></td>
		</tr>
	</table>
HTML;

	$html .=<<<HTML
	<div class="block-header2">Save changes</div>
	<table class="kb-subtable">
		<tr>
			<td width="160"></td>
			<td><input type="submit" name="submit" value="Save" /></td>
		</tr>
	</table>
	</form>
HTML;
	$html .= "<div style=\"padding: 5px; margin: 20px 10px 10px; text-align: right; border-top: 1px solid #ccc\">$module $version by <a href=\"http://babylonknights.com/\">Khi3l</a>.</div>";
	$page->setContent($html);
	$page->addContext($menubox->generate());
	$page->generate();
?>
