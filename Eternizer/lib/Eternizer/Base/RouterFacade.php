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
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Tue Jan 03 13:01:47 CET 2012.
 */

/**
 * Url router facade base class
 */
class Eternizer_Base_RouterFacade
{
    /**
     * @var Zikula_Routing_UrlRouter The router which is used internally
     */
    protected $router;

    /**
     * @var array Common requirement definitions
     */
    protected $requirements;

    /**
     * Constructor.
     */
    function __construct()
    {
        $displayDefaultEnding = System::getVar('shorturlsext', 'html');
        $this->requirements = array(
            'func'          => '\w+',
            'ot'            => '\w+',
            'title'         => '[^/.]+', // title used for slugs ([^/.]+ = all chars except / and .)
            'displayending' => '(?:' . $displayDefaultEnding . '|xml|pdf|json)',
            'viewending'    => '(?:\.csv|\.rss|\.atom|\.xml|\.pdf|\.json)?',
            'id'            => '\d+'
        );

        // initialise and reference router instance
        $this->router = new Zikula_Routing_UrlRouter();

        // add generic routes
        return $this->initUrlRoutes();
    }

    /**
     * Initialise the url routes for this application.
     *
     * @return Zikula_Routing_UrlRouter The router instance treating all initialised routes
     */
    protected function initUrlRoutes()
    {
        $fieldRequirements = $this->requirements;
        $isDefaultModule = (System::getVar('shorturlsdefaultmodule', '') == 'Eternizer');

        $defaults = array();
        $modulePrefix = '';
        if (!$isDefaultModule) {
            $defaults['module'] = 'Eternizer';
            $modulePrefix = ':module/';
        }

        $defaults['func'] = 'view';
        $viewFolder = 'view';
        // normal views (e.g. orders/ or customers.xml)
        $this->router->set('va', new Zikula_Routing_UrlRoute($modulePrefix . $viewFolder . '/:ot:viewending', $defaults, $fieldRequirements));

        // TODO filter views (e.g. /orders/customer/mr-smith.csv)
        // $this->initRouteForEachSlugType('vn', $modulePrefix . $viewFolder . '/:ot/:filterot/', ':viewending', $defaults, $fieldRequirements);

        $defaults['func'] = 'display';
        // normal display pages including the group folder corresponding to the object type
        $this->initRouteForEachSlugType('dn', $modulePrefix . ':ot/', ':displayending', $defaults, $fieldRequirements);

        // additional rules for the leading object type (where ot is omitted)
        $defaults['ot'] = 'entry';
        $this->initRouteForEachSlugType('dl', $modulePrefix . '', ':displayending', $defaults, $fieldRequirements);

        return $this->router;
    }

    /**
     * Helper function to route permalinks for different slug types.
     */
    protected function initRouteForEachSlugType($prefix, $patternStart, $patternEnd, $defaults, $fieldRequirements)
    {
        // entities with unique slug (slug only)
        $this->router->set($prefix . 'a', new Zikula_Routing_UrlRoute($patternStart . ':title.' . $patternEnd, $defaults, $fieldRequirements));
        // entities with non-unique slug (slug and id)
        $this->router->set($prefix . 'b', new Zikula_Routing_UrlRoute($patternStart . ':title.:id.' . $patternEnd, $defaults, $fieldRequirements));
        // entities without slug (id)
        $this->router->set($prefix . 'c', new Zikula_Routing_UrlRoute($patternStart . 'id.:id.' . $patternEnd, $defaults, $fieldRequirements));
    }

    /**
     * Get name of grouping folder for given object type and function.
     *
     * @param string $objectType Name of treated entity type.
     * @param string $func       Name of function.
     *
     * @return $string Name of the group folder
     */
    public function getGroupingFolderFromObjectType($objectType, $func)
    {
        // object type will be used as a fallback
        $groupFolder = $objectType;

        if ($func == 'view') {
            switch ($objectType) {
                case 'entry':
                    $groupFolder = 'entries';
                    break;
                default:
                    return '';
            }
        } else if ($func == 'display') {
            switch ($objectType) {
                case 'entry':
                    $groupFolder = 'entry';
                    break;
                default:
                    return '';
            }
        }

        return $groupFolder;
    }

    /**
     * Get name of object type based on given grouping folder.
     *
     * @param string $groupFolder Name of group folder.
     * @param string $func        Name of function.
     *
     * @return string Name of the object type.
     */
    public function getObjectTypeFromGroupingFolder($groupFolder, $func)
    {
        // group folder will be used as a fallback
        $objectType = $groupFolder;

        if ($func == 'view') {
            switch ($groupFolder) {
                case 'entries':
                    $objectType = 'entry';
                    break;
                default:
                    return '';
            }
        } else if ($func == 'display') {
            switch ($groupFolder) {
                case 'entry':
                    $objectType = 'entry';
                    break;
                default:
                    return '';
            }
        }

        return $objectType;
    }

    /**
     * Get permalink value based on slug properties.
     *
     * @param string  $objectType Name of treated entity type.
     * @param string  $func       Name of function.
     * @param array   $args       Additional parameters.
     * @param integer $itemid     Identifier of treated item.
     *
     * @return string The resulting url ending.
     */
    public function getFormattedSlug($objectType, $func, $args, $itemid)
    {
        $slug = '';
        $serviceManager = ServiceUtil::getManager();
        $entityManager = $serviceManager->getService('doctrine.entitymanager');

        switch ($objectType) {
            case 'entry':
                $slug = $itemid;
                break;
        }

        return $slug;
    }

    /**
     * Get router.
     *
     * @return Zikula_Routing_UrlRouter
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Set router.
     *
     * @param Zikula_Routing_UrlRouter $router.
     *
     * @return void
     */
    public function setRouter(Zikula_Routing_UrlRouter $router = null)
    {
        $this->router = $router;
    }

}
