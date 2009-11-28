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

/**
 * Get objects of import classes
 *
 * @author Philipp Niethammer <webmaster@nochwer.de>
 * @param  (string) $args['name'] Name of the Plugin
 * @return (mixed)  single object or array of objects
 */
function Eternizer_importapi_getObject ($args) {
    if (!SecurityUtil::checkPermission('Eternizer::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    static $plgs = array();

    if (array_key_exists('name', $args) && array_key_exists($args['name'], $plgs)) {
        return $plgs[$args['name']];
    }
    Loader::LoadClass('FileUtil');
    $files = FileUtil::getFiles('modules/Eternizer/classes', false, true, 'class.php', false);

    foreach ($files as $file) {
        if (substr($file, 0, 17) == 'Eternizer_Import_' && substr($file, 17, -10) != 'importHandler') {
            $name = substr($file, 0, -10);
            $class = Loader::loadClass($name, 'modules/Eternizer/classes');
            $plgs[substr($name, 17)] = new $name();
        }
    }

    if (array_key_exists('name', $args)) {
        return $plgs[$args['name']];
    }

    return $plgs;

}

/**
 * Search for installed supported guestbooks
 *
 * @author    Philipp Niethammer <webmaster@nochwer.de>
 * @return    (array)    Array of installed supported guestbooks
 */
function Eternizer_importapi_getInstalledBooks ($args) {

    if (!SecurityUtil::checkPermission('Eternizer::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    $books =& pnModAPIFunc('Eternizer', 'import', 'getObject');

    $inst = array();

    $keys = array_keys($books);
    foreach ($keys as $k) {
        if ($books[$k]->available()) {
            $inst[$k] = $books[$k]->getName();
        }
    }

    return $inst;
}

/**
 * Import data
 *
 * @author Philipp Niethammer <webmaster@nochwer.de>
 * @param  (string) $args['name'] Plugin name
 * @param  (bool)   $args['config'] Import config
 * @param  (bool)   $args['data'] Import data
 * @param  (bool)   $args['deactivate'] deactivate old module
 * @param  (bool)   $args['delete'] delete old module
 * @return (bool)
 */
function Eternizer_importapi_import($args)
{
    $dom = ZLanguage::getModuleDomain('Eternizer');
    if (!SecurityUtil::checkPermission('Eternizer::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    $obj =& pnModAPIFunc('Eternizer', 'import', 'getObject', array('name' => $args['plugin']));

    if (!is_object($obj)) {
        return LogUtil::registerArgsError();
    }

    if ($args['config']) {
        $configvars = array_keys(pnModGetVar('Eternizer'));
        foreach ($obj->getConfig() as $k => $v) {
            if (array_search($k, $configvars)) {
                pnModSetVar('Eternizer', $k, $v);
            }
        }
    }

    if ($args['data']) {
        $profile = pnModGetVar('Eternizer', 'profile');

        $items = array();
        foreach ($obj->getData() as $i) {
            $i['__ATTRIBUTES__'] = array();
            foreach ($profile as $k => $v) {
                if (isset($i['profile'][$k])) {
                    $i['__ATTRIBUTES__'][$k] = $i['profile'][$k];
                }
            }
            unset($i['profile']);
            $i['cr_uid'] = isset($i['cr_uid'])?$i['cr_uid']:1;

            $items[] = $i;
        }

        DBUtil::insertObjectArray($items, 'Eternizer_entry', 'id', true);
    }

    if ($args['deactivate']) {
        $obj->deactivate();
    }

    if ($args['delete']) {
        $obj->delete();
    }

    LogUtil::registerStatus(__('Done! Import successful.', $dom));

    return true;
}

/**
 * Import from 'guestbook'
 *
 * @author  Philipp Niethammer <webmaster@nochwer.de>
 * @param   (bool)    $args['approvedonly']
 * @param   (bool)    $args['setting']
 * @return  (bool)
 */

/** Deactivated because of name conflicts
 function Eternizer_importapi_from_guestbook ($args) {
 extract($args);

 if (!pnSecAuthAction(0, 'Eternizer::', '::', ACCESS_ADMIN)) {
 return SetErrorMsg(__('Sorry! No authorization to access this module.', $dom));
 }


 $dbconn =& pnDBGetConn(true);
 $pntable =& pnDBGetTables();

 $fromtable = $pntable['guestbook_posts'];
 $fromcolumn = &$pntable['guestbook_posts_column'];

 $totable = $pntable['Eternizer_entry'];
 $tocolumn = &$pntable['Eternizer_entry_column'];

 if ($approvedonly === true) {
 $where = "WHERE     $fromcolumn[approved]    = '1'";
 }

 $sql = "SELECT        $fromcolumn[user_id],
 $fromcolumn[user_name],
 $fromcolumn[user_ip],
 $fromcolumn[submit_date],
 $fromcolumn[message]
 FROM        $fromtable
 $where";

 if ($res = $dbconn->Execute($sql) === false)
 return SetErrorMsg(__FILE__.", ".__LINE__." (SQL-Error): ".$dbconn->ErrorMsg());

 for (; !$result->EOF; $result->MoveNext()) {
 $flds = array();
 list ($flds[$tocolumn['uid']], $flds[$tocolumn['name']], $flds[$tocolumn['ip']], $flds[$tocolumn['date']], $flds[$tocolumn['text']]) = $res->fields;


 if ($dbconn->AutoExecute($totable, $flds, 'INSERT') === false)
 return SetErrorMsg(__FILE__.", ".__LINE__." (SQL-Error): ".$dbconn->ErrorMsg());
 }

 if ($setting === true) {
 pnModSetVar('Eternizer', 'perpage', pnModGetVar('guestbook', 'items_per_page'));
 }

 return true;
 }
 */
