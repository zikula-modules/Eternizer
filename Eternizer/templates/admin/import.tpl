{* purpose of this template: module configuration *}
{include file='admin/header.tpl'}
<div class="eternizer-config">
{gt text='Import' assign='templateTitle'}
{pagesetvar name='title' value=$templateTitle}
<div class="z-admin-content-pagetitle">
    {icon type='config' size='small' __alt='Settings'}
    <h3>{$templateTitle}</h3>
</div>

    {form cssClass='z-form'}


        {* add validation summary and a <div> element for styling the form *}
        {eternizerFormFrame}
        {formsetinitialfocus inputId='name'}
            <fieldset>
                <legend>{gt text='Here you can manage all basic settings for the import.'}</legend>
                <div class="z-formrow">
                    {formlabel for='name' __text='-> Name'}
                    {formtextinput id='name' group='import' maxLength=255 width=20em __title='Enter this setting.'}
                    <div class="z-informationmsg z-formnote">{gt text="Notice: If you enter a correct email adress a message will be sent to when a new entry was saved. 
                    If you leave this field empty, it is disabled."}</div>
                </div>
                <div class="z-formrow">
                    {formlabel for='email' __text='-> Email'}
                    {formtextinput id='email' group='import' maxLength=255 width=20em __title='Enter this setting.'}
                    <div class="z-informationmsg z-formnote">{gt text="Notice: If you enter a correct email adress a message will be sent to when a new entry was saved. 
                    If you leave this field empty, it is disabled."}</div>
                </div>
                <div class="z-formrow">
                    {formlabel for='homepage' __text='-> Homepage'}
                    {formtextinput id='homepage' group='import' maxLength=255 width=20em __title='Enter this setting.'}
                    <div class="z-informationmsg z-formnote">{gt text="Notice: If you enter a correct email adress a message will be sent to when a new entry was saved. 
                    If you leave this field empty, it is disabled."}</div>
                </div>  
                <div class="z-formrow">
                    {formlabel for='location' __text='-> Location'}
                    {formtextinput id='location' group='import' maxLength=255 width=20em __title='Enter this setting.'}
                    <div class="z-informationmsg z-formnote">{gt text="Notice: If you enter a correct email adress a message will be sent to when a new entry was saved. 
                    If you leave this field empty, it is disabled."}</div>
                </div>                   
                <div class="z-formrow">
                    {formlabel for='attributes' __text='Import attributes?'}
                    {formcheckbox id='attributes' group='import'}
                </div>
                <div class="z-informationmsg z-formnote">{gt text="Notice: If you check here, also the attributes for the entries will 
                be imported into the new module table."}</div>
                <div class="z-formrow">
                    {formlabel for='oldtable' __text='Delete old table?'}
                    {formcheckbox id='oldtable' group='import'}
                </div>
            </fieldset>

            <div class="z-buttons z-formbuttons">
                {formbutton commandName='import' __text='Start import' class='z-bt-save'}             
                {formbutton commandName='cancel' __text='Cancel' class='z-bt-cancel'}
            </div>
        {/eternizerFormFrame}
    {/form}
</div>
{include file='admin/footer.tpl'}
