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

class Eternizer_Import_pnBook
{

    function getName()
    {
    	return 'pnBook';
    }

    function available()
    {
    	return (bool) pnModAvailable('pnBook');
    }

    function display()
    {
    	$pnr = $render = & FormUtil::newpnForm('Eternizer');

    	Loader::requireOnce('modules/Eternizer/classes/Eternizer_import_importHandler.class.php');

		return $render->pnFormExecute('Eternizer_import_Default.tpl', new Eternizer_import_importHandler(&$this));
    }

    function getConfig()
    {
		$config = pnModGetVar('pnBook');

		return $config;
    }

    function getData()
    {
    	pnModDBInfoLoad('pnBook');

    	$res = DBUtil::selectObjectArray('pnBook_entry');

    	$list = array();

    	foreach ($res as $item) {
    		$obj = array();
    		
            if (isset($item['uid']) && $item['uid'] > 1) {
                $item['name'] = pnUserGetVar('uname', $item['uid']);
                $item['city'] = pnUserGetVar('user_from', $item['uid']);
                $item['email'] = pnUserGetVar('femail', $item['uid']);
                $item['page'] = pnUserGetVar('url', $item['uid']);
                $item['icq'] = pnUserGetVar('user_icq', $item['uid']);
                $item['msn'] = pnUserGetVar('user_msnm', $item['uid']);
            }

            foreach ($item as $k => $v) {
    			switch ($k) {
    			case 'text':
    				$obj['text'] = $v;
    				break;
    			case 'date':
    				$obj['cr_date'] = DateUtil::getDatetime($v);
    				break;
    			case 'uid':
    				$obj['cr_uid'] = $v;
    				break;
    			case 'ip':
    				$obj['ip'] = $v;
    			default:
    				$profile = array('name'		=> 0,
    								'email'		=> 1,
    								'page'		=> 2,
    								'city'	    => 3);
                    if (isset($profile[$k]))
    				    $obj['profile'][$profile[$k]] = $v;
    			}
    		}
    		$list[] = $obj;
    	}
    	return $list;
    }

    function deactivate()
    {
    	$mid = pnModGetIdFromName('pnBook');

    	return pnModAPIFunc('Modules', 'admin', 'setstate', array(	'id'	=> $mid,
															'state'	=> PNMODULE_STATE_INACTIVE));
    }

    function delete()
    {
    	$mid = pnModGetIdFromName('pnBook');

    	$this->deactivate();
    	return pnModAPIFunc('Modules', 'admin', 'remove', array('id'					=> $mid,
																'interactive_remove'	=> true));
    }

    function __toString() {
        return serialize($this);
    }
}