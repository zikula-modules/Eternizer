'use strict';

var currentMUEternizerModuleEditor = null;
var currentMUEternizerModuleInput = null;

/**
 * Returns the attributes used for the popup window. 
 * @return {String}
 */
function getMUEternizerModulePopupAttributes()
{
    var pWidth, pHeight;

    pWidth = screen.width * 0.75;
    pHeight = screen.height * 0.66;

    return 'width=' + pWidth + ',height=' + pHeight + ',scrollbars,resizable';
}

/**
 * Open a popup window with the finder triggered by a CKEditor button.
 */
function MUEternizerModuleFinderCKEditor(editor, eternizerUrl)
{
    // Save editor for access in selector window
    currentMUEternizerModuleEditor = editor;

    editor.popup(
        Routing.generate('mueternizermodule_external_finder', { objectType: 'entry', editor: 'ckeditor' }),
        /*width*/ '80%', /*height*/ '70%',
        'location=no,menubar=no,toolbar=no,dependent=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=yes,scrollbars=yes'
    );
}


var mUEternizerModule = {};

mUEternizerModule.finder = {};

mUEternizerModule.finder.onLoad = function (baseId, selectedId)
{
    jQuery('select').not("[id$='pasteAs']").change(mUEternizerModule.finder.onParamChanged);
    
    jQuery('.btn-default').click(mUEternizerModule.finder.handleCancel);

    var selectedItems = jQuery('#mueternizermoduleItemContainer a');
    selectedItems.bind('click keypress', function (event) {
        event.preventDefault();
        mUEternizerModule.finder.selectItem(jQuery(this).data('itemid'));
    });
};

mUEternizerModule.finder.onParamChanged = function ()
{
    jQuery('#mUEternizerModuleSelectorForm').submit();
};

mUEternizerModule.finder.handleCancel = function (event)
{
    var editor;

    event.preventDefault();
    editor = jQuery("[id$='editor']").first().val();
    if ('tinymce' === editor) {
        mUEternizerClosePopup();
    } else if ('ckeditor' === editor) {
        mUEternizerClosePopup();
    } else {
        alert('Close Editor: ' + editor);
    }
};


function mUEternizerGetPasteSnippet(mode, itemId)
{
    var quoteFinder;
    var itemPath;
    var itemUrl;
    var itemTitle;
    var itemDescription;
    var pasteMode;

    quoteFinder = new RegExp('"', 'g');
    itemPath = jQuery('#path' + itemId).val().replace(quoteFinder, '');
    itemUrl = jQuery('#url' + itemId).val().replace(quoteFinder, '');
    itemTitle = jQuery('#title' + itemId).val().replace(quoteFinder, '').trim();
    itemDescription = jQuery('#desc' + itemId).val().replace(quoteFinder, '').trim();
    pasteMode = jQuery("[id$='pasteAs']").first().val();

    // item ID
    if (pasteMode === '3') {
        return '' + itemId;
    }

    // relative link to detail page
    if (pasteMode === '1') {
        return mode === 'url' ? itemPath : '<a href="' + itemPath + '" title="' + itemDescription + '">' + itemTitle + '</a>';
    }
    // absolute url to detail page
    if (pasteMode === '2') {
        return mode === 'url' ? itemUrl : '<a href="' + itemUrl + '" title="' + itemDescription + '">' + itemTitle + '</a>';
    }

    return '';
}


// User clicks on "select item" button
mUEternizerModule.finder.selectItem = function (itemId)
{
    var editor, html;

    editor = jQuery("[id$='editor']").first().val();
    if ('tinymce' === editor) {
        html = mUEternizerGetPasteSnippet('html', itemId);
        tinyMCE.activeEditor.execCommand('mceInsertContent', false, html);
        // other tinymce commands: mceImage, mceInsertLink, mceReplaceContent, see http://www.tinymce.com/wiki.php/Command_identifiers
    } else if ('ckeditor' === editor) {
        if (null !== window.opener.currentMUEternizerModuleEditor) {
            html = mUEternizerGetPasteSnippet('html', itemId);

            window.opener.currentMUEternizerModuleEditor.insertHtml(html);
        }
    } else {
        alert('Insert into Editor: ' + editor);
    }
    mUEternizerClosePopup();
};

function mUEternizerClosePopup()
{
    window.opener.focus();
    window.close();
}




//=============================================================================
// MUEternizerModule item selector for Forms
//=============================================================================

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
    jQuery('#ajax_indicator').removeClass('hidden');

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

    jQuery.ajax({
        type: 'POST',
        url: Routing.generate('mueternizermodule_ajax_getitemlistfinder'),
        data: params
    }).done(function(res) {
        // get data returned by the ajax response
        var baseId;
        baseId = mUEternizerModule.itemSelector.baseId;
        mUEternizerModule.itemSelector.items[baseId] = res.data;
        jQuery('#ajax_indicator').addClass('hidden');
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
