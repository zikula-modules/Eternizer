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
class Eternizer_Controller_Import extends Zikula_AbstractController {
/**
 * List installed supported books
 *
 * @author    Philipp Niethammer <webmaster@nochwer.de>
 * @return    output
 */
public function main () {
    if (!SecurityUtil::checkPermission('Eternizer::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    $books = ModUtil::apiFunc('Eternizer', 'import', 'getInstalledBooks', array());

    $pnRender = & pnRender::getInstance('Eternizer', false);

    $pnRender->assign('books', $books);

    return $pnRender->fetch('Eternizer_import_main.tpl');
}

/**
 * Redirect to the import of the specified module
 *
 * @author    Philipp Niethammer <webmaster@nochwer.de>
 * @return    output
 */
public function display () {
    if (!SecurityUtil::checkPermission('Eternizer::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    $plg = FormUtil::getPassedValue('plugin');

    $obj =& ModUtil::apiFunc('Eternizer', 'import', 'getObject', array('name' => $plg));

    if (!is_object($obj)) {
        return pnRedirect(pnModURL('Eternizer', 'import', 'main'));
    }

    return $obj->display();
}

public function import()
{
    if (!SecurityUtil::checkPermission('Eternizer::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    $name         = FormUtil::getPassedValue('plugin');
    $config        = (bool) FormUtil::getPassedValue('config');
    $data        = (bool) FormUtil::getPassedValue('data');
    $deactivate    = (bool) FormUtil::getPassedValue('deactivate');
    $delete        = (bool) FormUtil::getPassedValue('delete');

    ModUtil::apiFunc('Eternizer', 'import', 'import', concat('name', 'config', 'data', 'deactivate', 'delete'));

    return pnRedirect(pnModURL('Eternizer', 'import', 'main'));
}
}
