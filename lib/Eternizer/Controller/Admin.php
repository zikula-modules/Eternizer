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
 * This is the Admin controller class providing navigation and interaction functionality.
 */
class Eternizer_Controller_Admin extends Eternizer_Controller_Base_Admin
{

    /**
     * This method provides a generic item list overview.
     *
     * @param string  $ot           Treated object type.
     * @param string  $sort         Sorting field.
     * @param string  $sortdir      Sorting direction.
     * @param int     $pos          Current pager position.
     * @param int     $num          Amount of entries to display.
     * @param string  $tpl          Name of alternative template (for alternative display options, feeds and xml output)
     * @param boolean $raw          Optional way to display a template instead of fetching it (needed for standalone output)
     * @return mixed Output.
     */
    public function view($args)
    {
        $sort = $this->request->getGet()->filter('sortdir', '', FILTER_SANITIZE_STRING);
        if ($sort == '') {
            $sortdir = ModUtil::getVar('Eternizer', 'order');
            if ($sortdir == 'descending') {
                $args['sortdir'] = 'desc';
            }
            else {
                $args['sortdir'] = 'asc';
            }
        }
         
        return parent::view($args);
    }

    /**
     * This method overrites the parent diplay function.
     *
     * @return mixed System Redirect.
     */
    public function display($args)
    {
        // DEBUG: permission check aspect starts
        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Eternizer::', '::', ACCESS_ADMIN));
        // DEBUG: permission check aspect ends

        // return main template
        return System::redirect(ModUtil::url('Eternizer', 'admin', 'view'));
    }

    public function import()
    {

        // Create new Form reference
        $view = FormUtil::newForm($this->name, $this);

        // Execute form using supplied template and page event handler
        return $view->execute('admin/import.tpl', new Eternizer_Form_Handler_Admin_Base_Import());
    }

    /**
     * Process status changes for multiple items.
     *
     * This function processes the items selected in the admin view page.
     * Multiple items may have their state changed or be deleted.
     *
     * @param array  items  Identifier list of the items to be processed.
     * @param string action The action to be executed.
     *
     * @return bool true on sucess, false on failure.
     */
    public function handleselectedentries(array $args = array())
    {
        $this->checkCsrfToken();

        $returnUrl = ModUtil::url($this->name, 'admin', 'main');

        // Determine object type
        $objectType = isset($args['ot']) ? $args['ot'] : $this->request->request->get('ot', '');
        if (!$objectType) {
            return System::redirect($returnUrl);
        }
        $returnUrl = ModUtil::url($this->name, 'admin', 'view', array('ot' => $objectType));

        // Get other parameters
        $items = isset($args['checkentry']) ? $args['checkentry'] : $this->request->request->get('checkentry', null);
        $action = isset($args['action']) ? $args['action'] : $this->request->request->get('action', null);
        $action = strtolower($action);

        // we get a service manager
        $serviceManager = ServiceUtil::getManager();
        $entityManager = $serviceManager->getService('doctrine.entitymanager');

        // process each item
        foreach ($items as $itemid) {
            // check if item exists, and get record instance
            $selectionArgs = array('ot' => $objectType, 'id' => $itemid, 'useJoins' => false);
            $entity = ModUtil::apiFunc($this->name, 'selection', 'getEntity', $selectionArgs);

            // Let any hooks perform additional validation actions
            $hookType = $action == 'delete' ? 'validate_delete' : 'validate_edit';
            $hook = new Zikula_ValidationHook('eternizer.ui_hooks.entries' . '.' . $hookType, new Zikula_Hook_ValidationProviders());
            $validators = $this->notifyHooks($hook)->getValidators();
            if ($validators->hasErrors()) {
                continue;
            }

            $success = false;
            try {

                if ($action != 'delete') {
                    $entity->setObj_status(strtoupper($action));
                    $success = $entityManager->flush();
                } else {
                    $entityManager->remove($entity);
                    $success = $entityManager->flush();
                }

            } catch(\Exception $e) {
                LogUtil::registerError($this->__f('Sorry, but an unknown error occured during the %s action. Please apply the changes again!', array($action)));
            }

            if (!$success) {
                continue;
            }

            if ($action == 'delete') {
                LogUtil::registerStatus($this->__('Done! Item deleted.'));
            } else {
                LogUtil::registerStatus($this->__('Done! Item updated.'));
            }

            // Let any hooks know that we have updated or deleted an item
            $hookType = $action == 'delete' ? 'process_delete' : 'process_edit';
            $url = null;
            if ($action != 'delete') {
                $urlArgs = $entity->createUrlArgs();
                $url = new Zikula_ModUrl($this->name, 'admin', 'display', ZLanguage::getLanguageCode(), $urlArgs);
            }
            $hook = new Zikula_ProcessHook($hookAreaPrefix . '.' . $hookType, $entity->createCompositeIdentifier(), $url);
            $this->notifyHooks($hook);

            // An item was updated or deleted, so we clear all cached pages for this item.
            $cacheArgs = array('ot' => $objectType, 'item' => $entity);
            ModUtil::apiFunc($this->name, 'cache', 'clearItemCache', $cacheArgs);
        }

        // clear view cache to reflect our changes
        $this->view->clear_cache();

        return System::redirect($returnUrl);
    }
}
