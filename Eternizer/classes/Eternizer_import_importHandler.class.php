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

class Eternizer_import_importHandler
{

    var $plugin;

    function Eternizer_import_importHandler($plugin)
    {
        $this->plugin = $plugin;
        return $this;
    }

    function initialize(&$render)
    {
        $dom = ZLanguage::getModuleDomain('Eternizer');
        if (!SecurityUtil::checkPermission('Eternizer::', '::', ACCESS_ADMIN)) {
            return $render->pnFormSetErrorMsg(__('Sorry! No authorization to access this module.', $dom));
        }

        $render->assign('plugin', $this->plugin->getName());
        return true;
    }

    function handleCommand(&$render, $args)
    {
        switch($args['commandName']) {
            case 'import':
                if (!$render->pnFormIsValid())
                return false;

                $data = $render->pnFormGetValues();

                pnModAPIFunc('Eternizer', 'import', 'import',
                array('plugin'    => $this->plugin->getName(),
                                'config'    => $data['config'],
                                'data'    => $data['data'],
                                'deactivate'    => $data['deactivate'],
                                'delete'    => $data['delete']));

            default:
                return $render->pnFormRedirect(pnModURL('Eternizer', 'import', 'main'));
        }
    }
}
