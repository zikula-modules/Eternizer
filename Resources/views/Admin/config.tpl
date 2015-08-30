{* purpose of this template: module configuration *}
{include file='Admin/header.tpl'}
<div class="mueternizermodule-config">
    {gt text='Settings' assign='templateTitle'}
    {pagesetvar name='title' value=$templateTitle}
    <h3>
        <span class="fa fa-wrench"></span>
        {$templateTitle}
    </h3>

    {form cssClass='form-horizontal' role='form'}
        {* add validation summary and a <div> element for styling the form *}
        {mueternizermoduleFormFrame}
            {formsetinitialfocus inputId='pagesize'}
            {gt text='Variables' assign='tabTitle'}
            <fieldset>
                <legend>{$tabTitle}</legend>
            
                <p class="alert alert-info">{gt text='Here you can manage all basic settings for this application.'}</p>
            
                <div class="form-group">
                    {formlabel for='pagesize' __text='Pagesize' cssClass=' col-sm-3 control-label'}
                    <div class="col-sm-9">
                        {formintinput id='pagesize' group='config' maxLength=255 __title='Enter the pagesize. Only digits are allowed.' cssClass='form-control'}
                    </div>
                </div>
                <div class="form-group">
                    {formlabel for='mail' __text='Mail' cssClass=' col-sm-3 control-label'}
                    <div class="col-sm-9">
                        {formtextinput id='mail' group='config' maxLength=255 __title='Enter the mail.' cssClass='form-control'}
                    </div>
                </div>
                <div class="form-group">
                    {formlabel for='order' __text='Order' cssClass=' col-sm-3 control-label'}
                    <div class="col-sm-9">
                        {formdropdownlist id='order' group='config' __title='Choose the order' cssClass='form-control'}
                    </div>
                </div>
                <div class="form-group">
                    {formlabel for='moderate' __text='Moderate' cssClass=' col-sm-3 control-label'}
                    <div class="col-sm-9">
                        {formdropdownlist id='moderate' group='config' __title='Choose the moderate' cssClass='form-control'}
                    </div>
                </div>
                <div class="form-group">
                    {formlabel for='formposition' __text='Formposition' cssClass=' col-sm-3 control-label'}
                    <div class="col-sm-9">
                        {formdropdownlist id='formposition' group='config' __title='Choose the formposition' cssClass='form-control'}
                    </div>
                </div>
                <div class="form-group">
                    {formlabel for='ipsave' __text='Ipsave' cssClass=' col-sm-3 control-label'}
                    <div class="col-sm-9">
                        {formcheckbox id='ipsave' group='config'}
                    </div>
                </div>
                <div class="form-group">
                    {formlabel for='editentries' __text='Editentries' cssClass=' col-sm-3 control-label'}
                    <div class="col-sm-9">
                        {formcheckbox id='editentries' group='config'}
                    </div>
                </div>
                <div class="form-group">
                    {gt text='Here we can decide how long a user may edit his entry; input of hours' assign='toolTip'}
                    {formlabel for='period' __text='Period' cssClass='mueternizermodule-form-tooltips  col-sm-3 control-label' title=$toolTip}
                    <div class="col-sm-9">
                        {formintinput id='period' group='config' maxLength=255 __title='Enter the period. Only digits are allowed.' cssClass='form-control'}
                    </div>
                </div>
                <div class="form-group">
                    {formlabel for='simplecaptcha' __text='Simplecaptcha' cssClass=' col-sm-3 control-label'}
                    <div class="col-sm-9">
                        {formcheckbox id='simplecaptcha' group='config'}
                    </div>
                </div>
            </fieldset>

            <div class="form-group form-buttons">
            <div class="col-sm-offset-3 col-sm-9">
                {formbutton commandName='save' __text='Update configuration' class='btn btn-success'}
                {formbutton commandName='cancel' __text='Cancel' class='btn btn-default'}
            </div>
            </div>
        {/mueternizermoduleFormFrame}
    {/form}
</div>
{include file='Admin/footer.tpl'}
<script type="text/javascript">
/* <![CDATA[ */
    ( function($) {
        $(document).ready(function() {
            $('.mueternizermodule-form-tooltips').tooltip();
        });
    })(jQuery);
/* ]]> */
</script>
