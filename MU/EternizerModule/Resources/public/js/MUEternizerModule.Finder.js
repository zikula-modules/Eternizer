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
    if (jQuery('#mUEternizerModuleSelectorForm').length < 1) {
        return;
    }
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

jQuery(document).ready(function() {
    mUEternizerModule.finder.onLoad();
});
