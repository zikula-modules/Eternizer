<?php
/**
 * Eternizer.
 *
 * @copyright Michael Ueberschaer
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package Eternizer
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://www.webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Wed Jan 04 16:43:44 CET 2012.
 */

/**
 * This handler class handles the page events of the Form called by the Eternizer_user_edit() function.
 * It collects common functionality required by different object types.
 */
class Eternizer_Form_Handler_User_Edit extends Eternizer_Form_Handler_User_Base_Edit
{
    /**
     * Initialize form handler.
     *
     * This method takes care of all necessary initialisation of our data and form states.
     *
     * @return boolean False in case of initialization errors, otherwise true.
     */
    public function initialize(Zikula_Form_View $view)
    {
        $this->inlineUsage = ((UserUtil::getTheme() == 'Printer') ? true : false);
        $this->idPrefix = $this->request->getGet()->filter('idp', '', FILTER_SANITIZE_STRING);

        // initialise redirect goal
        $this->returnTo = $this->request->getGet()->filter('returnTo', null, FILTER_SANITIZE_STRING);
        // store current uri for repeated creations
        $this->repeatReturnUrl = System::getCurrentURI();

        $this->permissionComponent = $this->name . ':' . $this->objectTypeCapital . ':';

        $entityClass = $this->name . '_Entity_' . ucfirst($this->objectType);
        $objectTemp = new $entityClass();
        $this->idFields = $objectTemp->get_idFields();

        // retrieve identifier of the object we wish to view
        $this->idValues = Eternizer_Util_Controller::retrieveIdentifier($this->request, array(), $this->objectType, $this->idFields);
        $hasIdentifier = Eternizer_Util_Controller::isValidIdentifier($this->idValues);

        $entity = null;
        $this->mode = ($hasIdentifier) ? 'edit' : 'create';

        if ($this->mode == 'edit') {
            if (!SecurityUtil::checkPermission($this->permissionComponent, '::', ACCESS_EDIT)) {
                // set an error message and return false
                return LogUtil::registerPermissionError();
            }

            $entity = $this->initEntityForEdit();

            if ($this->hasPageLockSupport === true && ModUtil::available('PageLock')) {
                // try to guarantee that only one person at a time can be editing this entity
                ModUtil::apiFunc('PageLock', 'user', 'pageLock',
                array('lockName' => $this->name . $this->objectTypeCapital . $this->createCompositeIdentifier(),
                'returnUrl' => $this->getRedirectUrl(null, $entity)));
            }
        }
        else {
            if (!SecurityUtil::checkPermission($this->permissionComponent, '::', ACCESS_ADD)) {
                return LogUtil::registerPermissionError();
            }

            $entity = $this->initEntityForCreation($entityClass);
        }

        $this->view->assign('mode', $this->mode)
        ->assign('inlineUsage', $this->inlineUsage);

        $entityData = $entity->toArray();

        // Own code to handle editing of entries only for the creater
        $userid = UserUtil::getVar('uid');

        if ($entityData['createdUserId'] != $userid && $this->mode != 'create') {

            $url = ModUtil::url($this->name, 'user', 'view');
            return System::redirect($url);
        }

        // Own code to assign formposition
        $formposition = ModUtil::getVar($this->name, 'formposition');
        $this->view->assign('formposition', $formposition);

        $simplecaptcha = ModUtil::getVar($this->name, 'simplecaptcha');
        if ($simplecaptcha == 1) {
            // reset captcha
            SessionUtil::delVar('eternizer_captcha');    
        }
        // asssign var simplecaptcha to template
        $this->view->assign('simplecaptcha', $simplecaptcha);
        // assign data to template as array (makes translatable support easier)
        $this->view->assign($this->objectTypeLower, $entityData);

        // save entity reference for later reuse
        $this->entityRef = $entity;

        $this->initializeAdditions();

        // everything okay, no initialization errors occured
        return true;
    }
}
