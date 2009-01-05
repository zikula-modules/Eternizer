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

class Eternizer_admin_modifyHandler {

	var $id;
	var $goback;

	function initialize(&$render) {

		$this->id = FormUtil::getPassedValue('id');
		$this->goback = DataUtil::decode(FormUtil::getPassedValue('goback'));
	    if (empty($this->goback))
	        $this->goback = pnModURL('Eternizer', 'user', 'main');

    	if (!SecurityUtil::checkPermission('Eternizer::', $this->id.'::', ACCESS_MODERATE)) {
    		return $render->pnFormSetErrorMsg(_MODULEAUTHERROR);
    	}

		$entry = pnModAPIFunc('Eternizer', 'admin', 'GetEntry', array('id'	=> $this->id));
		
	    $profile = pnModGetVar('Eternizer', 'profile');
		if ($entry['cr_uid'] > 1) {
		    foreach (array_keys($profile) as $k) {
		        $profile[$k]['value'] = $entry['profile'][$k];
		        if ($profile[$k]['mandatory'] < 2)
		            $profile[$k]['mandatory'] = 0;
		    }		    
		} else {
		    foreach (array_keys($profile) as $k) {
		        $profile[$k]['value'] = $entry['profile'][$k];
		    }
		}
		$edit = SecurityUtil::checkPermission('Eternizer::', $entry['id'].'::', ACCESS_EDIT);
		$render->assign('modonly', !$edit);
        $render->assign('profileConfig', $profile);
		$render->assign($entry);
		$render->assign('profileReadonly', (bool)(($entry['cr_uid'] > 1 && pnModGetVar('Eternizer', 'useuserprofile')) || !$edit));
		$render->assign('bbcode', pnModAvailable('bbcode'));
		$render->assign('bbsmile', pnModAvailable('bbsmile'));

    	return true;
    }

    function handleCommand(&$render, $args)
  	{
    	switch($args['commandName']) {
    	case 'update':
    		if (!$render->pnFormIsValid())
        	return false;

      		$data = $render->pnFormGetValues();

      		$profile = array();
      		foreach ($data['profile'] as $k => $v) {
      			$profile[substr($k, 8)] = $v;
      		}
            
      		if (SecurityUtil::checkPermission('Eternizer::', $this->id.'::', ACCESS_EDIT)) {
      		    $obj = array('text'	=> $data['text'],
    			             'comment' => $data['comment'],
	    					 'profile'	=> $profile);
      		} elseif (SecurityUtil::checkPermission('Eternizer::', $this->id.'::', ACCESS_MODERATE)) {
      		    $obj = array('comment' => $data['comment']);
      		}

			$obj['id'] = $this->id;

      		pnModAPIFunc('Eternizer', 'admin', 'EditEntry', $obj);

      		return $render->pnFormRedirect($this->goback);
      	break;
      	case 'cancel':
      	default:
      		return $render->pnFormRedirect($this->goback);
    	}
  	}
}
?>