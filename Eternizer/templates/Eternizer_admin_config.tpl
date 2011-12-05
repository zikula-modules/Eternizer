{* $Id$ *}
{pnajaxheader filename=profilelist.js dragdrop=1}

<script type="text/javascript">
    var eternizer_deleteconfirm = '{gt text="Confirm deletion?"}';
</script>

{if $init neq 1}
{include file="Eternizer_admin_menu.tpl"}
{else}
<h2>{gt text="Installation of Eternizer"}</h2>
{/if}

<h2>{gt text="Settings"}</h2>

{pnform cssClass="z-form"}
<div>
    {pnformvalidationsummary}

    {pnformtabbedpanelset}

    {pnformtabbedpanel __title='General'}
    <fieldset>
        <legend>{gt text="General"}</legend>
        <div class="z-formrow">
            {pnformlabel for='perpage' __text='Items per page'}
            {pnformintinput id='perpage' mandatory='1' minValue='1'}
        </div>

        <div class="z-formrow">
            {pnformlabel for='notifymail' __text='E-mail address to notify about new entries'}
            {pnformtextinput id='notifymail' maxLength='255'}
        </div>

        <div class="z-formrow">
            {pnformlabel for='order' __text='Order'}
            {pnformdropdownlist id='order' mandatory='1'}
        </div>

        <div class="z-formrow">
            {pnformlabel for='spammode' __text='Spam check'}
            {pnformdropdownlist id='spammode' mandatory='1'}
        </div>

        <div class="z-formrow">
            {pnformlabel for='moderate' __text='Moderate'}
            {pnformdropdownlist id='moderate'}
        </div>

        <div class="z-formrow">
            {pnformlabel for='useuserprofile' __text='Enforce use of profile data'}
            {pnformcheckbox id='useuserprofile'}
        </div>
    </fieldset>
    {/pnformtabbedpanel}

    {pnformtabbedpanel __title='Long words processing'}
    <fieldset>
        <legend>{gt text="Long words processing"}</legend>
        <div class="z-formrow">
            {pnformlabel for='wwaction' __text='Action'}
            {pnformdropdownlist id='wwaction'}
        </div>

        <div class="z-formrow">
            {pnformlabel for='wwlimit' __text='Maximal length'}
            {pnformintinput id='wwlimit'}
        </div>

        <div class="z-formrow">
            {pnformlabel for='wwshortto' __text='Length after processing'}
            {pnformintinput id='wwshortto'}
        </div>
    </fieldset>
    {/pnformtabbedpanel}

    {pnformtabbedpanel __title="Profile"}
    <fieldset class="z-linear">
        <legend>{gt text="Profile"}</legend>
        <div class="z-formrow">
            {pnformlabel for='titlefield' __text='Title field'}
            {pnformdropdownlist id='titlefield'}
        </div>
        <ul id="profiletable">
            <li class="profileheader z-clearfix">
                <span class="col2">{gt text="ID"}</span>
                <span class="col3">{gt text="Title"}</span>
                <span class="col4">{gt text="Type"}</span>
                <span class="col5">{gt text="Mandatory"}</span>
                <span class="col6">{gt text="Equivalent in Profile"}</span>
                <span class="col7">{gt text="Action"}</span>
            </li>
            {foreach from=$profile key="id" item="item"}
            <li id="profile_{$id}" class="{cycle values="z-odd,z-even"} z-sortable z-clearfix moveable">
                <span class="col2">{$id}</span>
                {pnformtextinput cssClass="z-hide" id="profile_`$id`_pos" group="profile" text=$item.pos maxLength='20'}
                {pnformtextinput cssClass="col3" id="profile_`$id`_title" group="profile" text=$item.title maxLength='255'}
                {pnformdropdownlist cssClass="col4" id="profile_`$id`_type" group="profile" selectedValue=$item.type items=$typeItems}
                {pnformdropdownlist cssClass="col5" id="profile_`$id`_mandatory" group="profile" selectedValue=$item.mandatory items=$mandatoryItems}
                {pnformdropdownlist cssClass="col6" id="profile_`$id`_profile" group="profile" selectedValue=$item.profile items=$profileItems}
                <span class="col7">
                    <a href="javascript:eternizer_profile_delete({$id});" class="deleterow">{pnimg modname='core' src='delete_table_row.gif' set='icons/extrasmall' __title="Delete row" __alt="Delete row"}</a>
                </span>
            </li>
            {/foreach}
            <li id="profile_n1" class="{cycle values="z-odd,z-even"} z-sortable z-clearfix moveable">
                <span class="col2">{gt text="New"}</span>
                {pnformtextinput cssClass="z-hide" id='profile_n1_pos' group="profile" maxLength='20'}
                {pnformtextinput cssClass="col3" id='profile_n1_title' group="profile" maxLength='255'}
                {pnformdropdownlist cssClass="col4" id='profile_n1_type' group="profile" items=$typeItems}
                {pnformdropdownlist cssClass="col5" id='profile_n1_mandatory' group="profile" items=$mandatoryItems}
                {pnformdropdownlist cssClass="col6" id='profile_n1_profile' group="profile" items=$profileItems}
                <span class="col7">&nbsp;</span>
            </li>
        </ul>
    </fieldset>
    {/pnformtabbedpanel}

    {/pnformtabbedpanelset}

    <div class="z-formbuttons">
        {if !$init}
        {pnformbutton commandName="update" __text="Update"}
        {pnformbutton commandName="cancel" __text="Cancel"}
        {else}
        {pnformbutton commandName="update" __text="Continue"}
        {/if}
    </div>

</div>
{/pnform}

{if $init neq 1}
{include file="Eternizer_admin_footer.tpl"}
{/if}
