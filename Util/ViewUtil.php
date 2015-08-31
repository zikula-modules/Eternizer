<?php
/**
 * Eternizer.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://www.webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.7.0 (http://modulestudio.de).
 */

namespace MU\EternizerModule\Util;

use MU\EternizerModule\Util\Base\ViewUtil as BaseViewUtil;

use DateUtil;
use LogUtil;
use ModUtil;
use SecurityUtil;
use ServiceUtil;
use UserUtil;

/**
 * Utility implementation class for view helper methods.
 */
class ViewUtil extends BaseViewUtil
{
    /**
     * This method checks if user must edit this posting - creater of the issue
     */
    public static function getStateOfEditOfEntry($entryid, $kind = 1)
    {
    	$serviceManager = ServiceUtil::getManager();
    	//get repository for Entries
    	$repository = $serviceManager->get('mueternizermodule.' . 'entry' . '_factory')->getRepository();
        
        // get entry
        $entry = $repository->selectById($entryid);

        // get userid of user created this posting
        $createdUserId = $entry->getCreatedUserId();
        // get created Date
        $createdDate = $entry->getCreatedDate();
        $createdDate = $createdDate->getTimestamp();

        // get the actual time
        $actualTime = DateUtil::getDatetime();
        // get modvar editTime
        $editTime = ModUtil::getVar('MUEternizerModule', 'period');

        $diffTime = DateUtil::getDatetimeDiff($createdDate, $actualTime);
        $diffTimeHours = $diffTime['d'] * 24 + $diffTime['h'];

        if (UserUtil::isLoggedIn()== true) {
            $userid = UserUtil::getVar('uid');
        } else {
            $out = '';
        }

        if ($createdUserId == $userid && ($diffTimeHours < $editTime) ) {
            if ($kind == 1) {
                $serviceManager = ServiceUtil::getManager();
                // generate an auth key to use in urls
                $csrftoken = SecurityUtil::generateCsrfToken($serviceManager, true);
                $url = ModUtil::url('MUEternizerModule', 'entry', 'edit', array('lct' => 'user', 'id' => $entryid, 'token' => $csrftoken));
                $title = __('You have permissions to edit this issue!');
                $out = "<a title='{$title}' id='eternizer-user-entry-edit-creater' href='{$url}'>
                <img src='/images/icons/extrasmall/xedit.png' />
                </a>";
            } else {
                $out = true;

            }
        } else {
            if ($kind == 1) {
                $out = '';
            } else {
                $out = false;
            }
        }
		
        return $out;
    }
}
