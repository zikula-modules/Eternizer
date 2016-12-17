'use strict';

function mUEternizerCapitaliseFirstLetter(string)
{
    return string.charAt(0).toUpperCase() + string.substring(1);
}

/**
 * Submits a quick navigation form.
 */
function mUEternizerSubmitQuickNavForm(objectType)
{
    jQuery('#mueternizermodule' + mUEternizerCapitaliseFirstLetter(objectType) + 'QuickNavForm').submit();
}

/**
 * Initialise the quick navigation panel in list views.
 */
function mUEternizerInitQuickNavigation(objectType)
{
    if (jQuery('#mueternizermodule' + mUEternizerCapitaliseFirstLetter(objectType) + 'QuickNavForm').length < 1) {
        return;
    }

    if (jQuery('#catid').length > 0) {
        jQuery('#catid').change(function () { mUEternizerSubmitQuickNavForm(objectType); });
    }
    if (jQuery('#sortBy').length > 0) {
        jQuery('#sortBy').change(function () { mUEternizerSubmitQuickNavForm(objectType); });
    }
    if (jQuery('#sortDir').length > 0) {
        jQuery('#sortDir').change(function () { mUEternizerSubmitQuickNavForm(objectType); });
    }
    if (jQuery('#num').length > 0) {
        jQuery('#num').change(function () { mUEternizerSubmitQuickNavForm(objectType); });
    }

    switch (objectType) {
    case 'entry':
        if (jQuery('#workflowState').length > 0) {
            jQuery('#workflowState').change(function () { mUEternizerSubmitQuickNavForm(objectType); });
        }
        break;
    default:
        break;
    }
}

/**
 * Simulates a simple alert using bootstrap.
 */
function mUEternizerSimpleAlert(beforeElem, title, content, alertId, cssClass)
{
    var alertBox;

    alertBox = ' \
        <div id="' + alertId + '" class="alert alert-' + cssClass + ' fade"> \
          <button type="button" class="close" data-dismiss="alert">&times;</button> \
          <h4>' + title + '</h4> \
          <p>' + content + '</p> \
        </div>';

    // insert alert before the given element
    beforeElem.before(alertBox);

    jQuery('#' + alertId).delay(200).addClass('in').fadeOut(4000, function () {
        jQuery(this).remove();
    });
}
