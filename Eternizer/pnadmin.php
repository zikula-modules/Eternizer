<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2007, Philipp Niethammer
 * @link http://www.guite.de
 * @version $Id$
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @author    Philipp Niethammer <webmaster@nochwer.de>
 * @package Zikula
 * @subpackage Eternizer
 */


// TODO A - need a better way to load this, it will break from 1.3.0 otherwise... we use autoloaders in Zikula 1.3.0 and there is no /includes/ dir
Loader::requireOnce('includes/pnForm.php');

/**
 * replace for php core function "is_writeable()"
 *
 * @param string        $path        File
 * @return bool
 */
function is__writeable($path)
{
    if ($path{strlen($path) - 1} == '/')
    return is__writable($path . uniqid(mt_rand()) . '.tmp');

    if (file_exists($path)) {
        if (!($f = @fopen($path, 'r+')))
        return false;

        fclose($f);
        return true;
    }

    if (!($f = @fopen($path, 'w')))
    return false;
    fclose($f);
    unlink($path);
    return true;
}

/**
 * Show the main administration page
 *
 * @author    Philipp Niethammer <webmaster@nochwer.de
 * @return    output
 */
function Eternizer_admin_main()
{
    if (!SecurityUtil::checkPermission('Eternizer::', '::', ACCESS_ADMIN))
    return LogUtil::registerPermissionError();

    $pnRender = & pnRender::getInstance('Eternizer', false);

    return $pnRender->fetch('Eternizer_admin_main.tpl');
}

/**
 * Show the configuration Variables
 *
 * @author    Philipp Niethammer <webmaster@nochwer.de>
 * @return    output
 */
function Eternizer_admin_config()
{
    $render = FormUtil::newpnForm('Eternizer');

    Loader::requireOnce('modules/Eternizer/classes/Eternizer_admin_configHandler.class.php');

    $modinfo = pnModGetInfo(pnModGetIDFromName('Eternizer'));
    $newestversion = file('http://www.guite.de/downloads/Eternizer_version.txt');
    if (!$newestversion)
    $newestversion = array(0 => $modinfo['version']);

    $render->assign('newestversion', trim($newestversion[0]));

    return $render->pnFormExecute('Eternizer_admin_config.tpl', new Eternizer_admin_configHandler());
}

/**
 * Show a shorted list of entries ("Admin view")
 *
 * @author    Philipp Niethammer <webmaster@nochwer.de>
 * @return    pnRender output
 */
function Eternizer_admin_adminView()
{
    $dom = ZLanguage::getModuleDomain('Eternizer');
    $stati = array('A' => __('Active', $dom), 'M' => __('Moderated', $dom));
    if (!SecurityUtil::checkPermission('Eternizer::', '::', ACCESS_MODERATE))
    return LogUtil::registerPermissionError();

    $startnum = FormUtil::getPassedValue('startnum', $args['startnum'], 'G');
    $perpage = FormUtil::getPassedValue('perpage', $args['perpage'], 'G');

    $list = pnModAPIFunc('Eternizer', 'admin', 'getEntries', array('startnum' => $startnum-1, 'perpage' => $perpage));
    $count = pnModAPIFunc('Eternizer', 'user', 'CountEntries');

    foreach (array_keys($list) as $k) {
        $list[$k]['rights'] = array(
            'modify' => SecurityUtil::checkPermission('Eternizer::', $list[$k]['id'] . '::', ACCESS_EDIT),
            'delete' => SecurityUtil::checkPermission('Eternizer::', $list[$k]['id'] . '::', ACCESS_DELETE));
        $list[$k]['status'] = $stati[$list[$k]['obj_status']];
    }

    $rights = array(
        'modify' => SecurityUtil::checkPermission('Eternizer::', '*::', ACCESS_EDIT),
        'delete' => SecurityUtil::checkPermission('Eternizer::', '*::', ACCESS_DELETE));

    $pnRender = & pnRender::getInstance('Eternizer', false);
    $pnRender->assign('startnum', $startnum);
    $pnRender->assign('count', $count);
    $pnRender->assign('goback', DataUtil::encode(pnModURL('Eternizer', 'admin', 'adminView')));
    $pnRender->assign('config', pnModGetVar('Eternizer'));
    $pnRender->assign('list', $list);
    $pnRender->assign('rights', $rights);

    return $pnRender->fetch('Eternizer_admin_adminView.tpl');
}

/**
 * Redirect from AdminView
 */
function Eternizer_admin_adminViewRedirect()
{
    $action = array_keys(FormUtil::getPassedValue('action', '', 'P'));
    $selected = array_keys(FormUtil::getPassedValue('selected', '', 'P'));
    $goback = DataUtil::encode(pnModURL('Eternizer', 'admin', 'adminView'));
    switch (reset($action)) {
        case 'delete':
            $url = pnModURL('Eternizer', 'admin', 'suppress', array(
                'id' => $selected,
                'goback' => $goback));
            break;
        case 'activate':
            $url = pnModURL('Eternizer', 'admin', 'changeStatus', array(
                'status' => 'A',
                'id' => $selected,
                'goback' => $goback));
            break;
        case 'moderate':
            $url = pnModURL('Eternizer', 'admin', 'changeStatus', array(
                'status' => 'M',
                'id' => $selected,
                'goback' => $goback));
            break;
        default:
            $url = pnModURL('Eternizer', 'admin', 'adminView');
    }

    return pnRedirect($url);
}

/**
 * Change status of an item
 */
function Eternizer_admin_changeStatus()
{
    $id = FormUtil::getPassedValue('id');
    $status = FormUtil::getPassedValue('status');

    if (array_search($status, array('M', 'A')) === false) {
        return LogUtil::registerArgsError(pnModURL('Eternizer', 'user', 'main'));
    }

    if (empty($id) || (!is_numeric($id) && !is_array($id))) {
        return LogUtil::registerArgsError(pnModURL('Eternizer', 'user', 'main'));
    }

    if (!SecurityUtil::checkPermission('Eternizer::', (is_numeric($id) ? $id : '') . '::', ACCESS_MODERATE))
    return LogUtil::registerPermissionError();

    pnModAPIFunc('Eternizer', 'admin', 'changeStatus', compact('id', 'status'));

    $url = DataUtil::decode(FormUtil::getPassedValue('goback'));
    if (empty($url))
    $url = pnModURL('Eternizer', 'user', 'main');
    return pnRedirect($url);
}

/**
 * Ask if realy delete
 *
 * @author    Philipp Niethammer <webmaster@nochwer.de>
 * @return    output
 */
function Eternizer_admin_suppress()
{
    $dom = ZLanguage::getModuleDomain('Eternizer');
    $id = FormUtil::getPassedValue('id');

    if (empty($id) || (!is_numeric($id) && !is_array($id))) {
        return LogUtil::registerArgsError();
    }

    if (!SecurityUtil::checkPermission('Eternizer::', (is_numeric($id) ? $id : '') . '::', ACCESS_DELETE))
    return LogUtil::registerPermissionError();

    $pnRender = & pnRender::getInstance('Eternizer', false);
    $url = DataUtil::decode(FormUtil::getPassedValue('goback'));
    if (empty($url)) {
        $url = pnModURL('Eternizer', 'user', 'main');
    }
    $pnRender->assign('goback', FormUtil::getPassedValue('goback'));
    $pnRender->assign('cancelurl', $url);
    $pnRender->assign('id', serialize($id));
    return $pnRender->fetch('Eternizer_admin_suppress.tpl');
}

/**
 * Delete
 * Delete an entry
 * @return    output
 */
function Eternizer_admin_delete()
{
    $id = FormUtil::getPassedValue('id', False, 'GETPOST');
	if (DataUtil::is_serialized($id)) {
		$id = unserialize($id);
	}
    if (empty($id) || (!is_numeric($id) && !is_array($id))) {
        LogUtil::registerArgsError();
    }
    if (!SecurityUtil::checkPermission('Eternizer::', (is_numeric($id) ? $id : '') . '::', ACCESS_DELETE))
    return LogUtil::registerPermissionError();

    pnModAPIFunc('Eternizer', 'admin', 'DelEntry', array('id' => $id));

    $url = DataUtil::decode(FormUtil::getPassedValue('goback'));
    if (empty($url))
    $url = pnModURL('Eternizer', 'user', 'main');
    return pnRedirect($url);
}

/**
 * Modify
 * Show the Modify-Form
 * @return    output
 */
function Eternizer_admin_modify()
{
    if (!SecurityUtil::checkPermission('Eternizer::', '::', ACCESS_MODERATE)) {
        return LogUtil::registerPermissionError();
    }

    $render = FormUtil::newpnForm('Eternizer');

    Loader::requireOnce('modules/Eternizer/classes/Eternizer_admin_modifyHandler.class.php');

    return $render->pnFormExecute('Eternizer_admin_modify.tpl', new Eternizer_admin_modifyHandler());
}
