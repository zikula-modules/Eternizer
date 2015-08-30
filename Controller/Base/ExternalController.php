<?php
/**
 * Eternizer.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://www.webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.7.0 (http://modulestudio.de).
 */

namespace MU\EternizerModule\Controller\Base;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use ModUtil;
use PageUtil;
use SecurityUtil;
use ThemeUtil;
use UserUtil;
use Zikula_AbstractController;
use Zikula_View;
use Zikula\Core\Response\PlainResponse;

/**
 * Controller for external calls base class.
 */
class ExternalController extends Zikula_AbstractController
{
    /**
     * Post initialise.
     *
     * Run after construction.
     *
     * @return void
     */
    protected function postInitialize()
    {
        // Set caching to false by default.
        $this->view->setCaching(Zikula_View::CACHE_DISABLED);
    }

    /**
     * Displays one item of a certain object type using a separate template for external usages.
     *
     * @param string $ot          The currently treated object type.
     * @param int    $id          Identifier of the entity to be shown.
     * @param string $source      Source of this call (contentType or scribite).
     * @param string $displayMode Display mode (link or embed).
     *
     * @return string Desired data output.
     */
    public function displayAction($ot, $id, $source, $displayMode)
    {
        $controllerHelper = $this->serviceManager->get('mueternizermodule.controller_helper');
        
        $objectType = $ot;
        $utilArgs = array('controller' => 'external', 'action' => 'display');
        if (!in_array($objectType, $controllerHelper->getObjectTypes('controller', $utilArgs))) {
            $objectType = $controllerHelper->getDefaultObjectType('controllerType', $utilArgs);
        }
        
        $component = $this->name . ':' . ucfirst($objectType) . ':';
        if (!SecurityUtil::checkPermission($component, $id . '::', ACCESS_READ)) {
            return '';
        }
        
        $repository = $this->serviceManager->get('mueternizermodule.' . $objectType . '_factory')->getRepository();
        $repository->setRequest($this->request);
        $idFields = ModUtil::apiFunc('MUEternizerModule', 'selection', 'getIdFields', array('ot' => $objectType));
        $idValues = array('id' => $id);
        
        $hasIdentifier = $controllerHelper->isValidIdentifier($idValues);
        if (!$hasIdentifier) {
            return $this->__('Error! Invalid identifier received.');
        }
        
        // assign object data fetched from the database
        $entity = $repository->selectById($idValues);
        if ((!is_array($entity) && !is_object($entity)) || !isset($entity[$idFields[0]])) {
            return $this->__('No such item.');
        }
        
        $entity->initWorkflow();
        
        $instance = $entity->createCompositeIdentifier() . '::';
        
        $this->view->setCaching(Zikula_View::CACHE_ENABLED);
        // set cache id
        $accessLevel = ACCESS_READ;
        if (SecurityUtil::checkPermission($component, $instance, ACCESS_COMMENT)) {
            $accessLevel = ACCESS_COMMENT;
        }
        if (SecurityUtil::checkPermission($component, $instance, ACCESS_EDIT)) {
            $accessLevel = ACCESS_EDIT;
        }
        $this->view->setCacheId($objectType . '|' . $id . '|a' . $accessLevel);
        
        $this->view->assign('objectType', $objectType)
                  ->assign('source', $source)
                  ->assign($objectType, $entity)
                  ->assign('displayMode', $displayMode);
        
        return $this->response($this->view->fetch('External/' . ucfirst($objectType) . '/display.tpl'));
    }
    
    /**
     * Popup selector for Scribite plugins.
     * Finds items of a certain object type.
     *
     * @param string $objectType The object type.
     * @param string $editor     Name of used Scribite editor.
     * @param string $sort       Sorting field.
     * @param string $sortdir    Sorting direction.
     * @param int    $pos        Current pager position.
     * @param int    $num        Amount of entries to display.
     *
     * @return output The external item finder page
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function finderAction($objectType, $editor, $sort, $sortdir, $pos = 1, $num = 0)
    {
        PageUtil::addVar('stylesheet', '@MUEternizerModule/Resources/public/css/style.css');
        
        $getData = $this->request->query;
        $controllerHelper = $this->serviceManager->get('mueternizermodule.controller_helper');
        
        $utilArgs = array('controller' => 'external', 'action' => 'finder');
        if (!in_array($objectType, $controllerHelper->getObjectTypes('controller', $utilArgs))) {
            $objectType = $controllerHelper->getDefaultObjectType('controllerType', $utilArgs);
        }
        
        if (!SecurityUtil::checkPermission('MUEternizerModule:' . ucfirst($objectType) . ':', '::', ACCESS_COMMENT)) {
            throw new AccessDeniedException();
        }
        
        $repository = $this->serviceManager->get('mueternizermodule.' . $objectType . '_factory')->getRepository();
        $repository->setRequest($this->request);
        
        if (empty($editor) || !in_array($editor, array('xinha', 'tinymce', 'ckeditor'))) {
            return $this->__('Error: Invalid editor context given for external controller action.');
        }
        if (empty($sort) || !in_array($sort, $repository->getAllowedSortingFields())) {
            $sort = $repository->getDefaultSortingField();
        }
        
        $sdir = strtolower($sortdir);
        if ($sdir != 'asc' && $sdir != 'desc') {
            $sdir = 'asc';
        }
        
        $sortParam = $sort . ' ' . $sdir;
        
        // the current offset which is used to calculate the pagination
        $currentPage = (int) $pos;
        
        // the number of items displayed on a page for pagination
        $resultsPerPage = (int) $num;
        if ($resultsPerPage == 0) {
            $resultsPerPage = $this->getVar('pageSize', 20);
        }
        $where = '';
        list($entities, $objectCount) = $repository->selectWherePaginated($where, $sortParam, $currentPage, $resultsPerPage);
        
        foreach ($entities as $k => $entity) {
            $entity->initWorkflow();
        }
        
        $view = Zikula_View::getInstance('MUEternizerModule', false);
        
        $view->assign('editorName', $editor)
             ->assign('objectType', $objectType)
             ->assign('items', $entities)
             ->assign('sort', $sort)
             ->assign('sortdir', $sdir)
             ->assign('currentPage', $currentPage)
             ->assign('pager', array('numitems'     => $objectCount,
                                     'itemsperpage' => $resultsPerPage));
        
        return new PlainResponse($view->display('External/' . ucfirst($objectType) . '/find.tpl'));
    }
}
