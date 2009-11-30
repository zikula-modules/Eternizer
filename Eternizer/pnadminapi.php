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

/**
 * change status of an entry
 *
 * @author    Philipp Niethammer <webmaster@nochwer.de>
 * @param     (mixed) $args['id']
 * @param     (char)  $args['status']
 * @return    (bool)
 */
function Eternizer_adminapi_changeStatus($args)
{
    if (!isset($args['id']) || (!is_numeric($args['id']) && !is_array($args['id']))) {
        return LogUtil::registerArgsError();
    }

    if (array_search($args['status'], array('M', 'A')) === false) {
        return LogUtil::registerArgsError();
    }

    if (is_numeric($args['id'])) {
        $args['id'] = array($args['id']);
    }

    if (!SecurityUtil::checkPermission('Eternizer::', (is_numeric($args['id']) ? $args['id'] : '') . '::', ACCESS_DELETE)) {
        return LogUtil::registerPermissionError();
    }

    $objs = array();
    foreach ($args['id'] as $id) {
        $objs[] = array('id' => $id, 'obj_status' => $args['status']);
    }

    DBUtil::updateObjectArray($objs, 'Eternizer_entry');

    return true;
}

/**
 * Delete an entry
 *
 * @author    Philipp Niethammer <webmaster@nochwer.de>
 * @param     (mixed)    id
 * @return    (bool)
 */
function Eternizer_adminapi_DelEntry($args)
{
    $dom = ZLanguage::getModuleDomain('Eternizer');
    if (!isset($args['id']) || (!is_numeric($args['id']) && !is_array($args['id']))) {
        return LogUtil::registerArgsError();
    }

    if (is_numeric($args['id'])) {
        $args['id'] = array($args['id']);
    }

    if (!SecurityUtil::checkPermission('Eternizer::', (is_numeric($args['id']) ? $args['id'] : '') . '::', ACCESS_DELETE)) {
        return LogUtil::registerPermissionError();
    }

    DBUtil::deleteObjectsFromKeyArray(array_flip($args['id']), 'Eternizer_entry');

    pnModCallHooks('item', 'delete', $args['id'], array('module' => 'Eternizer'));

    return true;
}

/**
 * Edit an entry
 *
 * @author   Philipp Niethammer <webmaster@nochwer.de>
 * @param    (int)        $args['id']
 * @param    (text)       $args['text']
 * @param    (text)       $args['comment']
 * @param    (array)      $args['profile']
 * @return   (bool)
 */
function Eternizer_adminapi_EditEntry($args)
{
    $dom = ZLanguage::getModuleDomain('Eternizer');
    if (!isset($args['id']) || !is_numeric($args['id'])) {
        return LogUtil::registerArgsError();
    }

    if (!SecurityUtil::checkPermission('Eternizer::', $args['id'] . '::', ACCESS_MODERATE)) {
        return LogUtil::registerPermissionError();
    }

    if (!$args['text']) {
        return LogUtil::registerArgsError();
    }

    $profile = pnModGetVar('Eternizer', 'profile');

    $attr = array();
    foreach ($profile as $k => $v) {
        if (isset($args['profile'][$k])) {
            $attr[$k] = $args['profile'][$k];
        }
    }

    if (count($attr) > 0) {
        $args['__ATTRIBUTES__'] = $attr;
    }
    unset($args['profile']);

    if (!SecurityUtil::checkPermission('Eternizer::', $args['id'] . '::', ACCESS_EDIT)) {
        $args = array('comment' => $args['comment'], 'id' => $args['id']);
    }

    DBUtil::updateObject($args, 'Eternizer_entry');

    pnModCallHooks('item', 'update', $args['id'], array('module' => 'Eternizer'));

    return true;
}

/**
 * Get an entry
 *
 * @author    Philipp Niethammer <webmaster@nochwer.de>
 * @param     (int)        $args['id']
 * @return    (array)      array with infos or false
 */
function Eternizer_adminapi_GetEntry($args)
{
    $dom = ZLanguage::getModuleDomain('Eternizer');
    if (!isset($args['id']) || !is_numeric($args['id'])) {
        return LogUtil::registerArgsError();
    }

    if (!SecurityUtil::checkPermission('Eternizer::', $args['id'] . '::', ACCESS_EDIT)) {
        return LogUtil::registerPermissionError();
    }

    $entry = DBUtil::selectObjectByID('Eternizer_entry', $args['id']);

    $entry['profile'] = $entry['__ATTRIBUTES__'];
    unset($entry['__ATTRIBUTES__']);

    return $entry;
}

/**
 * Get all entries, also moderated
 *
 * @return array Array of items
 */
function Eternizer_adminapi_getEntries($args)
{
    if (!SecurityUtil::checkPermission('Eternizer::', $args['id'] . '::', ACCESS_MODERATE)) {
        return LogUtil::registerPermissionError();
    }

    $startnum = (isset($args['startnum']) && is_numeric($args['startnum']) ? DataUtil::formatForStore($args['startnum']) : -1);
    $perpage = (isset($args['perpage']) && is_numeric($args['perpage']) && $args['perpage'] > 0 ? $args['perpage'] : DataUtil::formatForStore(pnModGetVar('Eternizer', 'perpage')));
    $order = pnModGetVar('Eternizer', 'order');

    $pntable = & pnDBGetTables();
    $bookcolumn = &$pntable['Eternizer_entry_column'];

    $order = $bookcolumn['cr_date'] . " " . ($order == 'desc' ? 'DESC' : 'ASC');

    $perms = array(
        'realm' => 0,
        'component_left' => 'Eternizer',
        'component_middle' => '',
        'component_right' => '',
        'instance_left' => 'id',
        'instance_middle' => '',
        'instance_right' => '',
        'level' => ACCESS_MODERATE);

    $list = DBUtil::selectObjectArray('Eternizer_entry', '', $order, $startnum, $perpage, '', $perms);

    foreach (array_keys($list) as $k) {
        $list[$k]['profile'] = $list[$k]['__ATTRIBUTES__'];
        unset($list[$k]['__ATTRIBUTES__']);
    }

    return $list;
}

/**
 * get available admin panel links
 *
 * @return array     array of admin links
 */
function Eternizer_adminapi_getlinks()
{
    $dom = ZLanguage::getModuleDomain('Eternizer');
    $links = array();

    if (SecurityUtil::checkPermission('Eternizer::', '::', ACCESS_ADMIN)) {
        $links[] = array(
            'url' => pnModURL('Eternizer', 'admin', 'adminView'),
            'text' => __('Admin view', $dom),
            'title' => __('Administrative guestbook view', $dom),
            'id' => 'Eternizer_adminview');
        $links[] = array('url' => pnModURL('Eternizer', 'import', 'main'),
                         'text' => __('Import', $dom),
                         'title' => __('Import from other guestbooks', $dom),
                         'id' => 'Eternizer_import');
        $links[] = array('url' => pnModURL('Eternizer', 'admin', 'config'),
                         'text' => __('Settings', $dom),
                         'title' => __("Eternizer module configuration", $dom),
                         'id' => 'Eternizer_config')
        ;
    }

    return $links;
}
