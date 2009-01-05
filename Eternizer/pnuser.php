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

Loader::requireOnce('includes/pnForm.php');
Loader::requireOnce('modules/Eternizer/pnincludes/wordwrap.php');

/**
 * Main
 * Show the guestbook
 * @param int $args['startnum']
 * @param int $args['perpage'];
 * @param int $args['tpl']
 * @return      (string)	output
 */
function Eternizer_user_main($args) {
	$tpl = FormUtil::getPassedValue('tpl', $args['tpl'], 'G');
	if (!SecurityUtil::checkPermission('Eternizer::', '::', ACCESS_READ)) {
		return LogUtil::registerPermissionError();
	}

	$startnum = FormUtil::getPassedValue('startnum', $args['startnum'], 'G');
	$perpage = FormUtil::getPassedValue('perpage', $args['perpage'], 'G');

	$config = pnModGetVar('Eternizer');
	if (!empty($perpage)) 
	    $config['perpage'] = $perpage;
	
	$entries = pnModAPIFunc('Eternizer', 'user', 'GetEntries',
				array('startnum' => $startnum-1,
					 'perpage' 	=> $perpage));

	$pnRender = pnRender::getInstance('Eternizer', false);
	
	$count = pnModAPIFunc('Eternizer', 'user', 'CountEntries');

	$pnRender->assign('startnum', $startnum);
	$pnRender->assign('count', $count);
	$pnRender->assign('config', $config);

	$entryhtml = array();
	foreach (array_keys($entries) as $k) {
            $act =& $entries[$k];
	        $act = DataUtil::formatForDisplayHTML($act);
			list($act['text']) = pnModCallHooks('item', 'transform', '', array($act['text']));
			list($act['comment']) = pnModCallHooks('item', 'transform', '', array($act['comment']));
			$act['text'] = Eternizer_WWAction($act['text']);
			$act['text'] = nl2br($act['text']);
			$act['comment'] = nl2br($act['comment']);
			
			$act['right_moderate'] = SecurityUtil::checkPermission('Eternizer::', $act['id'] . '::', ACCESS_MODERATE);
			$act['right_edit'] = SecurityUtil::checkPermission('Eternizer::', $act['id'] . '::', ACCESS_EDIT);
			$act['right_delete'] = SecurityUtil::checkPermission('Eternizer::', $act['id'] . '::', ACCESS_DELETE);
			
			$profile = array();
			foreach (array_keys($config['profile']) as $pk) {
			    $profile[$pk] = $act['profile'][$pk]; 
			}
			
			$act['profile'] = $profile;

			$pnRender->assign($act);
			if (!empty($tpl) && $pnRender->template_exists('Eternizer_user_'. DataUtil::formatForOS($tpl) .'_entry.tpl')) {
				$entryhtml[] = $pnRender->fetch('Eternizer_user_'. DataUtil::formatForOS($tpl) . '_entry.tpl');
			}
			else {
				$entryhtml[] = $pnRender->fetch('Eternizer_user_entry.tpl');
			}
	}

	$pnRender->assign('entries', $entryhtml);
    $pnRender->assign('entryarray', $entries);

	$form = pnModFunc('Eternizer', 'user', 'new', array(	'inline'	=> true));
	$pnRender->assign('form', $form===false?'':$form );

	if (!empty($tpl) && $pnRender->template_exists('Eternizer_user_'. DataUtil::formatForOS($tpl) .'_main.tpl')) {
	    return $pnRender->fetch('Eternizer_user_'.pnVarPrepForOS($tpl).'_main.tpl');
    }
    else {
		return $pnRender->fetch('Eternizer_user_main.tpl');
    }
}

/**
 * New
 * Shows the Formular for a new entry
 * @param	(bool)	$args['single'] = false
 * @return      (string)	output
 */
function Eternizer_user_new($args) {
    if (!SecurityUtil::checkPermission('Eternizer::', '::', ACCESS_COMMENT)) {
    	return $args['inline']?'':LogUtil::registerPermissionError();
    }
	$render = & FormUtil::newpnForm('Eternizer');

	if (!empty($tpl) && $pnRender->template_exists('Eternizer_user_'. DataUtil::formatForOS($tpl) .'_form.tpl')) {
		$tpl = 'Eternizer_user_'.DataUtil::formatForOS($tpl).'_form.tpl';
	} else {
		$tpl ='Eternizer_user_form.tpl';
	}

	Loader::requireOnce('modules/Eternizer/classes/Eternizer_user_newHandler.class.php');

	return $render->pnFormExecute($tpl, new Eternizer_user_newHandler($args['inline']));
}