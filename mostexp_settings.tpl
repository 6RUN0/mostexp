<div class="block-header2">Settings</div>
{if !empty($mostexp_msg.text)}
  <div class="mostexp-msg">{$mostexp_msg.text}</div>
{/if}
<form name="settings[]" id="settings" method="post">
  <table class="kb-subtable">
    <tr>
      <td width="160"><strong>Display Mode</strong></td>
      <td><select name="settings[display]">
            <option value="board" {if $mostexp_options.display == 'board'}selected{/if}>Use Killboard settings</option>
            <option value="days" {if $mostexp_options.display == 'days'}selected{/if}>Day period</option>
          </select>
      </td>
    </tr>
    <tr>
      <td width="160"></td>
      <td></td>
    </tr>
    <tr>
      <td width="160"><strong>Day period</strong></td>
      <td><input {if isset($mostexp_msg.period)}class="{$mostexp_msg.period}" {/if}type="text" name="settings[period]" value="{$mostexp_options.period}" maxlength="80" size="4" /></td>
    </tr>
    <tr>
      <td width="160"></td>
      <td></td>
    </tr>
    <tr>
      <td width="160"><strong>Kill Count</strong></td>
      <td><input {if isset($mostexp_msg.count)}class="{$mostexp_msg.count}" {/if}type="text" name="settings[count]" value="{$mostexp_options.count}" maxlength="80" size="4" /></td>
    </tr>
    <tr>
      <td width="160"></td>
      <td></td>
    </tr>
    <tr>
      <td width="160"><strong>Show Pods</strong></td>
      <td><input type="checkbox" name="settings[viewpods]" value="yes" {if $mostexp_options.viewpods == 'yes'}checked{/if}></td>
    </tr>
    <tr>
      <td width="160"></td>
      <td></td>
    </tr>
    <tr>
      <td width="160"><strong>Pods Day period</strong></td>
      <td><input {if isset($mostexp_msg.periodpods)}class="{$mostexp_msg.periodpods}" {/if}type="text" name="settings[periodpods]" value="{$mostexp_options.periodpods}" maxlength="80" size="4" /></td>
    </tr>
    <tr>
      <td width="160"></td>
      <td></td>
    </tr>
    <tr>
      <td width="160"><strong>Pods Count</strong></td>
      <td><input {if isset($mostexp_msg.countpods)}class="{$mostexp_msg.countpods}" {/if}type="text" name="settings[countpods]" value="{$mostexp_options.countpods}" maxlength="80" size="4" /></td>
    </tr>
    <tr>
      <td width="160"><strong>Kill Type</strong></td>
      <td>
        <select name="settings[what]">
          <option value="kill" {if $mostexp_options.what == 'kill'}selected{/if}>Kills only</option>
          <option value="combined" {if $mostexp_options.what == 'combined'}selected{/if}>Kills and Losses</option>
        </select>
      </td>
    </tr>
    <tr>
      <td width="160"><strong>Display Position</strong></td>
      <td>
        <select name="settings[position]">
          <option value="start" {if $mostexp_options.position == 'start'}selected{/if}>On Top</option>
          <option value="summaryTable" {if $mostexp_options.position == 'summaryTable'}selected{/if}>After Summary Table</option>
          <option value="campaigns" {if $mostexp_options.position == 'campaigns'}selected{/if}>After Caimpaigns</option>
          <option value="contracts" {if $mostexp_options.position == 'contracts'}selected{/if}>After Contracts</option>
          <option value="killList"  {if $mostexp_options.position == 'killList'}selected{/if}>At Bottom</option>
        </select>
      </td>
    </tr>
    <tr>
      <td width="160"><strong>Show only verified</strong></td>
      <td><input type="checkbox" name="settings[only_verified]" value="yes" {if $mostexp_options.only_verified == 'yes'}checked{/if}>
      </td>
    </tr>
    <tr>
      <td width="160"></td>
      <td><input type="submit" name="submit" value="Save" /></td>
    </tr>
  </table>
</form>
<div class="block-header2"></div>
<div style="text-align: right;">Most Expensive Kills 1.4p3 by <a href="http://babylonknights.com/">Khi3l</a>. Patched by <a href="https://github.com/6RUN0">boris_t</a>.</div> 
