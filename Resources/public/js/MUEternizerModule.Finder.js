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
 * Open a popup window with the finder triggered by a Xinha button.
 */
function MUEternizerModuleFinderXinha(editor, eternizerUrl)
{
    var popupAttributes;

    // Save editor for access in selector window
    currentMUEternizerModuleEditor = editor;

    popupAttributes = getMUEternizerModulePopupAttributes();
    window.open(eternizerUrl, '', popupAttributes);
}

/**
 * Open a popup window with the finder triggered by a CKEditor button.
 */
function MUEternizerModuleFinderCKEditor(editor, eternizerUrl)
{
    // Save editor for access in selector window
    currentMUEternizerModuleEditor = editor;

    editor.popup(
        Routing.generate('mueternizermodule_external_finder', { editor: 'ckeditor' }),
        /*width*/ '80%', /*height*/ '70%',
        'location=no,menubar=no,toolbar=no,dependent=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=yes,scrollbars=yes'
    );
}


var mUEternizerModule = {};

mUEternizerModule.finder = {};

mUEternizerModule.finder.onLoad = function (baseId, selectedId)
{
    $('div.categoryselector select').change(mUEternizerModule.finder.onParamChanged);
    $('#mUEternizerModuleSort').change(mUEternizerModule.finder.onParamChanged);
    $('#mUEternizerModuleSortDir').change(mUEternizerModule.finder.onParamChanged);
    $('#mUEternizerModulePageSize').change(mUEternizerModule.finder.onParamChanged);
    $('#mUEternizerModuleSearchGo').click(mUEternizerModule.finder.onParamChanged);
    $('#mUEternizerModuleSearchGo').keypress(mUEternizerModule.finder.onParamChanged);
    $('#mUEternizerModuleSubmit').addClass('hidden');
    $('#mUEternizerModuleCancel').click(mUEternizerModule.finder.handleCancel);
};

mUEternizerModule.finder.onParamChanged = function ()
{
    $('#mUEternizerModuleSelectorForm').submit();
};

mUEternizerModule.finder.handleCancel = function ()
{
    var editor, w;

    editor = $('#editorName').val();
    if (editor === 'xinha') {
        w = parent.window;
        window.close();
        w.focus();
    } else if (editor === 'tinymce') {
        mUEternizerClosePopup();
    } else if (editor === 'ckeditor') {
        mUEternizerClosePopup();
    } else {
        alert('Close Editor: ' + editor);
    }
};


function mUEternizerGetPasteSnippet(mode, itemId)
{
    var quoteFinder, itemUrl, itemTitle, itemDescription, pasteMode;

    quoteFinder = new RegExp('"', 'g');
    itemUrl = $('#url' + itemId).val().replace(quoteFinder, '');
    itemTitle = $('#title' + itemId).val().replace(quoteFinder, '');
    itemDescription = $('#desc' + itemId).val().replace(quoteFinder, '');
    pasteMode = $('#mUEternizerModulePasteAs').val();

    if (pasteMode === '2' || pasteMode !== '1') {
        return itemId;
    }

    // return link to item
    if (mode === 'url') {
        // plugin mode
        return itemUrl;
    }

    // editor mode
    return '<a href="' + itemUrl + '" title="' + itemDescription + '">' + itemTitle + '</a>';
}


// User clicks on "select item" button
mUEternizerModule.finder.selectItem = function (itemId)
{
    var editor, html;

    editor = $('#editorName').val();
    if (editor === 'xinha') {
        if (window.opener.currentMUEternizerModuleEditor !== null) {
            html = mUEternizerGetPasteSnippet('html', itemId);

            window.opener.currentMUEternizerModuleEditor.focusEditor();
            window.opener.currentMUEternizerModuleEditor.insertHTML(html);
        } else {
            html = mUEternizerGetPasteSnippet('url', itemId);
            var currentInput = window.opener.currentMUEternizerModuleInput;

            if (currentInput.tagName === 'INPUT') {
                // Simply overwrite value of input elements
                currentInput.value = html;
            } else if (currentInput.tagName === 'TEXTAREA') {
                // Try to paste into textarea - technique depends on environment
                if (typeof document.selection !== 'undefined') {
                    // IE: Move focus to textarea (which fortunately keeps its current selection) and overwrite selection
                    currentInput.focus();
                    window.opener.document.selection.createRange().text = html;
                } else if (typeof currentInput.selectionStart !== 'undefined') {
                    // Firefox: Get start and end points of selection and create new value based on old value
                    var startPos = currentInput.selectionStart;
                    var endPos = currentInput.selectionEnd;
                    currentInput.value = currentInput.value.substring(0, startPos)
                                        + html
                                        + currentInput.value.substring(endPos, currentInput.value.length);
                } else {
                    // Others: just append to the current value
                    currentInput.value += html;
                }
            }
        }
    } else if (editor === 'tinymce') {
        html = mUEternizerGetPasteSnippet('html', itemId);
        tinyMCE.activeEditor.execCommand('mceInsertContent', false, html);
        // other tinymce commands: mceImage, mceInsertLink, mceReplaceContent, see http://www.tinymce.com/wiki.php/Command_identifiers
    } else if (editor === 'ckeditor') {
        if (window.opener.currentMUEternizerModuleEditor !== null) {
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
    $('#mUEternizerModuleObjectType').change(mUEternizerModule.itemSelector.onParamChanged);

    if ($('#' + baseId + '_catidMain').size() > 0) {
        $('#' + baseId + '_catidMain').change(mUEternizerModule.itemSelector.onParamChanged);
    } else if ($('#' + baseId + '_catidsMain').size() > 0) {
        $('#' + baseId + '_catidsMain').change(mUEternizerModule.itemSelector.onParamChanged);
    }
    $('#' + baseId + 'Id').change(mUEternizerModule.itemSelector.onItemChanged);
    $('#' + baseId + 'Sort').change(mUEternizerModule.itemSelector.onParamChanged);
    $('#' + baseId + 'SortDir').change(mUEternizerModule.itemSelector.onParamChanged);
    $('#mUEternizerModuleSearchGo').click(mUEternizerModule.itemSelector.onParamChanged);
    $('#mUEternizerModuleSearchGo').keypress(mUEternizerModule.itemSelector.onParamChanged);

    mUEternizerModule.itemSelector.getItemList();
};

mUEternizerModule.itemSelector.onParamChanged = function ()
{
    $('ajax_indicator').removeClass('hidden');

    mUEternizerModule.itemSelector.getItemList();
};

mUEternizerModule.itemSelector.getItemList = function ()
{
    var baseId, params;

    baseId = eternizer.itemSelector.baseId;
    params = 'ot=' + baseId + '&';
    if ($('#' + baseId + '_catidMain').size() > 0) {
        params += 'catidMain=' + $('#' + baseId + '_catidMain').val() + '&';
    } else if ($('#' + baseId + '_catidsMain').size() > 0) {
        params += 'catidsMain=' + $('#' + baseId + '_catidsMain').val() + '&';
    }
    params += 'sort=' + $('#' + baseId + 'Sort').val() + '&' +
              'sortdir=' + $('#' + baseId + 'SortDir').val() + '&' +
              'q=' + $('#' + baseId + 'SearchTerm').val();

    $.ajax({
        type: 'POST',
        url: Routing.generate('mueternizermodule_ajax_getitemlistfinder'),
        data: params
    }).done(function(res) {
        // get data returned by the ajax response
        var baseId;
        baseId = mUEternizerModule.itemSelector.baseId;
        mUEternizerModule.itemSelector.items[baseId] = res.data;
        $('#ajax_indicator').addClass('hidden');
        mUEternizerModule.itemSelector.updateItemDropdownEntries();
        mUEternizerModule.itemSelector.updatePreview();
    });
};

mUEternizerModule.itemSelector.updateItemDropdownEntries = function ()
{
    var baseId, itemSelector, items, i, item;

    baseId = mUEternizerModule.itemSelector.baseId;
    itemSelector = $('#' + baseId + 'Id');
    itemSelector.length = 0;

    items = mUEternizerModule.itemSelector.items[baseId];
    for (i = 0; i < items.length; ++i) {
        item = items[i];
        itemSelector.options[i] = new Option(item.title, item.id, false);
    }

    if (mUEternizerModule.itemSelector.selectedId > 0) {
        $('#' + baseId + 'Id').val(mUEternizerModule.itemSelector.selectedId);
    }
};

mUEternizerModule.itemSelector.updatePreview = function ()
{
    var baseId, items, selectedElement, i;

    baseId = mUEternizerModule.itemSelector.baseId;
    items = mUEternizerModule.itemSelector.items[baseId];

    $('#' + baseId + 'PreviewContainer').addClass('hidden');

    if (items.length === 0) {
        return;
    }

    selectedElement = items[0];
    if (mUEternizerModule.itemSelector.selectedId > 0) {
        for (var i = 0; i < items.length; ++i) {
            if (items[i].id === mUEternizerModule.itemSelector.selectedId) {
                selectedElement = items[i];
                break;
            }
        }
    }

    if (selectedElement !== null) {
        $('#' + baseId + 'PreviewContainer')
            .html(window.atob(selectedElement.previewInfo))
            .removeClass('hidden');
    }
};

mUEternizerModule.itemSelector.onItemChanged = function ()
{
    var baseId, itemSelector, preview;

    baseId = mUEternizerModule.itemSelector.baseId;
    itemSelector = $('#' + baseId + 'Id');
    preview = window.atob(mUEternizerModule.itemSelector.items[baseId][itemSelector.selectedIndex].previewInfo);

    $(baseId + 'PreviewContainer').html(preview);
    mUEternizerModule.itemSelector.selectedId = $('#' + baseId + 'Id').val();
};
