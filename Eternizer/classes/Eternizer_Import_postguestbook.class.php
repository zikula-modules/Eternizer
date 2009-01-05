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

class Eternizer_Import_postguestbook
{


    function getName()
    {
    	return 'Guestbook';
    }

    function available()
    {
    	return (bool) pnModAvailable('Guestbook');
    }

    function display()
    {
    	$pnr = $render = & FormUtil::newpnForm('Eternizer');

    	Loader::requireOnce('modules/Eternizer/classes/Eternizer_import_importHandler.class.php');

		return $render->pnFormExecute('Eternizer_import_Default.tpl', new Eternizer_import_importHandler(&$this));
    }

    function getConfig()
    {
		$config = array('perpage'	=> pnModGetVar('Guestbook', 'entriesperpage'));

		return $config;
    }

    function getData()
    {
    	pnModDBInfoLoad('postguestbook');

    	$res = DBUtil::selectObjectArray('postguestbook');

    	$list = array();

    	foreach ($res as $item) {
    		$obj = array();
    		foreach ($item as $k => $v) {
    			switch ($k) {
    			case 'message':
    				$obj['text'] = $v;
    				break;
    			case 'timestamp':
    				$obj['cr_date'] = $v;
    				break;
    			case 'pn_uid':
    				$obj['cr_uid'] = $v;
    				break;
    			case 'ip':
    				$obj['ip'] = $v;
    			default:
    				$profile = array('name'		=> 0,
    								'email'		=> 1,
    								'homepage'	=> 2,
    								'location'	=> 3);

    				$obj['profile'][$profile[$k]] = $v;
    			}
    		}
    		$list[] = $obj;
    	}

    	return $list;
    }

    function deactivate()
    {
    	$mid = pnModGetIdFromName('postguestbook');

    	return pnModAPIFunc('Modules', 'admin', 'setstate', array(	'id'	=> $mid,
															'state'	=> PNMODULE_STATE_INACTIVE));
    }

    function delete()
    {
    	$mid = pnModGetIdFromName('postguestbook');

    	$this->deactivate();
    	return pnModAPIFunc('Modules', 'admin', 'remove', array('id'					=> $mid,
																'interactive_remove'	=> true));
    }
    
    function __toString() {
        return serialize($this);
    }
}