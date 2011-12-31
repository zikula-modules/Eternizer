{* Purpose of this template: edit view of generic item list content type *}

<div class="z-formrow">
    {formlabel for='Eternizer_objecttype' __text='Object type'}
    {eternizerSelectorObjectTypes assign='allObjectTypes'}
    {formdropdownlist id='Eternizer_objecttype' dataField='objectType' group='data' mandatory=true items=$allObjectTypes}
</div>

<div class="z-formrow">
    {formlabel __text='Sorting'}
    <div>
        {formradiobutton id='Eternizer_srandom' value='random' dataField='sorting' group='data' mandatory=true}
        {formlabel for='Eternizer_srandom' __text='Random'}
        {formradiobutton id='Eternizer_snewest' value='newest' dataField='sorting' group='data' mandatory=true}
        {formlabel for='Eternizer_snewest' __text='Newest'}
        {formradiobutton id='Eternizer_sdefault' value='default' dataField='sorting' group='data' mandatory=true}
        {formlabel for='Eternizer_sdefault' __text='Default'}
    </div>
</div>

<div class="z-formrow">
    {formlabel for='Eternizer_amount' __text='Amount'}
    {formtextinput id='Eternizer_amount' dataField='amount' group='data' mandatory=true maxLength=2}
</div>

<div class="z-formrow">
    {formlabel for='Eternizer_template' __text='Template File'}
    {eternizerSelectorTemplates assign='allTemplates'}
    {formdropdownlist id='Eternizer_template' dataField='template' group='data' mandatory=true items=$allTemplates}
</div>

<div class="z-formrow">
    {formlabel for='Eternizer_filter' __text='Filter (expert option)'}
    {formtextinput id='Eternizer_filter' dataField='filter' group='data' mandatory=false maxLength=255}
    <div class="z-formnote">({gt text='Syntax examples'}: <kbd>name:like:foobar</kbd> {gt text='or'} <kbd>status:ne:3</kbd>)</div>
</div>
