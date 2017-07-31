'use strict';

var mUEternizerModule = {};

mUEternizerModule.itemSelector = {};
mUEternizerModule.itemSelector.items = {};
mUEternizerModule.itemSelector.baseId = 0;
mUEternizerModule.itemSelector.selectedId = 0;

mUEternizerModule.itemSelector.onLoad = function (baseId, selectedId)
{
    mUEternizerModule.itemSelector.baseId = baseId;
    mUEternizerModule.itemSelector.selectedId = selectedId;

    // required as a changed object type requires a new instance of the item selector plugin
    jQuery('#mUEternizerModuleObjectType').change(mUEternizerModule.itemSelector.onParamChanged);

    jQuery('#' + baseId + '_catidMain').change(mUEternizerModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + '_catidsMain').change(mUEternizerModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + 'Id').change(mUEternizerModule.itemSelector.onItemChanged);
    jQuery('#' + baseId + 'Sort').change(mUEternizerModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + 'SortDir').change(mUEternizerModule.itemSelector.onParamChanged);
    jQuery('#mUEternizerModuleSearchGo').click(mUEternizerModule.itemSelector.onParamChanged);
    jQuery('#mUEternizerModuleSearchGo').keypress(mUEternizerModule.itemSelector.onParamChanged);

    mUEternizerModule.itemSelector.getItemList();
};

mUEternizerModule.itemSelector.onParamChanged = function ()
{
    jQuery('#ajaxIndicator').removeClass('hidden');

    mUEternizerModule.itemSelector.getItemList();
};

mUEternizerModule.itemSelector.getItemList = function ()
{
    var baseId;
    var params;

    baseId = mUEternizerModule.itemSelector.baseId;
    params = {
        ot: baseId,
        sort: jQuery('#' + baseId + 'Sort').val(),
        sortdir: jQuery('#' + baseId + 'SortDir').val(),
        q: jQuery('#' + baseId + 'SearchTerm').val()
    }
    if (jQuery('#' + baseId + '_catidMain').length > 0) {
        params[catidMain] = jQuery('#' + baseId + '_catidMain').val();
    } else if (jQuery('#' + baseId + '_catidsMain').length > 0) {
        params[catidsMain] = jQuery('#' + baseId + '_catidsMain').val();
    }

    jQuery.getJSON(Routing.generate('mueternizermodule_ajax_getitemlistfinder'), params, function( data ) {
        var baseId;

        baseId = mUEternizerModule.itemSelector.baseId;
        mUEternizerModule.itemSelector.items[baseId] = data;
        jQuery('#ajaxIndicator').addClass('hidden');
        mUEternizerModule.itemSelector.updateItemDropdownEntries();
        mUEternizerModule.itemSelector.updatePreview();
    });
};

mUEternizerModule.itemSelector.updateItemDropdownEntries = function ()
{
    var baseId, itemSelector, items, i, item;

    baseId = mUEternizerModule.itemSelector.baseId;
    itemSelector = jQuery('#' + baseId + 'Id');
    itemSelector.length = 0;

    items = mUEternizerModule.itemSelector.items[baseId];
    for (i = 0; i < items.length; ++i) {
        item = items[i];
        itemSelector.get(0).options[i] = new Option(item.title, item.id, false);
    }

    if (mUEternizerModule.itemSelector.selectedId > 0) {
        jQuery('#' + baseId + 'Id').val(mUEternizerModule.itemSelector.selectedId);
    }
};

mUEternizerModule.itemSelector.updatePreview = function ()
{
    var baseId, items, selectedElement, i;

    baseId = mUEternizerModule.itemSelector.baseId;
    items = mUEternizerModule.itemSelector.items[baseId];

    jQuery('#' + baseId + 'PreviewContainer').addClass('hidden');

    if (items.length === 0) {
        return;
    }

    selectedElement = items[0];
    if (mUEternizerModule.itemSelector.selectedId > 0) {
        for (var i = 0; i < items.length; ++i) {
            if (items[i].id == mUEternizerModule.itemSelector.selectedId) {
                selectedElement = items[i];
                break;
            }
        }
    }

    if (null !== selectedElement) {
        jQuery('#' + baseId + 'PreviewContainer')
            .html(window.atob(selectedElement.previewInfo))
            .removeClass('hidden');
    }
};

mUEternizerModule.itemSelector.onItemChanged = function ()
{
    var baseId, itemSelector, preview;

    baseId = mUEternizerModule.itemSelector.baseId;
    itemSelector = jQuery('#' + baseId + 'Id').get(0);
    preview = window.atob(mUEternizerModule.itemSelector.items[baseId][itemSelector.selectedIndex].previewInfo);

    jQuery('#' + baseId + 'PreviewContainer').html(preview);
    mUEternizerModule.itemSelector.selectedId = jQuery('#' + baseId + 'Id').val();
};

jQuery(document).ready(function() {
    var infoElem;

    infoElem = jQuery('#itemSelectorInfo');
    if (infoElem.length == 0) {
        return;
    }

    mUEternizerModule.itemSelector.onLoad(infoElem.data('base-id'), infoElem.data('selected-id'));
});
