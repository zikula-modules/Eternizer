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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use DataUtil;
use ModUtil;
use SecurityUtil;
use System;
use UserUtil;
use Zikula_Controller_AbstractAjax;
use Zikula_View;
use ZLanguage;
use Zikula\Core\RouteUrl;
use Zikula\Core\Response\Ajax\AjaxResponse;
use Zikula\Core\Response\Ajax\BadDataResponse;
use Zikula\Core\Response\Ajax\FatalResponse;
use Zikula\Core\Response\Ajax\NotFoundResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Zikula\Core\Response\PlainResponse;

/**
 * Ajax controller class.
 */
class AjaxController extends Zikula_Controller_AbstractAjax
{


    /**
     * This method is the default function handling the main area called without defining arguments.
     *
     * @param Request  $request      Current request instance
     * @param string  $ot           Treated object type.
     *
     * @return mixed Output.
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions.
     */
    public function indexAction(Request $request)
    {
        // parameter specifying which type of objects we are treating
        $objectType = $request->query->filter('ot', 'entry', false, FILTER_SANITIZE_STRING);
        
        $permLevel = ACCESS_OVERVIEW;
        if (!SecurityUtil::checkPermission($this->name . '::', '::', $permLevel)) {
            throw new AccessDeniedException();
        }
    }
    
    
    /**
     * Retrieve item list for finder selections in Forms, Content type plugin and Scribite.
     *
     * @param string $ot      Name of currently used object type.
     * @param string $sort    Sorting field.
     * @param string $sortdir Sorting direction.
     *
     * @return AjaxResponse
     */
    public function getItemListFinderAction(Request $request)
    {
        if (!SecurityUtil::checkPermission($this->name . '::Ajax', '::', ACCESS_EDIT)) {
            return true;
        }
        
        $objectType = 'entry';
        if ($request->isMethod('POST') && $request->request->has('ot')) {
            $objectType = $request->request->filter('ot', 'entry', false, FILTER_SANITIZE_STRING);
        } elseif ($request->isMethod('GET') && $request->query->has('ot')) {
            $objectType = $request->query->filter('ot', 'entry', false, FILTER_SANITIZE_STRING);
        }
        $controllerHelper = $this->serviceManager->get('mueternizermodule.controller_helper');
        $utilArgs = array('controller' => 'ajax', 'action' => 'getItemListFinder');
        if (!in_array($objectType, $controllerHelper->getObjectTypes('controllerAction', $utilArgs))) {
            $objectType = $controllerHelper->getDefaultObjectType('controllerAction', $utilArgs);
        }
        
        $repository = $this->serviceManager->get('mueternizermodule.' . $objectType . '_factory')->getRepository();
        $repository->setRequest($request);
        $idFields = ModUtil::apiFunc($this->name, 'selection', 'getIdFields', array('ot' => $objectType));
        
        $descriptionField = $repository->getDescriptionFieldName();
        
        $sort = $request->request->filter('sort', '', false, FILTER_SANITIZE_STRING);
        if (empty($sort) || !in_array($sort, $repository->getAllowedSortingFields())) {
            $sort = $repository->getDefaultSortingField();
        }
        
        $sdir = $request->request->filter('sortdir', '', false, FILTER_SANITIZE_STRING);
        $sdir = strtolower($sdir);
        if ($sdir != 'asc' && $sdir != 'desc') {
            $sdir = 'asc';
        }
        
        $where = ''; // filters are processed inside the repository class
        $sortParam = $sort . ' ' . $sdir;
        
        $entities = $repository->selectWhere($where, $sortParam);
        
        $slimItems = array();
        $component = $this->name . ':' . ucfirst($objectType) . ':';
        foreach ($entities as $item) {
            $itemId = '';
            foreach ($idFields as $idField) {
                $itemId .= ((!empty($itemId)) ? '_' : '') . $item[$idField];
            }
            if (!SecurityUtil::checkPermission($component, $itemId . '::', ACCESS_READ)) {
                continue;
            }
            $slimItems[] = $this->prepareSlimItem($objectType, $item, $itemId, $descriptionField);
        }
        
        return new AjaxResponse($slimItems);
    }
    
    /**
     * Builds and returns a slim data array from a given entity.
     *
     * @param string $objectType       The currently treated object type.
     * @param object $item             The currently treated entity.
     * @param string $itemid           Data item identifier(s).
     * @param string $descriptionField Name of item description field.
     *
     * @return array The slim data representation.
     */
    protected function prepareSlimItem($objectType, $item, $itemId, $descriptionField)
    {
        $view = Zikula_View::getInstance('MUEternizerModule', false);
        $view->assign($objectType, $item);
        $previewInfo = base64_encode($view->fetch('External/' . ucfirst($objectType) . '/info.tpl'));
    
        $title = $item->getTitleFromDisplayPattern();
        $description = ($descriptionField != '') ? $item[$descriptionField] : '';
    
        return array('id'          => $itemId,
                     'title'       => str_replace('&amp;', '&', $title),
                     'description' => $description,
                     'previewInfo' => $previewInfo);
    }
}
