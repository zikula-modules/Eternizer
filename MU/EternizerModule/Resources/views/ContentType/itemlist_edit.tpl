{* Purpose of this template: edit view of generic item list content type *}
<div class="form-group">
    {gt text='Object type' domain='mueternizermodule' assign='objectTypeSelectorLabel'}
    {formlabel for='mUEternizerModuleObjectType' text=$objectTypeSelectorLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {mueternizermoduleObjectTypeSelector assign='allObjectTypes'}
        {formdropdownlist id='mUEternizerModuleObjectType' dataField='objectType' group='data' mandatory=true items=$allObjectTypes cssClass='form-control'}
        <span class="help-block">{gt text='If you change this please save the element once to reload the parameters below.' domain='mueternizermodule'}</span>
    </div>
</div>

<div class="form-group">
    {gt text='Sorting' domain='mueternizermodule' assign='sortingLabel'}
    {formlabel text=$sortingLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formradiobutton id='mUEternizerModuleSortRandom' value='random' dataField='sorting' group='data' mandatory=true}
        {gt text='Random' domain='mueternizermodule' assign='sortingRandomLabel'}
        {formlabel for='mUEternizerModuleSortRandom' text=$sortingRandomLabel}
        {formradiobutton id='mUEternizerModuleSortNewest' value='newest' dataField='sorting' group='data' mandatory=true}
        {gt text='Newest' domain='mueternizermodule' assign='sortingNewestLabel'}
        {formlabel for='mUEternizerModuleSortNewest' text=$sortingNewestLabel}
        {formradiobutton id='mUEternizerModuleSortDefault' value='default' dataField='sorting' group='data' mandatory=true}
        {gt text='Default' domain='mueternizermodule' assign='sortingDefaultLabel'}
        {formlabel for='mUEternizerModuleSortDefault' text=$sortingDefaultLabel}
    </div>
</div>

<div class="form-group">
    {gt text='Amount' domain='mueternizermodule' assign='amountLabel'}
    {formlabel for='mUEternizerModuleAmount' text=$amountLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formintinput id='mUEternizerModuleAmount' dataField='amount' group='data' mandatory=true maxLength=2 cssClass='form-control'}
    </div>
</div>

<div class="form-group">
    {gt text='Template' domain='mueternizermodule' assign='templateLabel'}
    {formlabel for='mUEternizerModuleTemplate' text=$templateLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {mueternizermoduleTemplateSelector assign='allTemplates'}
        {formdropdownlist id='mUEternizerModuleTemplate' dataField='template' group='data' mandatory=true items=$allTemplates cssClass='form-control'}
    </div>
</div>

<div id="customTemplateArea" class="form-group"{* data-switch="mUEternizerModuleTemplate" data-switch-value="custom"*}>
    {gt text='Custom template' domain='mueternizermodule' assign='customTemplateLabel'}
    {formlabel for='mUEternizerModuleCustomTemplate' text=$customTemplateLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formtextinput id='mUEternizerModuleCustomTemplate' dataField='customTemplate' group='data' mandatory=false maxLength=80 cssClass='form-control'}
        <span class="help-block">{gt text='Example' domain='mueternizermodule'}: <em>itemlist_[objectType]_display.html.twig</em></span>
    </div>
</div>

<div class="form-group">
    {gt text='Filter (expert option)' domain='mueternizermodule' assign='filterLabel'}
    {formlabel for='mUEternizerModuleFilter' text=$filterLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formtextinput id='mUEternizerModuleFilter' dataField='filter' group='data' mandatory=false maxLength=255 cssClass='form-control'}
    </div>
</div>

<script type="text/javascript">
    (function($) {
    	$('#mUEternizerModuleTemplate').change(function() {
    	    $('#customTemplateArea').toggleClass('hidden', $(this).val() != 'custom');
	    }).trigger('change');
    })(jQuery)
</script>
