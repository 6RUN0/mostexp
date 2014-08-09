<div class="block-header2 mostexp-header">Most expensive <strong>{$displaytype}</strong> for {$displaylist}.</div>
{if $killlist}
<table class="kb-table mostexp-table"><tbody><tr>
  {foreach from=$killlist item=k}
  <td width="{$width}%">
    <table class="mostexp-table-item"><tbody>
      <tr class="kb-table-header"><td align="center" class="kb-table-cell">
        {$k.classlink}&nbsp;<a class="kb-shipclass" href="{$k.details}">{$k.name|truncate:15}</a>
      </td></tr>
      <tr class="mostexp-row">
        <td class="kb-table-cell" onmouseover="javascript:swap('name-{$k.id}-ship','kb-table-row-hover','name-{$k.id}-sys','kb-table-row-hover');" onmouseout="javascript:swap('name-{$k.id}-ship','kb-table-row-odd','name-{$k.id}-sys','kb-table-row-even');" onclick="window.location.href='{$k.kill_detail}';">
          <table class="mostexp-table-item"><tbody>
            <tr class="kb-table-row-odd" id="name-{$k.id}-ship">
              <td class="mostexp-cell-img" rowspan="2">{$k.img}</td>
              <td class="kb-table-cell mostexp-cell-txt" ><strong>{$k.shipname|truncate:15}</strong></td>
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
          </tbody></table>
        </td>
      </tr>
      <tr class="kb-table-row-odd"><td class="kb-table-cell {$k.class}">
        <strong>{$k.isklost}</strong> ISK
      </td></tr>
    </tbody></table>
  </td>
  {/foreach}
</tr></tbody></table>
{else}
<p>No Data.</p>
{/if}
