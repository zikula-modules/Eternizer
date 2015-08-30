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

namespace MU\EternizerModule\Helper\Base;

use ModUtil;
use SecurityUtil;
use ServiceUtil;
use ZLanguage;

use Zikula\Core\RouteUrl;
use Zikula\Module\SearchModule\AbstractSearchable;

/**
 * Search helper base class.
 */
class SearchHelper extends AbstractSearchable
{
    /**
     * Display the search form.
     *
     * @param boolean    $active
     * @param array|null $modVars
     *
     * @return string Template output
     */
    public function getOptions($active, $modVars = null)
    {
        if (!SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_READ)) {
            return '';
        }
    
        $this->view->assign('active_entry', (!isset($args['active_entry']) || isset($args['active']['active_entry'])));
    
        return $this->view->fetch('Search/options.tpl');
    }
    
    /**
     * Returns the search results.
     *
     * @param array      $words      Array of words to search for
     * @param string     $searchType AND|OR|EXACT (defaults to AND)
     * @param array|null $modVars    Module form vars passed though
     *
     * @return array List of fetched results.
     */
    public function getResults(array $words, $searchType = 'AND', $modVars = null)
    {
        if (!SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_READ)) {
            return array();
        }
    
        $serviceManager = ServiceUtil::getManager();
    
        // save session id as it is used when inserting search results below
        $session = $serviceManager->get('session');
        $sessionId = $session->getId();
    
        // save current language
        $languageCode = ZLanguage::getLanguageCode();
    
        // initialise array for results
        $records = array();
    
        // retrieve list of activated object types
        $searchTypes = isset($modVars['objectTypes']) ? (array)$modVars['objectTypes'] : array();
    
        $controllerHelper = $serviceManager->get('mueternizermodule.controller_helper');
        $utilArgs = array('helper' => 'search', 'action' => 'getResults');
        $allowedTypes = $controllerHelper->getObjectTypes('helper', $utilArgs);
    
        foreach ($searchTypes as $objectType) {
            if (!in_array($objectType, $allowedTypes)) {
                continue;
            }
    
            $whereArray = array();
            $languageField = null;
            switch ($objectType) {
                case 'entry':
                    $whereArray[] = 'tbl.workflowState';
                    $whereArray[] = 'tbl.ip';
                    $whereArray[] = 'tbl.name';
                    $whereArray[] = 'tbl.email';
                    $whereArray[] = 'tbl.homepage';
                    $whereArray[] = 'tbl.location';
                    $whereArray[] = 'tbl.text';
                    $whereArray[] = 'tbl.notes';
                    $whereArray[] = 'tbl.obj_status';
                    break;
            }
    
            $repository = $serviceManager->get('mueternizermodule.' . $objectType . '_factory')->getRepository();
    
            // build the search query without any joins
            $qb = $repository->genericBaseQuery('', '', false);
    
            // build where expression for given search type
            $whereExpr = $this->formatWhere($qb, $words, $whereArray, $searchType);
            $qb->andWhere($whereExpr);
    
            $query = $qb->getQuery();
    
            // set a sensitive limit
            $query->setFirstResult(0)
                  ->setMaxResults(250);
    
            // fetch the results
            $entities = $query->getResult();
    
            if (count($entities) == 0) {
                continue;
            }
    
            $idFields = ModUtil::apiFunc($this->name, 'selection', 'getIdFields', array('ot' => $objectType));
            $descriptionField = $repository->getDescriptionFieldName();
    
            $entitiesWithDisplayAction = array('entry');
    
            foreach ($entities as $entity) {
                $urlArgs = $entity->createUrlArgs();
                $hasDisplayAction = in_array($objectType, $entitiesWithDisplayAction);
    
                $instanceId = $entity->createCompositeIdentifier();
                // perform permission check
                if (!SecurityUtil::checkPermission($this->name . ':' . ucfirst($objectType) . ':', $instanceId . '::', ACCESS_OVERVIEW)) {
                    continue;
                }
    
                $description = !empty($descriptionField) ? $entity[$descriptionField] : '';
                $created = isset($entity['createdDate']) ? $entity['createdDate'] : null;
    
                // override language if required
                if ($languageField != null) {
                    $languageCode = $entity[$languageField];
                }
    
                $displayUrl = $hasDisplayAction ? new RouteUrl('mueternizermodule_' . $objectType . '_display', $urlArgs) : '';
    
                $records[] = array(
                    'title' => $entity->getTitleFromDisplayPattern(),
                    'text' => $description,
                    'module' => $this->name,
                    'sesid' => $sessionId,
                    'created' => $created,
                    'url' => $displayUrl
                );
            }
        }
    
        return $records;
    }
}
