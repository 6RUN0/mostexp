<div class="mostexp-header">Most expensive <strong>{$displaytype}</strong> for {$displaylist}.</div>
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
<table class="kb-table mostexp-table">
  <tr class="kb-table-header"> {foreach from=$killlist item=k}
    <td align="center" width="{$width}%" class="kb-table-cell">{$k.classlink}&nbsp;<a class="kb-shipclass" href="{$k.victimdetails}">{$k.victim}</a></td>
    {/foreach} </tr>
  <tr class="mostexp-row"> {foreach from=$killlist item=k}
    <td class="kb-table-cell mostexp-cell" onmouseover="javascript:swap('name-{$k.id}-ship','kb-table-row-hover','name-{$k.id}-sys','kb-table-row-hover');" onmouseout="javascript:swap('name-{$k.id}-ship','kb-table-row-odd','name-{$k.id}-sys','kb-table-row-even');" onclick="window.location.href='{$k.kill_detail}';"><table class="mostexp-table-item">
        <tr class="kb-table-row-odd" id="name-{$k.id}-ship">
          <td class="mostexp-cell-img" rowspan="2"><img class="mostexp-img" src="{$k.victimshipimage}" alt="{$k.victimship}"/></td>
          <td class="kb-table-cell mostexp-cell-txt" ><strong>{$k.victimship}</strong></td>
        </tr>
        <tr class="kb-table-row-even" id="name-{$k.id}-sys">
          <td class="mostexp-cell-txt"><strong>{$k.system|truncate:10}</strong><br />
            <span class="mostexp-small">
              {if $k.systemsecurity < 0.05}
              (<span class="mostexp-nullsec">{$k.systemsecurity|max:0|string_format:"%01.1f"}</span>)
              {elseif $k.systemsecurity < 0.45}
              (<span class="mostexp-lowsec">{$k.systemsecurity|max:0|string_format:"%01.1f"}</span>)
              {else}
              (<span class="mostexp-highsec">{$k.systemsecurity|max:0|string_format:"%01.1f"}</span>){/if}
            </span>
          </td>
        </tr>
      </table></td>
    {/foreach} </tr>
  <tr class="kb-table-row-odd"> {foreach from=$killlist item=k}
    <td class="kb-table-cell {$k.class}"><strong>{$k.isklost}</strong> ISK</td>
    {/foreach} </tr>
</table>
{else}
<p>No Data.</p>
{/if}
{if $config->get('mostexp_viewpods') == "yes"}
<div class="mostexp-header">Most expensive <strong>Pod {$displaytype}</strong> for {$displaylist}.</div>
{if $podlist}
<table class="kb-table mostexp-table">
  <tr class="kb-table-header"> {foreach from=$podlist item=p}
    <td align="center" width="{$widthpods}%" class="kb-table-cell">{$p.classlink}&nbsp;<a class="kb-shipclass" href="{$p.victimdetails}">{$p.victim}</a></td>
    {/foreach} </tr>
  <tr class="mostexp-row"> {foreach from=$podlist item=p}
    <td class="kb-table-cell mostexp-cell" onmouseover="javascript:swap('name-{$p.id}-ship','kb-table-row-hover','name-{$p.id}-sys','kb-table-row-hover');" onmouseout="javascript:swap('name-{$p.id}-ship','kb-table-row-odd','name-{$p.id}-sys','kb-table-row-even');" onclick="window.location.href='{$p.kill_detail}';"><table class="mostexp-table-item">
        <tr class="kb-table-row-odd" id="name-{$p.id}-ship">
          <td class="mostexp-cell-img" rowspan="2"><img class="mostexp-img" src="{$p.victimimage}" alt="{$p.victim}"/></td>
          <td class="kb-table-cell mostexp-cell-txt"><strong>{$p.victimship|truncate:20}</strong></td>
        </tr>
        <tr class="kb-table-row-even" id="name-{$p.id}-sys">
          <td class="mostexp-cell-txt"><strong>{$p.system|truncate:10}</strong><br />
            <span class="mostexp-small">
              {if $p.systemsecurity < 0.05}
              (<span class="mostexp-nullsec">{$p.systemsecurity|max:0|string_format:"%01.1f"}</span>)
              {elseif $p.systemsecurity < 0.45}
              (<span class="mostexp-lowsec">{$p.systemsecurity|max:0|string_format:"%01.1f"}</span>)
              {else}
              (<span class="mostexp-highsec">{$p.systemsecurity|max:0|string_format:"%01.1f"}</span>){/if}
            </span>
          </td>
        </tr>
      </table></td>
    {/foreach} </tr>
  <tr class="kb-table-row-odd"> {foreach from=$podlist item=p}
    <td class="kb-table-cell {$p.class}"><strong>{$p.isklost}</strong> ISK</td>
    {/foreach} </tr>
</table>
{else}
<p>No Data.</p>
{/if}
{/if}
