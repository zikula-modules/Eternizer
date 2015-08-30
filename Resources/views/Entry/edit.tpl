{* purpose of this template: build the Form to edit an instance of entry *}
{assign var='lct' value='user'}
{if isset($smarty.get.lct) && $smarty.get.lct eq 'admin'}
    {assign var='lct' value='admin'}
{/if}
{assign var='lctUc' value=$lct|ucfirst}
{include file="`$lctUc`/header.tpl"}
{pageaddvar name='javascript' value='@MUEternizerModule/Resources/public/js/MUEternizerModule.EditFunctions.js'}
{pageaddvar name='javascript' value='@MUEternizerModule/Resources/public/js/MUEternizerModule.Validation.js'}

{if $mode eq 'edit'}
    {gt text='Edit entry' assign='templateTitle'}
    {if $lct eq 'admin'}
        {assign var='adminPageIcon' value='pencil-square-o'}
    {/if}
{elseif $mode eq 'create'}
    {gt text='Create entry' assign='templateTitle'}
    {if $lct eq 'admin'}
        {assign var='adminPageIcon' value='plus'}
    {/if}
{else}
    {gt text='Edit entry' assign='templateTitle'}
    {if $lct eq 'admin'}
        {assign var='adminPageIcon' value='pencil-square-o'}
    {/if}
{/if}
<div class="mueternizermodule-entry mueternizermodule-edit">
    {pagesetvar name='title' value=$templateTitle}
    {if $lct eq 'admin'}
        <h3>
            <span class="fa fa-{$adminPageIcon}"></span>
            {$templateTitle}
        </h3>
    {else}
        <h2>{$templateTitle}</h2>
    {/if}
{form cssClass='form-horizontal' role='form'}
    {* add validation summary and a <div> element for styling the form *}
    {mueternizermoduleFormFrame}
    {formsetinitialfocus inputId='ip'}

    <fieldset>
        <legend>{gt text='Content'}</legend>
        
        <div class="form-group">
            {formlabel for='ip' __text='Ip' cssClass=' col-sm-3 control-label'}
            <div class="col-sm-9">
            {formtextinput group='entry' id='ip' mandatory=false readOnly=false __title='Enter the ip of the entry' textMode='singleline' maxLength=15 cssClass='form-control ' }
            </div>
        </div>
        
        <div class="form-group">
            {formlabel for='name' __text='Name' cssClass=' col-sm-3 control-label'}
            <div class="col-sm-9">
            {formtextinput group='entry' id='name' mandatory=false readOnly=false __title='Enter the name of the entry' textMode='singleline' maxLength=100 cssClass='form-control ' }
            </div>
        </div>
        
        <div class="form-group">
            {formlabel for='email' __text='Email' cssClass=' col-sm-3 control-label'}
            <div class="col-sm-9">
            <div class="input-group">
                <span class="input-group-addon">@</span>
                {formemailinput group='entry' id='email' mandatory=false readOnly=false __title='Enter the email of the entry' textMode='singleline' maxLength=100 cssClass='form-control  validate-email' }
            </div>
            </div>
        </div>
        
        <div class="form-group">
            {formlabel for='homepage' __text='Homepage' cssClass=' col-sm-3 control-label'}
            <div class="col-sm-9">
            {formurlinput group='entry' id='homepage' mandatory=false readOnly=false __title='Enter the homepage of the entry' textMode='singleline' maxLength=255 cssClass='form-control  validate-url' }
            </div>
        </div>
        
        <div class="form-group">
            {formlabel for='location' __text='Location' cssClass=' col-sm-3 control-label'}
            <div class="col-sm-9">
            {formtextinput group='entry' id='location' mandatory=false readOnly=false __title='Enter the location of the entry' textMode='singleline' maxLength=100 cssClass='form-control ' }
            </div>
        </div>
        
        <div class="form-group">
            {formlabel for='text' __text='Text' mandatorysym='1' cssClass=' col-sm-3 control-label'}
            <div class="col-sm-9">
            {formtextinput group='entry' id='text' mandatory=true __title='Enter the text of the entry' textMode='multiline' rows='6' cssClass='form-control required' }
            </div>
        </div>
        
        <div class="form-group">
            {formlabel for='notes' __text='Notes' cssClass=' col-sm-3 control-label'}
            <div class="col-sm-9">
            {formtextinput group='entry' id='notes' mandatory=false __title='Enter the notes of the entry' textMode='multiline' rows='6' cssClass='form-control ' }
            </div>
        </div>
        
        <div class="form-group">
            {formlabel for='obj_status' __text='Obj_status' mandatorysym='1' cssClass=' col-sm-3 control-label'}
            <div class="col-sm-9">
            {formtextinput group='entry' id='obj_status' mandatory=true readOnly=false __title='Enter the obj_status of the entry' textMode='singleline' maxLength=1 cssClass='form-control required' }
            </div>
        </div>
    </fieldset>
    
    {if $mode ne 'create'}
        {include file='Helper/include_standardfields_edit.tpl' obj=$entry}
    {/if}
    
    {* include display hooks *}
    {if $mode ne 'create'}
        {assign var='hookId' value=$entry.id}
        {notifydisplayhooks eventname='eternizer.ui_hooks.entries.form_edit' id=$hookId assign='hooks'}
    {else}
        {notifydisplayhooks eventname='eternizer.ui_hooks.entries.form_edit' id=null assign='hooks'}
    {/if}
    {if is_array($hooks) && count($hooks)}
        {foreach name='hookLoop' key='providerArea' item='hook' from=$hooks}
            {if $providerArea ne 'provider.scribite.ui_hooks.editor'}{* fix for #664 *}
                <fieldset>
                    {$hook}
                </fieldset>
            {/if}
        {/foreach}
    {/if}
    
    
    {* include return control *}
    {if $mode eq 'create'}
        <fieldset>
            <legend>{gt text='Return control'}</legend>
            <div class="form-group">
                {formlabel for='repeatCreation' __text='Create another item after save' cssClass='col-sm-3 control-label'}
            <div class="col-sm-9">
                    {formcheckbox group='entry' id='repeatCreation' readOnly=false}
            </div>
            </div>
        </fieldset>
    {/if}
    
    {* include possible submit actions *}
    <div class="form-group form-buttons">
    <div class="col-sm-offset-3 col-sm-9">
        {foreach item='action' from=$actions}
            {assign var='actionIdCapital' value=$action.id|@ucfirst}
            {gt text=$action.title assign='actionTitle'}
            {*gt text=$action.description assign='actionDescription'*}{* TODO: formbutton could support title attributes *}
            {if $action.id eq 'delete'}
                {gt text='Really delete this entry?' assign='deleteConfirmMsg'}
                {formbutton id="btn`$actionIdCapital`" commandName=$action.id text=$actionTitle class=$action.buttonClass confirmMessage=$deleteConfirmMsg}
            {else}
                {formbutton id="btn`$actionIdCapital`" commandName=$action.id text=$actionTitle class=$action.buttonClass}
            {/if}
        {/foreach}
        {formbutton id='btnCancel' commandName='cancel' __text='Cancel' class='btn btn-default' formnovalidate='formnovalidate'}
    </div>
    </div>
    {/mueternizermoduleFormFrame}
{/form}
</div>
{include file="`$lctUc`/footer.tpl"}

{assign var='editImage' value='<span class="fa fa-pencil-square-o"></span>'}
{assign var='deleteImage' value='<span class="fa fa-trash-o"></span>'}


<script type="text/javascript">
/* <![CDATA[ */
    
            var formButtons;
    
            function executeCustomValidationConstraints()
            {
                mUEternizerPerformCustomValidationRules('entry', '{{if $mode ne 'create'}}{{$entry.id}}{{/if}}');
            }
    
            function triggerFormValidation()
            {
                executeCustomValidationConstraints();
                if (!document.getElementById('{{$__formid}}').checkValidity()) {
                    // This does not really submit the form,
                    // but causes the browser to display the error message
                    jQuery('#{{$__formid}}').find(':submit').not(jQuery('#btnDelete')).click();
                }
            }
    
            function handleFormSubmit (event) {
                triggerFormValidation();
                if (!document.getElementById('{{$__formid}}').checkValidity()) {
                    event.preventDefault();
                    return false;
                }
    
                // hide form buttons to prevent double submits by accident
                formButtons.each(function (index) {
                    jQuery(this).addClass('hidden');
                });
    
                return true;
            }
    
            ( function($) {
                $(document).ready(function() {
    
                    var allFormFields = $('#{{$__formid}} input, #{{$__formid}} select, #{{$__formid}} textarea');
                    allFormFields.change(executeCustomValidationConstraints);
    
                    formButtons = $('#{{$__formid}} .form-buttons input');
                    $('#{{$__formid}}').submit(handleFormSubmit);
    
                    {{if $mode ne 'create'}}
                        triggerFormValidation();
                    {{/if}}
    
                    $('#{{$__formid}} label').tooltip();
                });
            })(jQuery);
/* ]]> */
</script>
