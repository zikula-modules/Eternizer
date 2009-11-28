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

class Eternizer_Import_Guestbook
{

    function getName()
    {
        return 'Postguestbook';
    }

    function available()
    {
        return (bool) pnModAvailable('postguestbook');
    }

    function display()
    {
        $render = FormUtil::newpnForm('Eternizer');

        Loader::requireOnce('modules/Eternizer/classes/Eternizer_import_importHandler.class.php');

        return $render->pnFormExecute('Eternizer_import_Default.tpl', new Eternizer_import_importHandler(&$this));
    }

    function getConfig()
    {
        $config = array('perpage'    => pnModGetVar('postguestbook', 'entries_per_page'));

        return $config;
    }

    function getData()
    {
        pnModDBInfoLoad('Guestbook');

        $res = DBUtil::selectObjectArray('Guestbook');

        $list = array();

        foreach ($res as $item) {
            $obj = array();
            foreach ($item as $k => $v) {
                switch ($k) {
                    case 'comment':
                        $obj['text'] = $v;
                        break;
                    case 'date':
                        $obj['cr_date'] = DateUtil::getDatetime($v);
                        break;
                    case 'nukeuser':
                        $obj['cr_uid'] = $v;
                    default:
                        $profile = array('name'        => 0,
                                    'email'        => 1,
                                    'url'        => 2,
                                    'location'    => 3);

                        $obj['profile'][$profile[$k]] = $v;
                }
            }
            $list[] = $obj;
        }

        return $list;
    }

    function deactivate()
    {
        $mid = pnModGetIdFromName('Guestbook');

        return pnModAPIFunc('Modules', 'admin', 'setstate', array(    'id'    => $mid,
                                                            'state'    => PNMODULE_STATE_INACTIVE));
    }

    function delete()
    {
        $mid = pnModGetIdFromName('Guestbook');

        $this->deactivate();
        return pnModAPIFunc('Modules', 'admin', 'remove', array('id'                    => $mid,
                                                                'interactive_remove'    => true));
    }

    function __toString() {
        return serialize($this);
    }
}