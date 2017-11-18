'use strict';

function mUEternizerValidateNoSpace(val) {
    var valStr;
    valStr = new String(val);

    return (valStr.indexOf(' ') === -1);
}

/**
 * Runs special validation rules.
 */
function mUEternizerExecuteCustomValidationConstraints(objectType, currentEntityId) {
    jQuery('.validate-nospace').each(function () {
        if (!mUEternizerValidateNoSpace(jQuery(this).val())) {
            document.getElementById(jQuery(this).attr('id')).setCustomValidity(Translator.__('This value must not contain spaces.'));
        } else {
            document.getElementById(jQuery(this).attr('id')).setCustomValidity('');
        }
    });
}
