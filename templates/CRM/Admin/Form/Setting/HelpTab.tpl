{*
 +--------------------------------------------------------------------------+
 | Copyright IT Bliss LLC (c) 2012-2013                                     |
 +--------------------------------------------------------------------------+
 | This program is free software: you can redistribute it and/or modify     |
 | it under the terms of the GNU Affero General Public License as published |
 | by the Free Software Foundation, either version 3 of the License, or     |
 | (at your option) any later version.                                      |
 |                                                                          |
 | This program is distributed in the hope that it will be useful,          |
 | but WITHOUT ANY WARRANTY; without even the implied warranty of           |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            |
 | GNU Affero General Public License for more details.                      |
 |                                                                          |
 | You should have received a copy of the GNU Affero General Public License |
 | along with this program.  If not, see <http://www.gnu.org/licenses/>.    |
 +--------------------------------------------------------------------------+
*}

{* this template is used for setting-up the Cividesk HelpTab extension *}
<div class="form-item">
  <fieldset>
    <legend>{ts}Setup{/ts}</legend>
    <div class="crm-block crm-form-block crm-cividesk-helptab-form-block">
      <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="top"}</div>
      <table class="form-layout-compressed">
        <tr class="crm-cividesk-helptab-form-block">
          <td class="label">{$form.cividesk_key.label}</td>
          <td>{$form.cividesk_key.html}</td>
        </tr>
      </table>
      <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="bottom"}</div>
    </div>
  </fieldset>
</div>

<style type="text/css">
  {literal}
  #crm-container .crm-error {
    padding: 0;
  }
  {/literal}
</style>
