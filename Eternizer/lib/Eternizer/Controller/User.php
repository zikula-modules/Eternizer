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

// need to find a better way to load pnForm here since the path changes and we also use autoloading in Zikula 1.3.0
//Loader::requireOnce('includes/pnForm.php');
Loader::requireOnce('modules/Eternizer/includes/wordwrap.php');

class Eternizer_Controller_User extends Zikula_AbstractController {

/**
 * Main
 * Show the guestbook
 * @param  int $args['startnum']
 * @param  int $args['perpage'];
 * @param  int $args['tpl']
 * @return (string)    output
 */
public function main($args) {
	
    $tpl = FormUtil::getPassedValue('tpl', $args['tpl'], 'G');
    if (!SecurityUtil::checkPermission('Eternizer::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();
    }

    $dom = ZLanguage::getModuleDomain('Eternizer');

    $startnum = FormUtil::getPassedValue('startnum', $args['startnum'], 'G');
    $perpage = FormUtil::getPassedValue('perpage', $args['perpage'], 'G');

    $config = ModUtil::getVar('Eternizer');
    if (!empty($perpage))
    $config['perpage'] = $perpage;

    $entries = ModUtil::apiFunc('Eternizer', 'user', 'GetEntries', array('startnum' => $startnum-1, 'perpage' => $perpage));

    $count = ModUtil::apiFunc('Eternizer', 'user', 'CountEntries');

    $this->view->assign('startnum', $startnum);
    $this->view->assign('count', $count);
    $this->view->assign('config', $config);

    $entryhtml = array();
    foreach (array_keys($entries) as $k) {
        $act =& $entries[$k];
        $act = DataUtil::formatForDisplayHTML($act);
        $act['text'] = Eternizer_WWAction($act['text']);
        $act['text'] = nl2br($act['text']);
        $act['comment'] = nl2br($act['comment']);
        list($act['text']) = ModUtil::callHooks('item', 'transform', '', array($act['text']));
        list($act['comment']) = ModUtil::callHooks('item', 'transform', '', array($act['comment']));

        $act['right_moderate'] = SecurityUtil::checkPermission('Eternizer::', $act['id'] . '::', ACCESS_MODERATE);
        $act['right_edit'] = SecurityUtil::checkPermission('Eternizer::', $act['id'] . '::', ACCESS_EDIT);
        $act['right_delete'] = SecurityUtil::checkPermission('Eternizer::', $act['id'] . '::', ACCESS_DELETE);

        $profile = array();
        foreach (array_keys($config['profile']) as $pk) {
            $profile[$pk] = $act['profile'][$pk];
        }

        $act['profile'] = $profile;
        $act['avatarpath'] = ModUtil::getVar('Users', 'avatarpath');

        $this->view->assign($act);
        if (!empty($tpl) && $this->view->template_exists('Eternizer_user_'. DataUtil::formatForOS($tpl) .'_entry.tpl')) {
            $entryhtml[] = $this->view->fetch('Eternizer_user_'. DataUtil::formatForOS($tpl) . '_entry.tpl');
        }
        else {
            $entryhtml[] = $this->view->fetch('Eternizer_user_entry.tpl');
        }
    }

    $this->view->assign('entries', $entryhtml);
    $this->view->assign('entryarray', $entries);

    $form = ModUtil::func('Eternizer', 'user', 'create', array( 'inline' => true));
    $this->view->assign('form', $form===false?'':$form );

    if (!empty($tpl) && $this->view->template_exists('Eternizer_user_'. DataUtil::formatForOS($tpl) .'_main.tpl')) {
        return $this->view->fetch('Eternizer_user_'. DataUtil::formatForOS($tpl).'_main.tpl');
    }
    else {
        return $this->view->fetch('Eternizer_user_main.tpl');
    }
}

/**
 * New
 * Shows the Formular for a new entry
 * @param  (bool)    $args['single'] = false
 * @return (string)  output
 */
public function create($args) {
    if (!SecurityUtil::checkPermission('Eternizer::', '::', ACCESS_COMMENT)) {
        return false;
    }
    
    $inline = $args['inline'];

    $dom = ZLanguage::getModuleDomain('Eternizer');

    $form = FormUtil::newForm('Eternizer', $this);

    if (!empty($tpl) && $this->view->template_exists('Eternizer_user_'. DataUtil::formatForOS($tpl) .'_form.tpl')) {
        $tpl = 'Eternizer_user_'.DataUtil::formatForOS($tpl).'_form.tpl';
    } else {
        $tpl ='Eternizer_user_form.tpl';
    }

    //Loader::requireOnce('modules/Eternizer/classes/Eternizer_user_newHandler.class.php');
    $args = array('inline' => true);
    return $form->execute($tpl, new Eternizer_Form_Handler_User_newHandler());
}
}