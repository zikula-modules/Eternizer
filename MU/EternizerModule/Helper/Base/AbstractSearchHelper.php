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

namespace MU\EternizerModule\Helper\Base;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Composite;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Zikula\Core\RouteUrl;
use Zikula\PermissionsModule\Api\ApiInterface\PermissionApiInterface;
use Zikula\SearchModule\Entity\SearchResultEntity;
use Zikula\SearchModule\SearchableInterface;
use MU\EternizerModule\Entity\Factory\EntityFactory;
use MU\EternizerModule\Helper\ControllerHelper;
use MU\EternizerModule\Helper\EntityDisplayHelper;

/**
 * Search helper base class.
 */
abstract class AbstractSearchHelper implements SearchableInterface
{
    use TranslatorTrait;
    
    /**
     * @var PermissionApiInterface
     */
    protected $permissionApi;
    
    /**
     * @var SessionInterface
     */
    private $session;
    
    /**
     * @var Request
     */
    private $request;
    
    /**
     * @var EntityFactory
     */
    private $entityFactory;
    
    /**
     * @var ControllerHelper
     */
    private $controllerHelper;
    
    /**
     * @var EntityDisplayHelper
     */
    protected $entityDisplayHelper;
    
    /**
     * SearchHelper constructor.
     *
     * @param TranslatorInterface $translator          Translator service instance
     * @param PermissionApiInterface $permissionApi    PermissionApi service instance
     * @param SessionInterface    $session             Session service instance
     * @param RequestStack        $requestStack        RequestStack service instance
     * @param EntityFactory       $entityFactory       EntityFactory service instance
     * @param ControllerHelper    $controllerHelper    ControllerHelper service instance
     * @param EntityDisplayHelper $entityDisplayHelper EntityDisplayHelper service instance
     */
    public function __construct(
        TranslatorInterface $translator,
        PermissionApiInterface $permissionApi,
        SessionInterface $session,
        RequestStack $requestStack,
        EntityFactory $entityFactory,
        ControllerHelper $controllerHelper,
        EntityDisplayHelper $entityDisplayHelper
    ) {
        $this->setTranslator($translator);
        $this->permissionApi = $permissionApi;
        $this->session = $session;
        $this->request = $requestStack->getCurrentRequest();
        $this->entityFactory = $entityFactory;
        $this->controllerHelper = $controllerHelper;
        $this->entityDisplayHelper = $entityDisplayHelper;
    }
    
    /**
     * Sets the translator.
     *
     * @param TranslatorInterface $translator Translator service instance
     */
    public function setTranslator(/*TranslatorInterface */$translator)
    {
        $this->translator = $translator;
    }
    
    /**
     * @inheritDoc
     */
    public function amendForm(FormBuilderInterface $builder)
    {
        if (!$this->permissionApi->hasPermission('MUEternizerModule::', '::', ACCESS_READ)) {
            return '';
        }
    
        $builder->add('active', HiddenType::class, [
            'data' => true
        ]);
    
        $searchTypes = $this->getSearchTypes();
    
        foreach ($searchTypes as $searchType => $typeInfo) {
            $builder->add('active_' . $searchType, CheckboxType::class, [
                'value' => $typeInfo['value'],
                'label' => $typeInfo['label'],
                'label_attr' => ['class' => 'checkbox-inline'],
                'required' => false
            ]);
        }
    }
    
    /**
     * @inheritDoc
     */
    public function getResults(array $words, $searchType = 'AND', $modVars = null)
    {
        if (!$this->permissionApi->hasPermission('MUEternizerModule::', '::', ACCESS_READ)) {
            return [];
        }
    
        // initialise array for results
        $results = [];
    
        // retrieve list of activated object types
        $searchTypes = $this->getSearchTypes();
        $entitiesWithDisplayAction = ['entry'];
    
        foreach ($searchTypes as $searchTypeCode => $typeInfo) {
            $isActivated = false;
            $searchSettings = $this->request->query->get('zikulasearchmodule_search', []);
            $moduleActivationInfo = $searchSettings['modules'];
            if (isset($moduleActivationInfo['MUEternizerModule'])) {
                $moduleActivationInfo = $moduleActivationInfo['MUEternizerModule'];
                $isActivated = isset($moduleActivationInfo['active_' . $searchTypeCode]);
            }
            if (!$isActivated) {
                continue;
            }
    
            $objectType = $typeInfo['value'];
            $whereArray = [];
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
                    break;
            }
    
            $repository = $this->entityFactory->getRepository($objectType);
    
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
    
            $descriptionFieldName = $this->entityDisplayHelper->getDescriptionFieldName($objectType);
            $hasDisplayAction = in_array($objectType, $entitiesWithDisplayAction);
    
            foreach ($entities as $entity) {
                // perform permission check
                if (!$this->permissionApi->hasPermission('MUEternizerModule:' . ucfirst($objectType) . ':', $entity->getKey() . '::', ACCESS_OVERVIEW)) {
                    continue;
                }
    
                $description = !empty($descriptionFieldName) ? $entity[$descriptionFieldName] : '';
                $created = isset($entity['createdDate']) ? $entity['createdDate'] : null;
    
                $formattedTitle = $this->entityDisplayHelper->getFormattedTitle($entity);
                $displayUrl = '';
                if ($hasDisplayAction) {
                    $urlArgs = $entity->createUrlArgs();
                    $urlArgs['_locale'] = (null !== $languageField && !empty($entity[$languageField])) ? $entity[$languageField] : $this->request->getLocale();
                    $displayUrl = new RouteUrl('mueternizermodule_' . strtolower($objectType) . '_display', $urlArgs);
                }
    
                $result = new SearchResultEntity();
                $result->setTitle($formattedTitle)
                    ->setText($description)
                    ->setModule('MUEternizerModule')
                    ->setCreated($created)
                    ->setSesid($this->session->getId())
                    ->setUrl($displayUrl);
                $results[] = $result;
            }
        }
    
        return $results;
    }
    
    /**
     * Returns list of supported search types.
     *
     * @return array
     */
    protected function getSearchTypes()
    {
        $searchTypes = [
            'mUEternizerModuleEntries' => [
                'value' => 'entry',
                'label' => $this->__('Entries')
            ]
        ];
    
        $allowedTypes = $this->controllerHelper->getObjectTypes('helper', ['helper' => 'search', 'action' => 'getSearchTypes']);
        $allowedSearchTypes = [];
        foreach ($searchTypes as $searchType => $typeInfo) {
            if (!in_array($typeInfo['value'], $allowedTypes)) {
                continue;
            }
            $allowedSearchTypes[$searchType] = $typeInfo;
        }
    
        return $allowedSearchTypes;
    }
    
    /**
     * @inheritDoc
     */
    public function getErrors()
    {
        return [];
    }
    
    /**
     * Construct a QueryBuilder Where orX|andX Expr instance.
     *
     * @param QueryBuilder $qb
     * @param array $words the words to query for
     * @param array $fields
     * @param string $searchtype AND|OR|EXACT
     *
     * @return null|Composite
     */
    protected function formatWhere(QueryBuilder $qb, array $words, array $fields, $searchtype = 'AND')
    {
        if (empty($words) || empty($fields)) {
            return null;
        }
    
        $method = ($searchtype == 'OR') ? 'orX' : 'andX';
        /** @var $where Composite */
        $where = $qb->expr()->$method();
        $i = 1;
        foreach ($words as $word) {
            $subWhere = $qb->expr()->orX();
            foreach ($fields as $field) {
                $expr = $qb->expr()->like($field, "?$i");
                $subWhere->add($expr);
                $qb->setParameter($i, '%' . $word . '%');
                $i++;
            }
            $where->add($subWhere);
        }
    
        return $where;
    }
}
