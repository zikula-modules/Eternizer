<?php
/**
 * Eternizer.
 *
 * @copyright Michael Ueberschaer
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package Eternizer
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://www.webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Sat Dec 31 13:59:03 CET 2011.
 */

/**
 * Utility implementation class for controller helper methods.
 */
class Eternizer_Util_Controller extends Eternizer_Util_Base_Controller
{
    /**
     *
     * Helper method for setting the modvars
     */
    public static function setModVars()
    {
        ModUtil::setVar('Eternizer', 'pagesize', 10);
        ModUtil::setVar('Eternizer', 'mail', '');
        ModUtil::setVar('Eternizer', 'order', 'descending');
        ModUtil::setVar('Eternizer', 'moderate', 'guests');
        ModUtil::setVar('Eternizer', 'formposition', 'below');
        ModUtil::setVar('Eternizer', 'ipsave', false);
        ModUtil::setVar('Eternizer', 'editentries', false);
        ModUtil::setVar('Eternizer', 'period', 12);
        ModUtil::setVar('Eternizer', 'simplecaptcha', false);

        return true;
    }

    /**
     * Get list of form positions.
     */
    public static function getKindOfStatus()
    {
        $dom = ZLanguage::getModuleDomain('Eternizer');
        $moderation = array();
        $moderation[] = array('value' => 'A', 'text' => __('accepted and published', $dom));
        $moderation[] = array('value' => 'M', 'text' => __('to moderate', $dom));
        $moderation[] = array('value' => 'D', 'text' => __('denied', $dom));
        return $moderation;
    }
}
