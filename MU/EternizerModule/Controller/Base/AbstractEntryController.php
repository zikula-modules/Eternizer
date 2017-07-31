<?php
/**
 * Eternizer.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link https://homepages-mit-zikula.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (https://modulestudio.de).
 */

namespace MU\EternizerModule\Controller\Base;

use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Zikula\Bundle\HookBundle\Category\FormAwareCategory;
use Zikula\Bundle\HookBundle\Category\UiHooksCategory;
use Zikula\Component\SortableColumns\Column;
use Zikula\Component\SortableColumns\SortableColumns;
use Zikula\Core\Controller\AbstractController;
use Zikula\Core\RouteUrl;
use MU\EternizerModule\Entity\EntryEntity;

/**
 * Entry controller base class.
 */
abstract class AbstractEntryController extends AbstractController
{
    /**
     * This is the default action handling the main admin area called without defining arguments.
     * @Cache(expires="+7 days", public=true)
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function adminIndexAction(Request $request)
    {
        return $this->indexInternal($request, true);
    }
    
    /**
     * This is the default action handling the main area called without defining arguments.
     * @Cache(expires="+7 days", public=true)
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function indexAction(Request $request)
    {
        return $this->indexInternal($request, false);
    }
    
    /**
     * This method includes the common implementation code for adminIndex() and index().
     */
    protected function indexInternal(Request $request, $isAdmin = false)
    {
        // parameter specifying which type of objects we are treating
        $objectType = 'entry';
        $permLevel = $isAdmin ? ACCESS_ADMIN : ACCESS_OVERVIEW;
        if (!$this->hasPermission('MUEternizerModule:' . ucfirst($objectType) . ':', '::', $permLevel)) {
            throw new AccessDeniedException();
        }
        $templateParameters = [
            'routeArea' => $isAdmin ? 'admin' : ''
        ];
        
        return $this->redirectToRoute('mueternizermodule_entry_' . $templateParameters['routeArea'] . 'view');
    }
    /**
     * This action provides an item list overview in the admin area.
     * @Cache(expires="+2 hours", public=false)
     *
     * @param Request $request Current request instance
     * @param string $sort         Sorting field
     * @param string $sortdir      Sorting direction
     * @param int    $pos          Current pager position
     * @param int    $num          Amount of entries to display
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function adminViewAction(Request $request, $sort, $sortdir, $pos, $num)
    {
        return $this->viewInternal($request, $sort, $sortdir, $pos, $num, true);
    }
    
    /**
     * This action provides an item list overview.
     * @Cache(expires="+2 hours", public=false)
     *
     * @param Request $request Current request instance
     * @param string $sort         Sorting field
     * @param string $sortdir      Sorting direction
     * @param int    $pos          Current pager position
     * @param int    $num          Amount of entries to display
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function viewAction(Request $request, $sort, $sortdir, $pos, $num)
    {
        return $this->viewInternal($request, $sort, $sortdir, $pos, $num, false);
    }
    
    /**
     * This method includes the common implementation code for adminView() and view().
     */
    protected function viewInternal(Request $request, $sort, $sortdir, $pos, $num, $isAdmin = false)
    {
        // parameter specifying which type of objects we are treating
        $objectType = 'entry';
        $permLevel = $isAdmin ? ACCESS_ADMIN : ACCESS_READ;
        if (!$this->hasPermission('MUEternizerModule:' . ucfirst($objectType) . ':', '::', $permLevel)) {
            throw new AccessDeniedException();
        }
        $templateParameters = [
            'routeArea' => $isAdmin ? 'admin' : ''
        ];
        $controllerHelper = $this->get('mu_eternizer_module.controller_helper');
        $viewHelper = $this->get('mu_eternizer_module.view_helper');
        
        $request->query->set('sort', $sort);
        $request->query->set('sortdir', $sortdir);
        $request->query->set('pos', $pos);
        
        $sortableColumns = new SortableColumns($this->get('router'), 'mueternizermodule_entry_' . ($isAdmin ? 'admin' : '') . 'view', 'sort', 'sortdir');
        
        $sortableColumns->addColumns([
            new Column('workflowState'),
            new Column('ip'),
            new Column('name'),
            new Column('email'),
            new Column('homepage'),
            new Column('location'),
            new Column('text'),
            new Column('notes'),
            new Column('createdBy'),
            new Column('createdDate'),
            new Column('updatedBy'),
            new Column('updatedDate'),
        ]);
        
        $templateParameters = $controllerHelper->processViewActionParameters($objectType, $sortableColumns, $templateParameters, true);
        
        
        // fetch and return the appropriate template
        return $viewHelper->processTemplate($objectType, 'view', $templateParameters);
    }
    /**
     * This action provides a item detail view in the admin area.
     * @ParamConverter("entry", class="MUEternizerModule:EntryEntity", options = {"repository_method" = "selectById", "mapping": {"id": "id"}, "map_method_signature" = true})
     * @Cache(lastModified="entry.getUpdatedDate()", ETag="'Entry' ~ entry.getid() ~ entry.getUpdatedDate().format('U')")
     *
     * @param Request $request Current request instance
     * @param EntryEntity $entry Treated entry instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by param converter if entry to be displayed isn't found
     */
    public function adminDisplayAction(Request $request, EntryEntity $entry)
    {
        return $this->displayInternal($request, $entry, true);
    }
    
    /**
     * This action provides a item detail view.
     * @ParamConverter("entry", class="MUEternizerModule:EntryEntity", options = {"repository_method" = "selectById", "mapping": {"id": "id"}, "map_method_signature" = true})
     * @Cache(lastModified="entry.getUpdatedDate()", ETag="'Entry' ~ entry.getid() ~ entry.getUpdatedDate().format('U')")
     *
     * @param Request $request Current request instance
     * @param EntryEntity $entry Treated entry instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by param converter if entry to be displayed isn't found
     */
    public function displayAction(Request $request, EntryEntity $entry)
    {
        return $this->displayInternal($request, $entry, false);
    }
    
    /**
     * This method includes the common implementation code for adminDisplay() and display().
     */
    protected function displayInternal(Request $request, EntryEntity $entry, $isAdmin = false)
    {
        // parameter specifying which type of objects we are treating
        $objectType = 'entry';
        $permLevel = $isAdmin ? ACCESS_ADMIN : ACCESS_READ;
        if (!$this->hasPermission('MUEternizerModule:' . ucfirst($objectType) . ':', '::', $permLevel)) {
            throw new AccessDeniedException();
        }
        // create identifier for permission check
        $instanceId = $entry->getKey();
        if (!$this->hasPermission('MUEternizerModule:' . ucfirst($objectType) . ':', $instanceId . '::', $permLevel)) {
            throw new AccessDeniedException();
        }
        
        $templateParameters = [
            'routeArea' => $isAdmin ? 'admin' : '',
            $objectType => $entry
        ];
        
        $controllerHelper = $this->get('mu_eternizer_module.controller_helper');
        $templateParameters = $controllerHelper->processDisplayActionParameters($objectType, $templateParameters, true);
        
        // fetch and return the appropriate template
        $response = $this->get('mu_eternizer_module.view_helper')->processTemplate($objectType, 'display', $templateParameters);
        
        return $response;
    }
    /**
     * This action provides a handling of edit requests in the admin area.
     * @Cache(lastModified="entry.getUpdatedDate()", ETag="'Entry' ~ entry.getid() ~ entry.getUpdatedDate().format('U')")
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by form handler if entry to be edited isn't found
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available)
     */
    public function adminEditAction(Request $request)
    {
        return $this->editInternal($request, true);
    }
    
    /**
     * This action provides a handling of edit requests.
     * @Cache(lastModified="entry.getUpdatedDate()", ETag="'Entry' ~ entry.getid() ~ entry.getUpdatedDate().format('U')")
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by form handler if entry to be edited isn't found
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available)
     */
    public function editAction(Request $request)
    {
        return $this->editInternal($request, false);
    }
    
    /**
     * This method includes the common implementation code for adminEdit() and edit().
     */
    protected function editInternal(Request $request, $isAdmin = false)
    {
        // parameter specifying which type of objects we are treating
        $objectType = 'entry';
        $permLevel = $isAdmin ? ACCESS_ADMIN : ACCESS_EDIT;
        if (!$this->hasPermission('MUEternizerModule:' . ucfirst($objectType) . ':', '::', $permLevel)) {
            throw new AccessDeniedException();
        }
        $templateParameters = [
            'routeArea' => $isAdmin ? 'admin' : ''
        ];
        
        $controllerHelper = $this->get('mu_eternizer_module.controller_helper');
        $templateParameters = $controllerHelper->processEditActionParameters($objectType, $templateParameters);
        
        // delegate form processing to the form handler
        $formHandler = $this->get('mu_eternizer_module.form.handler.entry');
        $result = $formHandler->processForm($templateParameters);
        if ($result instanceof RedirectResponse) {
            return $result;
        }
        
        $templateParameters = $formHandler->getTemplateParameters();
        
        // fetch and return the appropriate template
        return $this->get('mu_eternizer_module.view_helper')->processTemplate($objectType, 'edit', $templateParameters);
    }
    /**
     * This action provides a handling of simple delete requests in the admin area.
     * @ParamConverter("entry", class="MUEternizerModule:EntryEntity", options = {"repository_method" = "selectById", "mapping": {"id": "id"}, "map_method_signature" = true})
     * @Cache(lastModified="entry.getUpdatedDate()", ETag="'Entry' ~ entry.getid() ~ entry.getUpdatedDate().format('U')")
     *
     * @param Request $request Current request instance
     * @param EntryEntity $entry Treated entry instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by param converter if entry to be deleted isn't found
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available)
     */
    public function adminDeleteAction(Request $request, EntryEntity $entry)
    {
        return $this->deleteInternal($request, $entry, true);
    }
    
    /**
     * This action provides a handling of simple delete requests.
     * @ParamConverter("entry", class="MUEternizerModule:EntryEntity", options = {"repository_method" = "selectById", "mapping": {"id": "id"}, "map_method_signature" = true})
     * @Cache(lastModified="entry.getUpdatedDate()", ETag="'Entry' ~ entry.getid() ~ entry.getUpdatedDate().format('U')")
     *
     * @param Request $request Current request instance
     * @param EntryEntity $entry Treated entry instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     * @throws NotFoundHttpException Thrown by param converter if entry to be deleted isn't found
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available)
     */
    public function deleteAction(Request $request, EntryEntity $entry)
    {
        return $this->deleteInternal($request, $entry, false);
    }
    
    /**
     * This method includes the common implementation code for adminDelete() and delete().
     */
    protected function deleteInternal(Request $request, EntryEntity $entry, $isAdmin = false)
    {
        // parameter specifying which type of objects we are treating
        $objectType = 'entry';
        $permLevel = $isAdmin ? ACCESS_ADMIN : ACCESS_DELETE;
        if (!$this->hasPermission('MUEternizerModule:' . ucfirst($objectType) . ':', '::', $permLevel)) {
            throw new AccessDeniedException();
        }
        $logger = $this->get('logger');
        $logArgs = ['app' => 'MUEternizerModule', 'user' => $this->get('zikula_users_module.current_user')->get('uname'), 'entity' => 'entry', 'id' => $entry->getKey()];
        
        // determine available workflow actions
        $workflowHelper = $this->get('mu_eternizer_module.workflow_helper');
        $actions = $workflowHelper->getActionsForObject($entry);
        if (false === $actions || !is_array($actions)) {
            $this->addFlash('error', $this->__('Error! Could not determine workflow actions.'));
            $logger->error('{app}: User {user} tried to delete the {entity} with id {id}, but failed to determine available workflow actions.', $logArgs);
            throw new \RuntimeException($this->__('Error! Could not determine workflow actions.'));
        }
        
        // redirect to the list of entries
        $redirectRoute = 'mueternizermodule_entry_' . ($isAdmin ? 'admin' : '') . 'view';
        
        // check whether deletion is allowed
        $deleteActionId = 'delete';
        $deleteAllowed = false;
        foreach ($actions as $actionId => $action) {
            if ($actionId != $deleteActionId) {
                continue;
            }
            $deleteAllowed = true;
            break;
        }
        if (!$deleteAllowed) {
            $this->addFlash('error', $this->__('Error! It is not allowed to delete this entry.'));
            $logger->error('{app}: User {user} tried to delete the {entity} with id {id}, but this action was not allowed.', $logArgs);
        
            return $this->redirectToRoute($redirectRoute);
        }
        
        $form = $this->createForm('Zikula\Bundle\FormExtensionBundle\Form\Type\DeletionType', $entry);
        $hookHelper = $this->get('mu_eternizer_module.hook_helper');
        
        // Call form aware display hooks
        $formHook = $hookHelper->callFormDisplayHooks($form, $entry, FormAwareCategory::TYPE_DELETE);
        
        if ($form->handleRequest($request)->isValid()) {
            if ($form->get('delete')->isClicked()) {
                // Let any ui hooks perform additional validation actions
                $validationErrors = $hookHelper->callValidationHooks($entry, UiHooksCategory::TYPE_VALIDATE_DELETE);
                if (count($validationErrors) > 0) {
                    foreach ($validationErrors as $message) {
                        $this->addFlash('error', $message);
                    }
                } else {
                    // execute the workflow action
                    $success = $workflowHelper->executeAction($entry, $deleteActionId);
                    if ($success) {
                        $this->addFlash('status', $this->__('Done! Item deleted.'));
                        $logger->notice('{app}: User {user} deleted the {entity} with id {id}.', $logArgs);
                    }
                    
                    // Call form aware processing hooks
                    $hookHelper->callFormProcessHooks($form, $entry, FormAwareCategory::TYPE_PROCESS_DELETE);
                    
                    // Let any ui hooks know that we have deleted the entry
                    $hookHelper->callProcessHooks($entry, UiHooksCategory::TYPE_PROCESS_DELETE);
                    
                    return $this->redirectToRoute($redirectRoute);
                }
            } elseif ($form->get('cancel')->isClicked()) {
                $this->addFlash('status', $this->__('Operation cancelled.'));
        
                return $this->redirectToRoute($redirectRoute);
            }
        }
        
        $templateParameters = [
            'routeArea' => $isAdmin ? 'admin' : '',
            'deleteForm' => $form->createView(),
            $objectType => $entry,
            'formHookTemplates' => $formHook->getTemplates()
        ];
        
        $controllerHelper = $this->get('mu_eternizer_module.controller_helper');
        $templateParameters = $controllerHelper->processDeleteActionParameters($objectType, $templateParameters, true);
        
        // fetch and return the appropriate template
        return $this->get('mu_eternizer_module.view_helper')->processTemplate($objectType, 'delete', $templateParameters);
    }

    /**
     * Process status changes for multiple items.
     *
     * This function processes the items selected in the admin view page.
     * Multiple items may have their state changed or be deleted.
     *
     * @param Request $request Current request instance
     *
     * @return RedirectResponse
     *
     * @throws RuntimeException Thrown if executing the workflow action fails
     */
    public function adminHandleSelectedEntriesAction(Request $request)
    {
        return $this->handleSelectedEntriesActionInternal($request, true);
    }
    
    /**
     * Process status changes for multiple items.
     *
     * This function processes the items selected in the admin view page.
     * Multiple items may have their state changed or be deleted.
     *
     * @param Request $request Current request instance
     *
     * @return RedirectResponse
     *
     * @throws RuntimeException Thrown if executing the workflow action fails
     */
    public function handleSelectedEntriesAction(Request $request)
    {
        return $this->handleSelectedEntriesActionInternal($request, false);
    }
    
    /**
     * This method includes the common implementation code for adminHandleSelectedEntriesAction() and handleSelectedEntriesAction().
     *
     * @param Request $request Current request instance
     * @param Boolean $isAdmin Whether the admin area is used or not
     */
    protected function handleSelectedEntriesActionInternal(Request $request, $isAdmin = false)
    {
        $objectType = 'entry';
        
        // Get parameters
        $action = $request->request->get('action', null);
        $items = $request->request->get('items', null);
        
        $action = strtolower($action);
        
        $repository = $this->get('mu_eternizer_module.entity_factory')->getRepository($objectType);
        $workflowHelper = $this->get('mu_eternizer_module.workflow_helper');
        $hookHelper = $this->get('mu_eternizer_module.hook_helper');
        $logger = $this->get('logger');
        $userName = $this->get('zikula_users_module.current_user')->get('uname');
        
        // process each item
        foreach ($items as $itemId) {
            // check if item exists, and get record instance
            $entity = $repository->selectById($itemId, false);
            if (null === $entity) {
                continue;
            }
        
            // check if $action can be applied to this entity (may depend on it's current workflow state)
            $allowedActions = $workflowHelper->getActionsForObject($entity);
            $actionIds = array_keys($allowedActions);
            if (!in_array($action, $actionIds)) {
                // action not allowed, skip this object
                continue;
            }
        
            // Let any ui hooks perform additional validation actions
            $hookType = $action == 'delete' ? UiHooksCategory::TYPE_VALIDATE_DELETE : UiHooksCategory::TYPE_VALIDATE_EDIT;
            $validationErrors = $hookHelper->callValidationHooks($entity, $hookType);
            if (count($validationErrors) > 0) {
                foreach ($validationErrors as $message) {
                    $this->addFlash('error', $message);
                }
                continue;
            }
        
            $success = false;
            try {
                // execute the workflow action
                $success = $workflowHelper->executeAction($entity, $action);
            } catch (\Exception $exception) {
                $this->addFlash('error', $this->__f('Sorry, but an error occured during the %action% action.', ['%action%' => $action]) . '  ' . $exception->getMessage());
                $logger->error('{app}: User {user} tried to execute the {action} workflow action for the {entity} with id {id}, but failed. Error details: {errorMessage}.', ['app' => 'MUEternizerModule', 'user' => $userName, 'action' => $action, 'entity' => 'entry', 'id' => $itemId, 'errorMessage' => $exception->getMessage()]);
            }
        
            if (!$success) {
                continue;
            }
        
            if ($action == 'delete') {
                $this->addFlash('status', $this->__('Done! Item deleted.'));
                $logger->notice('{app}: User {user} deleted the {entity} with id {id}.', ['app' => 'MUEternizerModule', 'user' => $userName, 'entity' => 'entry', 'id' => $itemId]);
            } else {
                $this->addFlash('status', $this->__('Done! Item updated.'));
                $logger->notice('{app}: User {user} executed the {action} workflow action for the {entity} with id {id}.', ['app' => 'MUEternizerModule', 'user' => $userName, 'action' => $action, 'entity' => 'entry', 'id' => $itemId]);
            }
        
            // Let any ui hooks know that we have updated or deleted an item
            $hookType = $action == 'delete' ? UiHooksCategory::TYPE_PROCESS_DELETE : UiHooksCategory::TYPE_PROCESS_EDIT;
            $url = null;
            if ($action != 'delete') {
                $urlArgs = $entity->createUrlArgs();
                $urlArgs['_locale'] = $request->getLocale();
                $url = new RouteUrl('mueternizermodule_entry_display', $urlArgs);
            }
            $hookHelper->callProcessHooks($entity, $hookType, $url);
        }
        
        return $this->redirectToRoute('mueternizermodule_entry_' . ($isAdmin ? 'admin' : '') . 'index');
    }
}
