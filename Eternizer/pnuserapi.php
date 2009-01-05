<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2007, Philipp Niethammer
 * @link http://www.guite.de
 * @version $Id$
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @author	Philipp Niethammer <webmaster@nochwer.de>
 * @package Zikula
 * @subpackage Eternizer
 */

// Include the needed classes
Loader::loadClass('Eternizer_antispam', 'modules/Eternizer/classes');


/**
 * GetEntries
 * Get persite entries beginning at startnum
 * @param	(int)	$args['startnum'] = -1
 * @param	(int)	$args['perpage']
 * @return	(array)	$entries		Array with entries
 */
function Eternizer_userapi_GetEntries($args) {
	if (!SecurityUtil::checkPermission('Eternizer::', '::', ACCESS_OVERVIEW)) {
		return LogUtil::registerPermissionError();
	}

	$startnum = (isset($args['startnum']) && is_numeric($args['startnum'])?DataUtil::formatForStore($args['startnum']):-1);
	$perpage = (isset($args['perpage']) && is_numeric($args['perpage']) && $args['perpage'] > 0?$args['perpage']:DataUtil::formatForStore(pnModGetVar('Eternizer', 'perpage')));
	$order = pnModGetVar('Eternizer', 'order');

	$pntable =& pnDBGetTables();
	$bookcolumn = &$pntable['Eternizer_entry_column'];

	$where = $bookcolumn['obj_status']." = 'A'";

	$order = $bookcolumn['cr_date'] . " " . ($order == 'desc'?'DESC':'ASC');

	$perms = array('realm' => 0,
                  'component_left'   => 'Eternizer',
                  'component_middle' => '',
                  'component_right'  => '',
                  'instance_left'    => 'id',
                  'instance_middle'  => '',
                  'instance_right'   => '',
                  'level'            => ACCESS_OVERVIEW);

	$list = DBUtil::selectObjectArray('Eternizer_entry', $where, $order, $startnum, $perpage, '', $perms);

	foreach (array_keys($list) as $k) {
		$list[$k]['profile'] = $list[$k]['__ATTRIBUTES__'];
		unset($list[$k]['__ATTRIBUTES__']);
	}

	return $list;
}

/**
 * CountEntries
 * Count the number of entries
 * @return	(int)				number of entries or false on error
 */
function Eternizer_userapi_CountEntries() {
	if (!SecurityUtil::checkPermission('Eternizer::', '::', ACCESS_OVERVIEW)) {
		return LogUtil::registerPermissionError();
	}
	
		$pntable =& pnDBGetTables();
	$bookcolumn = &$pntable['Eternizer_entry_column'];

	$where = $bookcolumn['obj_status']." = 'A'";

	$count = DBUtil::selectObjectCount('Eternizer_entry', $where);

	return $count;
}


/**
 * Write a new entry
 *
 * @author	Philipp Niethammer <webmaster@nochwer.de>
 * @param	(text)		$args['text'] the text of the entry
 * @param	(array)		$args['profile'] all profile data
 * @return	(bool)		True or False on error
 */
function Eternizer_userapi_WriteEntry($args) {
	if (!SecurityUtil::checkPermission('Eternizer::', '::', ACCESS_COMMENT)) {
		return LogUtil::registerPermissionError();
	}

	if (!$args['text']) {
		return LogUtil::registerError(_MODARGSERROR);
	}

	$profile = pnModGetVar('Eternizer', 'profile');
	foreach ($profile as $id => $state) {
		if ($state == 0) {
			unset ($profile[$id]);
		}
	} 

	$attr = array();
	foreach ($args['profile'] as $k => $v) {
		if ($profile[$k]) {
			$attr[$k] = $v;
		}
	}

	$args['__ATTRIBUTES__'] = $attr;

	unset($args['profile']);

	$args['ip'] = $_SERVER['REMOTE_ADDR'];
	
	$spammode = pnModGetVar('Eternizer', 'spammode');
	if (($spammode > 0 && !pnUserLoggedIn()) || ($spammode > 2)) {
	    $spamscan = Eternizer_antispam::scan(array('text' => $args['text'],
	                                            'profile' => $attr));
	}
	if ($spamscan == true) {
	    if (($spammode == 1 && !pnUserLoggedIn()) || $spammode == 3) {
	        $args['obj_status'] = 'M';
	        LogUtil::registerStatus(_ETERNIZER_SPAMSUSPICION);
	        LogUtil::registerStatus(_ETERNIZER_MODERATED_INFO);
	    } elseif (($spammode == 2 && !pnUserLoggedIn()) || $spammode == 4 ) {
	        LogUtil::registerStatus(_ETERNIZER_SPAMSUSPICION);
	        return LogUtil::registerError(_ETERNIZER_REJECTED_INFO);
	    }
	}

	if ($args['obj_status'] != 'M' && (($mod = pnModGetVar('Eternizer', 'moderate')) == 2 || ($mod == 1 && !pnUserLoggedIn()))) {
		$args['obj_status'] = 'M';
		LogUtil::registerStatus(_ETERNIZER_MODERATED_INFO);
	}
	
	DBUtil::insertObject($args, 'Eternizer_entry');

	pnModCallHooks('item', 'create', $args['id'], array('module' => 'Eternizer'));

	return true;
}
?>
