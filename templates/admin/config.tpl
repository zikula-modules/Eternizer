{* purpose of this template: module configuration *}
{include file='admin/header.tpl'}
{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='jquery-ui'}
<div class="eternizer-config">
    {gt text='Settings' assign='templateTitle'}
    {pagesetvar name='title' value=$templateTitle}
    <div class="z-admin-content-pagetitle">
        {icon type='config' size='small' __alt='Settings'}
        <h3>{$templateTitle}</h3>
    </div>

    {form cssClass='z-form'}
    {eternizerFormFrame}
    {formsetinitialfocus inputId='pagesize'}
    <fieldset>
        <legend>{gt text='Here you can manage all basic settings for this application.'}</legend>
        <div class="z-formrow">
            {formlabel for='pagesize' __text='Items per page'}
            {formintinput id='pagesize' group='config' maxLength=255 __title='Enter this setting. Only digits are allowed.'}
        </div>
        <div class="z-formrow">
            {formlabel for='mail' __text='Mail'}
            {formemailinput id='mail' group='config' maxLength=255 __title='Enter this setting.'}
            <div class="z-informationmsg z-formnote">{gt text="Notice: If you enter a correct email address a message will be sent to when a new entry was saved. If you leave this field empty, it is disabled."}</div>
        </div>
        <div class="z-formrow">
            {formlabel for='order' __text='Order'}
            {formdropdownlist id='order' group='config'}
            <div class="z-informationmsg z-formnote">{gt text="Notice: Here you enter the standard order in backend and frontend. In backend you are able to sort the columns over using the links."}</div>
        </div>
        <div class="z-formrow">
            {formlabel for='moderate' __text='Moderate'}
            {formdropdownlist id='moderate' group='config'}
        </div>
        <div class="z-formrow">
            {formlabel for='formposition' __text='Formposition'}
            {formdropdownlist id='formposition' group='config'}
        </div>
        <div class="z-formrow">
            {formlabel for='ipsave' __text='Save Ip?'}
            {formcheckbox id='ipsave' group='config'}
        </div>
        <div class="z-formrow">
            {formlabel for='editentries' __text='Edit own entries?'}
            {formcheckbox id='editentries' group='config'}
        </div>
        <div id="periodoption" style="display: none;">
        <div class="z-formrow">
        {gt text='Enter the hours, an entry is editable for users' assign='toolTip'}
            {formlabel for='period' __text='Period' class='eternizerFormTooltips' title=$toolTip}
            {formintinput id='period' group='config' maxLength=255 __title='Enter this setting. Only digits are allowed.'}
        </div>
        </div>
        <div class="z-formrow">
            {formlabel for='simplecaptcha' __text='Use simple captcha?'}
            {formcheckbox id='simplecaptcha' group='config'}
        </div>
    </fieldset>

    <div class="z-buttons z-formbuttons">
        {formbutton commandName='save' __text='Update configuration' class='z-bt-save'}
        {formbutton commandName='cancel' __text='Cancel' class='z-bt-cancel'}
    </div>
    {/eternizerFormFrame}
    {/form}
</div>
<script type="text/javascript" charset="utf-8">
/* <![CDATA[ */
             
    var MU = jQuery.noConflict();
    MU(document).ready(function() {
        if(MU(".z-formrow > #editentries").is(':checked')) {
        MU("#periodoption").css({display: 'block'});
        }

        
    	MU(".z-formrow > #editentries").click( function() {
        if(MU(this).is(':checked')) {
        	MU("#periodoption").slideDown('slow');
        }
        else {
        	MU("#periodoption").slideUp('slow');
        }

    });

    	MU(".z-formrow > #uploadFiles").click( function() {
            if(MU(this).is(':checked')) {
            	MU("#fileoptions").slideDown('slow');
            }
            else {
            	MU("#fileoptions").slideUp('slow');
            }

        }); 

    	MU(".z-formrow > #editPostings").click( function() {
            if(MU(this).is(':checked')) {
            	MU("#editoptions").slideDown('slow');
            }
            else {
            	MU("#editoptions").slideUp('slow');
            }

        });        
    });


/* ]]> */
</script>
{include file='admin/footer.tpl'}
