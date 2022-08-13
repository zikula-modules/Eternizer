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
 * It aims on the entry object type.
 */
class Eternizer_Form_Handler_User_Entry_Edit extends Eternizer_Form_Handler_User_Entry_Base_Edit
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
        $dom = ZLanguage::getModuleDomain($this->name);
        parent::initialize($view);
        // we check if we may create an new entry
        if ($this->mode == 'create') {
            if (!SecurityUtil::checkPermission($this->permissionComponent, '.*', ACCESS_ADD)) {
                return LogUtil::registerError(__('That means, you have no permission to create an entry.', $dom));
            }
        }
        // we check if we may edit this entry if we want to edit
        if ($this->mode == 'edit') {
            $entryid = $this->request->query->filter('id', 0, FILTER_SANITIZE_NUMBER_INT);
            $editentry = Eternizer_Util_View::getStateOfEditOfEntry($entryid, 2);

            if ($editentry === false) {
                LogUtil::registerError(__('Sorry! The time to edit this entry is gone!'));
                return System::redirect(ModUtil::url($this->name, 'user', 'view'));
            }
        }

        $entity = $this->entityRef;
        if ($this->mode == 'edit') {
        } else {
            if ($this->hasTemplateId !== true) {
            }
        }

        // save entity reference for later reuse
        $this->entityRef = $entity;

        // everything okay, no initialization errors occured
        return true;
    }

    /**
     * Input data processing called by handleCommand method.
     */
    public function fetchInputData(Zikula_Form_View $view, &$args)
    {
        // fetch posted data input values as an associative array
        $formData = $this->view->getValues();
        // check if captcha is enabled
        $simplecaptcha = $this->getVar('simplecaptcha');
        $check = '';

        if ($simplecaptcha == 1) {
            $captchaInput = $formData['captcha']['eternizer_captcha'];

            $check = $this->checkSimpleCaptcha($captchaInput);
        }

        if ($check == true || $check == '') {
            parent::fetchInputData($view, $args);

            // get treated entity reference from persisted member var
            $entity = $this->entityRef;

            $entityData = array();

            $this->reassignRelatedObjects();

            // assign fetched data
            if (count($entityData) > 0) {
                $entity->merge($entityData);
            }

            // save updated entity
            $this->entityRef = $entity;
        }
    }

    /**
     * Command event handler.
     *
     * This event handler is called when a command is issued by the user. Commands are typically something
     * that originates from a {@link Zikula_Form_Plugin_Button} plugin. The passed args contains different properties
     * depending on the command source, but you should at least find a <var>$args['commandName']</var>
     * value indicating the name of the command. The command name is normally specified by the plugin
     * that initiated the command.
     * @see Zikula_Form_Plugin_Button
     * @see Zikula_Form_Plugin_ImageButton
     */
    public function HandleCommand(Zikula_Form_View $view, &$args)
    {
        if ($args['commandName'] == 'delete') {
            if (!SecurityUtil::checkPermission($this->permissionComponent, '::', ACCESS_DELETE)) {
                return LogUtil::registerPermissionError();
            }
        }

        if (!in_array($args['commandName'], array('delete', 'cancel'))) {
            // do forms validation including checking all validators on the page to validate their input
            if (!$this->view->isValid()) {
                return false;
            }
        }

        $entityClass = $this->name . '_Entity_' . ucfirst($this->objectType);
        $repository = $this->entityManager->getRepository($entityClass);
       /* if ($this->hasTranslatableFields === true) {
            $transRepository = $this->entityManager->getRepository($entityClass . 'Translation');
        }*/ //TODO maybe it is an issue in MOST

        $result = $this->fetchInputData($view, $args);
        if ($result === false) {
            return $result;
        }

        $hookAreaPrefix = 'eternizer.ui_hooks.' . $this->objectTypeLowerMultiple;

        // get treated entity reference from persisted member var
        $entity = $this->entityRef;

        if (in_array($args['commandName'], array('create', 'update'))) {
            // event handling if user clicks on create or update

            // Let any hooks perform additional validation actions
            $hook = new Zikula_ValidationHook($hookAreaPrefix . '.validate_edit', new Zikula_Hook_ValidationProviders());
            $validators = $this->notifyHooks($hook)->getValidators();
            if ($validators->hasErrors()) {
                $dom = ZLanguage::getModuleDomain($this->name);
                LogUtil::registerError(__('There is an error with the captcha validation. Enter the correct input!', $dom));
 
                return false;
            }

            $this->performUpdate($args);

            $success = true;
            if ($args['commandName'] == 'create') {
                // store new identifier
                foreach ($this->idFields as $idField) {
                    $this->idValues[$idField] = $entity[$idField];
                    // check if the insert has worked, might become obsolete due to exception usage
                    if (!$this->idValues[$idField]) {
                        $success = false;
                        break;
                    }
                }
            } else if ($args['commandName'] == 'update') {
            }
            $this->addDefaultMessage($args, $success);

            // Let any hooks know that we have created or updated an item
            $urlArgs = array('ot' => $this->objectType);
            $urlArgs = $this->addIdentifiersToUrlArgs($urlArgs);
            $url = new Zikula_ModUrl($this->name, 'user', 'display', ZLanguage::getLanguageCode(), $urlArgs);
            $hook = new Zikula_ProcessHook($hookAreaPrefix . '.process_edit', $this->createCompositeIdentifier(), $url);
            $this->notifyHooks($hook);
        } else if ($args['commandName'] == 'delete') {
            // event handling if user clicks on delete

            // Let any hooks perform additional validation actions
            $hook = new Zikula_ValidationHook($hookAreaPrefix . '.validate_delete', new Zikula_Hook_ValidationProviders());
            $validators = $this->notifyHooks($hook)->getValidators();
            if ($validators->hasErrors()) {
                return false;
            }

            // delete entity
            $this->entityManager->remove($entity);
            $this->entityManager->flush();

            $this->addDefaultMessage($args, true);

            // Let any hooks know that we have deleted an item
            $hook = new Zikula_ProcessHook($hookAreaPrefix . '.process_delete', $this->createCompositeIdentifier());
            $this->notifyHooks($hook);
        } else if ($args['commandName'] == 'cancel') {
            // event handling if user clicks on cancel
        }

        if ($args['commandName'] != 'cancel') {
            // clear view cache to reflect our changes
            $this->view->clear_cache();
        }

        if ($this->hasPageLockSupport === true && $this->mode == 'edit') {
            ModUtil::apiFunc('PageLock', 'user', 'releaseLock',
                array('lockName' => $this->name . $this->objectTypeCapital . $this->createCompositeIdentifier()));
        }

        return $this->view->redirect($this->getRedirectUrl($args, $entity, $repeatCreateAction));
    }

    public function checkSimpleCaptcha($captcha)
    {
        $captcha_ok = false;
        $cdata = @unserialize(SessionUtil::getVar('eternizercaptcha', 'Hallo'));
        if(is_array($cdata)) {
            switch($cdata['z'].'-'.$cdata['w']) {
                case '0-0':
                    $captcha_ok = (((int)$cdata['x'] + (int)$cdata['y'] + (int)$cdata['v']) == $captcha);
                    break;
                case '0-1':
                    $captcha_ok = (((int)$cdata['x'] + (int)$cdata['y'] - (int)$cdata['v']) == $captcha);
                    break;
                case '1-0':
                    $captcha_ok = (((int)$cdata['x'] - (int)$cdata['y'] + (int)$cdata['v']) == $captcha);
                    break;
                case '1-1':
                    $captcha_ok = (((int)$cdata['x'] - (int)$cdata['y'] - (int)$cdata['v']) == $captcha);
                    break;
                default:
                    // $captcha_ok is false
            }
        }

        if($captcha_ok == false) {
            // we delete the session var
            SessionUtil::delVar('eternizercaptcha');

            // we get the formposition for redirect
            $formposition = ModUtil::getVar($this->name, 'formposition');

            if ($formposition == 'menue') {
                $url = ModUtil::url($this->name, 'user', 'edit', array('ot' => 'entry'));
            } else {
                $url = ModUtil::url($this->name, 'user', 'view');
            }
            LogUtil::registerError($this->__('The calculation to prevent spam was incorrect. Please try again.'));
            return System::redirect($url);
        } else {
            SessionUtil::delVar('eternizercaptcha');
            return true;
        }
    }
}