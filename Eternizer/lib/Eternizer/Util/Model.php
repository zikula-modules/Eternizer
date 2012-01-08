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
 * Utility implementation class for model helper methods.
 */
class Eternizer_Util_Model extends Eternizer_Util_Base_Model
{

	// build the query to searching items by userid == loggedin user
	public function getUserId()
	{
    	// get userid of loggedin user
    	if (UserUtil::isLoggedIn() === true) {
			$userid = UserUtil::getVar('uid');
    	}
    	else {
    		$userid = 1;
    	}
    	if ($userid != 1) {
    	// build where clause
    	$where = 'tbl.createdUserId = \'' . DataUtil::formatForStore($userid) . '\'';
    	}
    	else {
    		$where = '';
    	}
    	
    	return $where;
	
	}
	
	//build the where clause for allowed entries
	public function entryAllowed()
	{
		$where = 'tbl.obj_status = \'' . DataUtil::formatForStore('A') . '\'';
		
		return $where;
	}
}
