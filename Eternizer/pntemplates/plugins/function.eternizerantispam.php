<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2008, philipp
 * @link http://code.zikula.org/eternizer
 * @version $Id$
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula
 * @subpackage Eternizer
*/

function smarty_function_eternizerantispam($params, &$smarty) {
    $spammode = pnModGetVar('Eternizer', 'spammode');
    //Only available with spamFree!
    if (pnModAvailable('spamFree') && ($spammode > 2 || ($spammode > 0 && !pnUserLoggedIn()))) {
        if (!function_exists('smarty_function_spamfree_displayform')) {
            Loader::requireOnce('modules/spamFree/pntemplates/plugins/function.spamfree_displayform.php');
        }
        return smarty_function_spamfree_displayform($params, $smarty);
    }
    
    return '';
}