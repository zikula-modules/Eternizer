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

class Eternizer_Form_Handler_User_newHandler extends Zikula_Form_AbstractHandler {

    var $tpl;

    var $inline;

    

    public function __construct($args)
    {
        $this->args = $args;
    }
 /*   function Eternizer_user_newHandler($inline = false) {
        $this->inline = $inline;

        return $this;
    }*/

    function initialize(Zikula_Form_View $view) {
    	
    	$this->inline = 
    	
        $dom = ZLanguage::getModuleDomain('Eternizer');
        $this->tpl = FormUtil::getPassedValue('tpl');

        if (!SecurityUtil::checkPermission('Eternizer::', '::', ACCESS_COMMENT)) {
//            return $this->inline?'':$render->pnFormSetErrorMsg(__('Sorry! No authorization to access this module.', $dom));
        	LogUtil::registerPermissionError();
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

        $this->view->assign('profile', $profile);
        $this->view->assign('loggedin', pnUserLoggedIn());
       // $this->view->assign('bbcode', pnModAvailable('bbcode') && pnModIsHooked('bbcode', 'Eternizer'));
       // $this->view->assign('bbsmile', pnModAvailable('bbsmile') && pnModIsHooked('bbsmile', 'Eternizer'));
        $this->view->assign('profileReadonly', (bool)$ro);

        return true;
    }

    function handleCommand(Zikula_Form_View $view , $args)
    {
        switch($args['commandName']) {
            case 'create':
                if (!$this->view->isValid());
                return false;

                $data = $this->view->getValues();

                $profile = array();
                foreach ($data['profile'] as $k => $v) {
                    $profile[substr($k, 8)] = $v;
                }

                $obj = array('text'    => $data['text'],
                         'profile'    => $profile);

                ModUtil::apiFunc($this->name, 'user', 'WriteEntry', $obj);

                return $this->view->redirect(ModUtil::url($this->name, 'user', 'main'));
                break;
            case 'cancel':
            default:
                return $this->view->redirect(ModUtil::url($this->name, 'user', 'main'));
        }
    }
}
