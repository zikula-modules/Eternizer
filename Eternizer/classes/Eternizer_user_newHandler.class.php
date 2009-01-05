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

class Eternizer_user_newHandler {

	var $tpl;

	var $inline;

	function Eternizer_user_newHandler($inline = false) {
		$this->inline = $inline;

		return $this;
	}

	function initialize(&$render) {

		$this->tpl = FormUtil::getPassedValue('tpl');

    	if (!SecurityUtil::checkPermission('Eternizer::', '::', ACCESS_COMMENT)) {
    		return $this->inline?'':$render->pnFormSetErrorMsg(_MODULEAUTHERROR);
    	}
    	$ro = (bool)(pnUserLoggedIn() && (bool)pnModGetVar('Eternizer', 'useuserprofile'));
		$profile = pnModGetVar('Eternizer', 'profile');
		if (pnUserLoggedIn()) {
		    foreach (array_keys($profile) as $k) {
		        if (isset($profile[$k]['profile']))
		            $profile[$k]['value'] = pnUserGetVar($profile[$k]['profile'], -1, '');
		        if ($profile[$k]['mandatory'] < 2)
		            $profile[$k]['mandatory'] = 0;
		        $profile[$k]['readonly'] = $ro && !empty($profile[$k]['value']); 
		    }		    
		} else {
		    foreach (array_keys($profile) as $k) {
		        $profile[$k]['value'] = '';
		    }
		}

		$render->assign('profile', $profile);
		$render->assign('loggedin', pnUserLoggedIn());
		$render->assign('bbcode', pnModAvailable('bbcode') && pnModIsHooked('bbcode', 'Eternizer'));
		$render->assign('bbsmile', pnModAvailable('bbsmile') && pnModIsHooked('bbsmile', 'Eternizer'));
		$render->assign('profileReadonly', (bool)$ro);

    	return true;
    }

    function handleCommand(&$render, $args)
  	{
    	switch($args['commandName']) {
    	case 'create':
    		if (!$render->pnFormIsValid())
        	    return false;
        	
      		$data = $render->pnFormGetValues();

      		$profile = array();
      		foreach ($data['profile'] as $k => $v) {
  				$profile[substr($k, 8)] = $v;
      		}

			$obj = array('text'	=> $data['text'],
						 'profile'	=> $profile);

      		pnModAPIFunc('Eternizer', 'user', 'WriteEntry', $obj);

      		return $render->pnFormRedirect(pnModURL('Eternizer', 'user', 'main'));
      	break;
      	case 'cancel':
      	default:
      		return $render->pnFormRedirect(pnModURL('Eternizer', 'user', 'main'));
    	}
  	}
}
?>