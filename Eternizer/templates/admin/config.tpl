{* purpose of this template: module configuration *}
{include file='admin/header.tpl'}
<div class="eternizer-config">
{gt text='Settings' assign='templateTitle'}
{pagesetvar name='title' value=$templateTitle}
<div class="z-admin-content-pagetitle">
    {icon type='config' size='small' __alt='Settings'}
    <h3>{$templateTitle}</h3>
</div>

    {form cssClass='z-form'}


        {* add validation summary and a <div> element for styling the form *}
        {eternizerFormFrame}
        {formsetinitialfocus inputId='itemsperpage'}
            <fieldset>
                <legend>{gt text='Here you can manage all basic settings for this application.'}</legend>

                <div class="z-formrow">
                    {formlabel for='itemsperpage' __text='Itemsperpage'}
                    {formintinput id='itemsperpage' group='config' maxLength=255 width=20em __title='Input this setting. Only digits are allowed.'}
                </div>
                <div class="z-formrow">
                    {formlabel for='mail' __text='Mail'}
                    {formtextinput id='mail' group='config' maxLength=255 width=20em __title='Input this setting.'}
                </div>
                <div class="z-formrow">
                    {formlabel for='order' __text='Order'}
                    {formtextinput id='order' group='config' maxLength=255 width=20em __title='Input this setting.'}
                </div>
                <div class="z-formrow">
                    {formlabel for='moderate' __text='Moderate'}
                    {formtextinput id='moderate' group='config' maxLength=255 width=20em __title='Input this setting.'}
                </div>
                <div class="z-formrow">
                    {formlabel for='profiledata' __text='Profiledata'}
                    {formtextinput id='profiledata' group='config' maxLength=255 width=20em __title='Input this setting.'}
                </div>
                <div class="z-formrow">
                    {formlabel for='action' __text='Action'}
                    {formtextinput id='action' group='config' maxLength=255 width=20em __title='Input this setting.'}
                </div>
                <div class="z-formrow">
                    {formlabel for='maxlength' __text='Maxlength'}
                    {formintinput id='maxlength' group='config' maxLength=255 width=20em __title='Input this setting. Only digits are allowed.'}
                </div>
                <div class="z-formrow">
                    {formlabel for='afterlength' __text='Afterlength'}
                    {formintinput id='afterlength' group='config' maxLength=255 width=20em __title='Input this setting. Only digits are allowed.'}
                </div>
            </fieldset>

            <div class="z-buttons z-formbuttons">
                {formbutton commandName='save' __text='Update configuration' class='z-bt-save'}
                {formbutton commandName='cancel' __text='Cancel' class='z-bt-cancel'}
            </div>
        {/eternizerFormFrame}
    {/form}
</div>
{include file='admin/footer.tpl'}
