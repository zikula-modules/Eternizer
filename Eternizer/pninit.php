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

// need a better way to load this - from 1.3 we use autoloading
Loader::requireOnce('includes/pnForm.php');

function Eternizer_init_getDefaultConfig()
{
    $dom = ZLanguage::getModuleDomain('Eternizer');
    $profile = array(
    array(
          'pos'         => 1, //Position in formular. ASC
          'title'       => __('Name', $dom),
          'type'        => 'name', //Field type
          'mandatory'   => 2, //0 optional, 1 guests, 2 all
          'profile'     => '_UREALNAME'), //field name in profile
    array(
          'pos'         => 2,
          'title'       => __('E-mail address', $dom),
          'type'        => 'mail',
          'mandatory'   => 2,
          'profile'     => '_UFAKEMAIL'),
    array(
          'pos'         => 3,
          'title'       => __('Homepage', $dom),
          'type'        => 'url',
          'mandatory'   => 0,
          'profile'     => '_YOURHOMEPAGE'),
    array(
          'pos'         => 4,
          'title'       => __('Location', $dom),
          'type'        => 'text',
          'mandatory'   => 0,
          'profile'     => '_YLOCATION')
    );

    $config = array('perpage'        => 10,
                    'order'          => 'desc',
                    'spammode'       => 1,
                    'moderate'       => 0,
                    'useuserprofile' => 1,
                    'wwaction'       => 'wrap',
                    'wwlimit'        => 60,
                    'wwshortto'      => 50,
                    'notify'         => 0,
                    'notifymail'     => pnConfigGetVar('adminmail', 'email@example.org'),
                    'profile'        => $profile,
                    'titlefield'     => 0);

    return $config;
}

/**
 * @return       bool       true
 */
function Eternizer_init() {
    DBUtil::CreateTable('Eternizer_entry');

    // Forget about configuration.. It is defect
    pnModDelVar('Eternizer');
    $config = Eternizer_init_getDefaultConfig();
    foreach ($config as $k => $v)
    pnModSetVar('Eternizer', $k, $v);

    // Initialisation successful
    return true;
}

/**
 * @return       bool       true
 */
function Eternizer_upgrade($oldversion) {
    switch ($oldversion) {
        case '1.0a':
        case '1.0':
        case '1.1':
            break;
    }

    return true;
}


/**
 * @return       bool       true
 */
function Eternizer_delete() {
    DBUtil::dropTable('Eternizer_entry');

    pnModDelVar('Eternizer');

    // Deletion successful
    return true;
}

/**
 * interactive installation procedure
 *
 * @author Philipp Niethammer
 * @return pnRender output
 */
function Eternizer_init_interactiveinit()
{
    if (!SecurityUtil::checkPermission('::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    $pnRender = & pnRender::getInstance('Eternizer', null);
    return $pnRender->fetch('Eternizer_init_interactive.tpl');
}

/**
 * step 2 of interactive initializing
 *
 * @author Philipp Niethammer
 * @return pnRender output
 */
function Eternizer_init_step2()
{
    if (!SecurityUtil::checkPermission('::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    $render = FormUtil::newpnForm('Eternizer');

    Loader::requireOnce('modules/Eternizer/classes/Eternizer_admin_configHandler.class.php');

    return $render->pnFormExecute('Eternizer_admin_config.tpl', new Eternizer_admin_configHandler(true));
}

/**
 * step 2 of interactive initializing
 *
 * @author Philipp Niethammer
 * @return pnRender output
 */
function Eternizer_init_step3()
{
    if (!SecurityUtil::checkPermission('::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    $pnRender = & pnRender::getInstance('Eternizer', null);

    $pnRender->assign('authid', SecurityUtil::generateAuthKey('Modules'));
    return $pnRender->fetch('Eternizer_init_step3.tpl');
}

