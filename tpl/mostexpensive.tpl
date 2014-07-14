<div class="kb-mostexpensive-header">Most expensive <strong>{$displaytype}</strong> for {$displaylist}.</div>
{literal} 
<script type="text/javascript" language="javascript">
//<![CDATA[
function swap(s,w) {
	var d = document;
	var e = d.getElementById(s);
	e.className = w;
}
//]]>
</script> 
{/literal}
{if $killlist}
<table class="kb-table" style="width: 100%; text-align: left;" cellspacing="1">
	<tr class="kb-table-header"> {foreach from=$killlist item=k}
		<td align="center" width="{$width}%" class="kb-table-cell">{$k.classlink}&nbsp;<a href="?a=pilot_detail&amp;plt_id={$k.victimid}" class="mostexplink">{$k.victim}</a></td>
		{/foreach} </tr>
	<tr style="cursor: pointer;"> {foreach from=$killlist item=k}
		<td class="kb-table-cell" style="padding: 0" onmouseover="javascript:swap('name-{$k.id}-ship','kb-table-row-hover','name-{$k.id}-sys','kb-table-row-hover');" onmouseout="javascript:swap('name-{$k.id}-ship','kb-table-row-odd','name-{$k.id}-sys','kb-table-row-even');" onclick="window.location.href='?a=kill_detail&amp;kll_id={$k.id}';"><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" style="border: 0; border-collapse: 0; border-spacing: 0">
				<tr class="kb-table-row-odd" id="name-{$k.id}-ship">
					<td width="64" height="64" rowspan="2" valign="top" style="padding: 1px"><img src="{$k.victimshipimage}" border="0" width="64" height="64" alt="{$k.victimship}"/></td>
					<td height="32" class="kb-table-cell" align="center"><strong>{$k.victimship}</strong></td>
				</tr>
				<tr class="kb-table-row-even" id="name-{$k.id}-sys">
					<td align="center"><strong>{$k.system|truncate:10}</strong><br />
						{if $k.systemsecurity < 0.05}
						(<span class="system-null">{$k.systemsecurity|max:0|string_format:"%01.1f"}</span>)
						{elseif $k.systemsecurity < 0.45}
						(<span class="system-low">{$k.systemsecurity|max:0|string_format:"%01.1f"}</span>)
						{else}
						(<span class="system-high">{$k.systemsecurity|max:0|string_format:"%01.1f"}</span>){/if}</td>
				</tr>
			</table></td>
		{/foreach} </tr>
	<tr class="kb-table-row-odd"> {foreach from=$killlist item=k}
		<td class="kb-table-cell {$k.class}" align="center" style="font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif; font-size: 12px;"><strong>{$k.isklost}</strong> ISK</td>
		{/foreach} </tr>
</table>
{else}
<p>No Data.</p>
{/if}
{if $config->get('mostexp_viewpods') == "yes"}
<div class="kb-mostexpensive-header">Most expensive <strong>Pod {$displaytype}</strong> for {$displaylist}.</div>
{if $podlist}
<table class="kb-table" style="width: 100%; text-align: left;" cellspacing="1">
	<tr class="kb-table-header"> {foreach from=$podlist item=p}
		<td align="center" width="{$widthpods}%" class="kb-table-cell">{$p.classlink}&nbsp;<a href="?a=pilot_detail&amp;plt_id={$p.victimid}" class="mostexplink">{$p.victim}</a></td>
		{/foreach} </tr>
	<tr style="cursor: pointer;"> {foreach from=$podlist item=p}
		<td class="kb-table-cell" style="padding: 0" onmouseover="javascript:swap('name-{$p.id}-ship','kb-table-row-hover','name-{$p.id}-sys','kb-table-row-hover');" onmouseout="javascript:swap('name-{$p.id}-ship','kb-table-row-odd','name-{$p.id}-sys','kb-table-row-even');" onclick="window.location.href='?a=kill_detail&amp;kll_id={$p.id}';"><table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" style="border: 0; border-collapse: 0; border-spacing: 0">
				<tr class="kb-table-row-odd" id="name-{$p.id}-ship">
					<td width="64" height="64" rowspan="2" valign="top" style="padding: 1px"><img src="{$p.victimimage}" border="0" width="64" height="64" alt="{$p.victimimage}"/></td>
					<td height="32" class="kb-table-cell" align="center"><strong>{$p.victimship}</strong></td>
				</tr>
				<tr class="kb-table-row-even" id="name-{$p.id}-sys">
					<td align="center"><strong>{$p.system|truncate:10}</strong><br />
						{if $p.systemsecurity < 0.05}
						(<span class="system-null">{$p.systemsecurity|max:0|string_format:"%01.1f"}</span>)
						{elseif $p.systemsecurity < 0.45}
						(<span class="system-low">{$p.systemsecurity|max:0|string_format:"%01.1f"}</span>)
						{else}
						(<span class="system-high">{$p.systemsecurity|max:0|string_format:"%01.1f"}</span>){/if}</td>
				</tr>
			</table></td>
		{/foreach} </tr>
	<tr class="kb-table-row-odd"> {foreach from=$podlist item=p}
		<td class="kb-table-cell {$p.class}" align="center" style="font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif; font-size: 12px;"><strong>{$p.isklost}</strong> ISK</td>
		{/foreach} </tr>
</table>
{else}
<p>No Data.</p>
{/if}
{/if}
