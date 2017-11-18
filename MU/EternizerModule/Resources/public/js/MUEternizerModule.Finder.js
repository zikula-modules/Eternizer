'use strict';

var currentMUEternizerModuleEditor = null;
var currentMUEternizerModuleInput = null;

/**
 * Returns the attributes used for the popup window. 
 * @return {String}
 */
function getMUEternizerModulePopupAttributes() {
    var pWidth, pHeight;

    pWidth = screen.width * 0.75;
    pHeight = screen.height * 0.66;

    return 'width=' + pWidth + ',height=' + pHeight + ',location=no,menubar=no,toolbar=no,dependent=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=yes,scrollbars=yes';
}

/**
 * Open a popup window with the finder triggered by an editor button.
 */
function MUEternizerModuleFinderOpenPopup(editor, editorName) {
    var popupUrl;

    // Save editor for access in selector window
    currentMUEternizerModuleEditor = editor;

    popupUrl = Routing.generate('mueternizermodule_external_finder', { objectType: 'entry', editor: editorName });

    if (editorName == 'ckeditor') {
        editor.popup(popupUrl, /*width*/ '80%', /*height*/ '70%', getMUEternizerModulePopupAttributes());
    } else {
        window.open(popupUrl, '_blank', getMUEternizerModulePopupAttributes());
    }
}


var mUEternizerModule = {};

mUEternizerModule.finder = {};

mUEternizerModule.finder.onLoad = function (baseId, selectedId) {
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

mUEternizerModule.finder.onParamChanged = function () {
    jQuery('#mUEternizerModuleSelectorForm').submit();
};

mUEternizerModule.finder.handleCancel = function (event) {
    var editor;

    event.preventDefault();
    editor = jQuery("[id$='editor']").first().val();
    if ('ckeditor' === editor) {
        mUEternizerClosePopup();
    } else if ('quill' === editor) {
        mUEternizerClosePopup();
    } else if ('summernote' === editor) {
        mUEternizerClosePopup();
    } else if ('tinymce' === editor) {
        mUEternizerClosePopup();
    } else {
        alert('Close Editor: ' + editor);
    }
};


function mUEternizerGetPasteSnippet(mode, itemId) {
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
mUEternizerModule.finder.selectItem = function (itemId) {
    var editor, html;

    html = mUEternizerGetPasteSnippet('html', itemId);
    editor = jQuery("[id$='editor']").first().val();
    if ('ckeditor' === editor) {
        if (null !== window.opener.currentMUEternizerModuleEditor) {
            window.opener.currentMUEternizerModuleEditor.insertHtml(html);
        }
    } else if ('quill' === editor) {
        if (null !== window.opener.currentMUEternizerModuleEditor) {
            window.opener.currentMUEternizerModuleEditor.clipboard.dangerouslyPasteHTML(window.opener.currentMUEternizerModuleEditor.getLength(), html);
        }
    } else if ('summernote' === editor) {
        if (null !== window.opener.currentMUEternizerModuleEditor) {
            html = jQuery(html).get(0);
            window.opener.currentMUEternizerModuleEditor.invoke('insertNode', html);
        }
    } else if ('tinymce' === editor) {
        window.opener.currentMUEternizerModuleEditor.insertContent(html);
    } else {
        alert('Insert into Editor: ' + editor);
    }
    mUEternizerClosePopup();
};

function mUEternizerClosePopup() {
    window.opener.focus();
    window.close();
}

jQuery(document).ready(function () {
    mUEternizerModule.finder.onLoad();
});
