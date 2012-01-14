{zdebug}{* purpose of this template: build the Form to edit an instance of entry *}
{if $formposition eq 'menue'}
	{include file='user/header.tpl'}
{/if}
{pageaddvar name='javascript' value='modules/Eternizer/javascript/Eternizer_editFunctions.js'}
{pageaddvar name='javascript' value='modules/Eternizer/javascript/Eternizer_validation.js'}

{if $mode eq 'edit'}
    {gt text='Edit entry' assign='templateTitle'}
{elseif $mode eq 'create'}
    {gt text='Create entry' assign='templateTitle'}
{else}
    {gt text='Edit entry' assign='templateTitle'}
{/if}
<div class="eternizer-entry eternizer-edit">
{pagesetvar name='title' value=$templateTitle}
<div class="z-frontendcontainer">
    <h2>{$templateTitle}</h2>
{form cssClass='z-form'}
    {* add validation summary and a <div> element for styling the form *}
    {eternizerFormFrame}
    
            {foreach from=$profile key=id item=item}
        <div class="z-formrow">
            {formlabel for="profile_$id" text=$item.title}
            {if $item.type eq 'mail'}
            {formemailinput id="profile_$id" group="profile" text=$item.value maxLength='255' mandatory=$item.mandatory readOnly=$item.readonly}
            {elseif $item.type eq 'url'}
            {formurlinput id="profile_$id" group="profile" text=$item.value maxLength='255' mandatory=$item.mandatory readOnly=$item.readonly}
            {else}
            {formtextinput id="profile_$id" group="profile" text=$item.value maxLength='255' mandatory=$item.mandatory readOnly=$item.readonly}
            {/if}
        </div>
        {/foreach}

    <fieldset>
        <legend>{gt text='Content'}</legend>
        <div class="z-formrow" style='visibility: hidden;'>
            {formlabel for='ip' __text='Ip'}
            {formtextinput group='entry' id='ip' mandatory=false readOnly=false __title='Input the ip of the entry' textMode='singleline' maxLength=15 cssClass=''}
        </div>
                <div class="z-formrow">
            {formlabel for='name' __text='Name'}
            {formtextinput group='entry' id='name' mandatory=false readOnly=false __title='Enter the name of the entry' textMode='singleline' maxLength=100 cssClass=''}
        </div>
        <div class="z-formrow">
            {formlabel for='email' __text='Email'}
            {formemailinput group='entry' id='email' mandatory=false readOnly=false __title='Enter the email of the entry' textMode='singleline' maxLength=100 cssClass='validate-email'}
            {eternizerValidationError id='email' class='validate-email'}
        </div>
        <div class="z-formrow">
            {formlabel for='homepage' __text='Homepage'}
            {formurlinput group='entry' id='homepage' mandatory=false readOnly=false __title='Enter the homepage of the entry' textMode='singleline' maxLength=255 cssClass='validate-url'}
            {eternizerValidationError id='homepage' class='validate-url'}
        </div>
        <div class="z-formrow">
            {formlabel for='location' __text='Location'}
            {formtextinput group='entry' id='location' mandatory=false readOnly=false __title='Enter the location of the entry' textMode='singleline' maxLength=100 cssClass=''}
        </div>
        <div class="z-formrow">
            {formlabel for='text' __text='Text'   mandatorysym='1'}
            {formtextinput group='entry' id='text' mandatory=true __title='Input the text of the entry' textMode='multiline' rows='6' cols='50' cssClass=''}
            {eternizerValidationError id='text' class='required'}
        </div>
        <input type="hidden" id='notes' name='notes' value=''>
    </fieldset>

    {if $mode ne 'create'}
        {include file='user/include_standardfields_edit.tpl' obj=$entry}
    {/if}

    {* include display hooks *}
    {if $mode eq 'create'}
        {notifydisplayhooks eventname='eternizer.ui_hooks.entries.form_edit' id=null assign='hooks'}
    {else}
        {notifydisplayhooks eventname='eternizer.ui_hooks.entries.form_edit' id=$entry.id assign='hooks'}
    {/if}
    {if is_array($hooks) && isset($hooks[0])}
        <fieldset>
            <legend>{gt text='Hooks'}</legend>
            {foreach key='hookName' item='hook' from=$hooks}
            <div class="z-formrow">
                {$hook}
            </div>
            {/foreach}
        </fieldset>
    {/if}

    {* include return control *}
    {* {if $mode eq 'create'}
        <fieldset>
            <legend>{gt text='Return control'}</legend>
            <div class="z-formrow">
                {formlabel for='repeatcreation' __text='Create another item after save'}
                {formcheckbox group='entry' id='repeatcreation' readOnly=false}
            </div>
        </fieldset>
    {/if} *}

    {* include possible submit actions *}
    <div class="z-buttons z-formbuttons">
    {if $mode eq 'edit'}
        {formbutton id='btnUpdate' commandName='update' __text='Update entry' class='z-bt-save'}
     {* {if !$inlineUsage}
        {gt text='Really delete this entry?' assign="deleteConfirmMsg"}
        {formbutton id='btnDelete' commandName='delete' __text='Delete entry' class='z-bt-delete z-btred' confirmMessage=$deleteConfirmMsg}
      {/if} *}
    {elseif $mode eq 'create'}
        {formbutton id='btnCreate' commandName='create' __text='Create entry' class='z-bt-ok'}
    {else}
        {formbutton id='btnUpdate' commandName='update' __text='OK' class='z-bt-ok'}
    {/if}
        {formbutton id='btnCancel' commandName='cancel' __text='Cancel' class='z-bt-cancel'}
    </div>
  {/eternizerFormFrame}
{/form}

</div>
</div>
{if $formposition eq 'menue'}
{include file='user/footer.tpl'}
{/if}

{icon type='edit' size='extrasmall' assign='editImageArray'}
{icon type='delete' size='extrasmall' assign='deleteImageArray'}

<script type="text/javascript" charset="utf-8">
/* <![CDATA[ */
    var editImage = '<img src="{{$editImageArray.src}}" width="16" height="16" alt="" />';
    var removeImage = '<img src="{{$deleteImageArray.src}}" width="16" height="16" alt="" />';

    document.observe('dom:loaded', function() {

        eternizerAddCommonValidationRules('entry', '{{if $mode eq 'create'}}{{else}}{{$entry.id}}{{/if}}');

        // observe button events instead of form submit
        var valid = new Validation('{{$__formid}}', {onSubmit: false, immediate: true, focusOnError: false});
        {{if $mode ne 'create'}}
            var result = valid.validate();
        {{/if}}

        $('{{if $mode eq 'create'}}btnCreate{{else}}btnUpdate{{/if}}').observe('click', function(event) {
            var result = valid.validate();
            if (!result) {
                // validation error, abort form submit
                Event.stop(event);
            } else {
                // hide form buttons to prevent double submits by accident
                $$('div.z-formbuttons input').each(function(btn) {
                    btn.hide();
                });
            }
            return result;
        });

        Zikula.UI.Tooltips($$('.eternizerFormTooltips'));
    });

/* ]]> */
</script>
