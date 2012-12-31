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
    {eternizerFormFrame}
    {formsetinitialfocus inputId='name'}
    <div class="z-warningmsg z-formnote">{gt text="Warning: Don' forget to make a backup of your database before using this import form!"}</div>
    <div class="z-informationmsg z-formnote">{gt text="Notice: With this form you are able to move your old entries into the new table. You can get the attributes 'name', 'email, 'homepage' and 'location' from the objectdata_attributes table for the entries if saved there. You can delete the old table. And you can delete the entries for Eternizer in the table objectdata_attributes."}</div>
    <legend>{gt text='Here you can manage all basic settings for the import.'}</legend>
    <fieldset>
        <div class="z-informationmsg z-formnote">{gt text="Notice: For import of attributes look in the table objectdata_attributes for entries where object_type is 'Eternizer_entry'!"}</div>
        <div class="z-formrow">
            {formlabel for='name' __text='-> Name'}
            {formtextinput id='name' group='import' maxLength=255 width=20em __title='Enter the correct attribute name.' value='0'}
            <div class="z-informationmsg z-formnote">{gt text="Notice: Enter the attribute name for the name in the table objectdata_attributes. For example: 0"}</div>
        </div>
        <div class="z-formrow">
            {formlabel for='email' __text='-> Email'}
            {formtextinput id='email' group='import' maxLength=255 width=20em __title='Enter the correct attribute name.'}
            <div class="z-informationmsg z-formnote">{gt text="Notice: Enter the attribute name for the email in the table objectdata_attributes. For example: 1"}</div>
        </div>
        <div class="z-formrow">
            {formlabel for='homepage' __text='-> Homepage'}
            {formtextinput id='homepage' group='import' maxLength=255 width=20em __title='Enter the correct attribute name.'}
            <div class="z-informationmsg z-formnote">{gt text="Notice: Enter the attribute name for the homepage in the table objectdata_attributes. For example: 2"}</div>
        </div>
        <div class="z-formrow">
            {formlabel for='location' __text='-> Location'}
            {formtextinput id='location' group='import' maxLength=255 width=20em __title='Enter the correct attribute name.'}
            <div class="z-informationmsg z-formnote">{gt text="Notice: Enter the attribute name for the location in the table objectdata_attributes. For example: 3"}</div>
        </div>
        <div class="z-formrow">
            {formlabel for='attributes' __text='Import attributes?'}
            {formcheckbox id='attributes' group='import'}
        </div>
        <div class="z-informationmsg z-formnote">{gt text="Notice: If you check here, also the attributes for the entries will be imported into the new module table."}</div>
        <div class="z-formrow">
            {formlabel for='oldtable' __text='Delete old table?'}
            {formcheckbox id='oldtable' group='import'}
        </div>
        <div class="z-informationmsg z-formnote">{gt text="Notice: If you check here, also the old table of Eternizer will be deleted."}</div>
        <div class="z-formrow">
            {formlabel for='entriesattributes' __text='Delete entries in table objectdata_attributes?'}
            {formcheckbox id='entriesattributes' group='import'}
        </div>
        <div class="z-informationmsg z-formnote">{gt text="Notice: If you check here, also the entries of Eternizer in the table objectdata_attributes will be deleted."}</div>
    </fieldset>

    <div class="z-buttons z-formbuttons">
        {formbutton commandName='import' __text='Start import' class='z-bt-save'}
        {formbutton commandName='cancel' __text='Cancel' class='z-bt-cancel'}
    </div>
    {/eternizerFormFrame}
    {/form}
</div>
{include file='admin/footer.tpl'}
