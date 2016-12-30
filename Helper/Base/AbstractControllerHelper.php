<?php
/**
 * Eternizer.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://www.webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

namespace MU\EternizerModule\Helper\Base;

use DataUtil;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Zikula\Common\Translator\TranslatorInterface;

/**
 * Helper base class for controller layer methods.
 */
abstract class AbstractControllerHelper
{
    /**
     * @var ContainerBuilder
     */
    protected $container;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * Constructor.
     * Initialises member vars.
     *
     * @param ContainerBuilder    $container  ContainerBuilder service instance
     * @param TranslatorInterface $translator Translator service instance
     */
    public function __construct(ContainerBuilder $container, TranslatorInterface $translator)
    {
        $this->container = $container;
        $this->translator = $translator;
    }

    /**
     * Returns an array of all allowed object types in MUEternizerModule.
     *
     * @param string $context Usage context (allowed values: controllerAction, api, helper, actionHandler, block, contentType, util)
     * @param array  $args    Additional arguments
     *
     * @return array List of allowed object types
     */
    public function getObjectTypes($context = '', $args = [])
    {
        if (!in_array($context, ['controllerAction', 'api', 'helper', 'actionHandler', 'block', 'contentType', 'util'])) {
            $context = 'controllerAction';
        }
    
        $allowedObjectTypes = [];
        $allowedObjectTypes[] = 'entry';
    
        return $allowedObjectTypes;
    }

    /**
     * Returns the default object type in MUEternizerModule.
     *
     * @param string $context Usage context (allowed values: controllerAction, api, helper, actionHandler, block, contentType, util)
     * @param array  $args    Additional arguments
     *
     * @return string The name of the default object type
     */
    public function getDefaultObjectType($context = '', $args = [])
    {
        if (!in_array($context, ['controllerAction', 'api', 'helper', 'actionHandler', 'block', 'contentType', 'util'])) {
            $context = 'controllerAction';
        }
    
        $defaultObjectType = 'entry';
    
        return $defaultObjectType;
    }

    /**
     * Checks whether a certain entity type uses composite keys or not.
     *
     * @param string $objectType The object type to retrieve
     *
     * @return Boolean Whether composite keys are used or not
     */
    public function hasCompositeKeys($objectType)
    {
        switch ($objectType) {
            case 'entry':
                return false;
                default:
                    return false;
        }
    }

    /**
     * Retrieve identifier parameters for a given object type.
     *
     * @param Request $request    The current request
     * @param array   $args       List of arguments used as fallback if request does not contain a field
     * @param string  $objectType Name of treated entity type
     * @param array   $idFields   List of identifier field names
     *
     * @return array List of fetched identifiers
     */
    public function retrieveIdentifier(Request $request, array $args, $objectType = '', array $idFields)
    {
        $idValues = [];
        $routeParams = $request->get('_route_params', []);
        foreach ($idFields as $idField) {
            $defaultValue = isset($args[$idField]) && is_numeric($args[$idField]) ? $args[$idField] : 0;
            if ($this->hasCompositeKeys($objectType)) {
                // composite key may be alphanumeric
                if (array_key_exists($idField, $routeParams)) {
                    $id = !empty($routeParams[$idField]) ? $routeParams[$idField] : $defaultValue;
                } elseif ($request->query->has($idField)) {
                    $id = $request->query->getAlnum($idField, $defaultValue);
                } else {
                    $id = $defaultValue;
                }
            } else {
                // single identifier
                if (array_key_exists($idField, $routeParams)) {
                    $id = (int) !empty($routeParams[$idField]) ? $routeParams[$idField] : $defaultValue;
                } elseif ($request->query->has($idField)) {
                    $id = $request->query->getInt($idField, $defaultValue);
                } else {
                    $id = $defaultValue;
                }
            }
    
            // fallback if id has not been found yet
            if (!$id && $idField != 'id' && count($idFields) == 1) {
                $defaultValue = isset($args['id']) && is_numeric($args['id']) ? $args['id'] : 0;
                if (array_key_exists('id', $routeParams)) {
                    $id = (int) !empty($routeParams['id']) ? $routeParams['id'] : $defaultValue;
                } elseif ($request->query->has('id')) {
                    $id = (int) $request->query->getInt('id', $defaultValue);
                } else {
                    $id = $defaultValue;
                }
            }
            $idValues[$idField] = $id;
        }
    
        return $idValues;
    }

    /**
     * Checks if all identifiers are set properly.
     *
     * @param array  $idValues List of identifier field values
     *
     * @return boolean Whether all identifiers are set or not
     */
    public function isValidIdentifier(array $idValues)
    {
        if (!count($idValues)) {
            return false;
        }
    
        foreach ($idValues as $idField => $idValue) {
            if (!$idValue) {
                return false;
            }
        }
    
        return true;
    }

    /**
     * Create nice permalinks.
     *
     * @param string $name The given object title
     *
     * @return string processed permalink
     * @deprecated made obsolete by Doctrine extensions
     */
    public function formatPermalink($name)
    {
        $name = str_replace(
            ['�', '�', '�', '�', '�', '�', '�', '.', '?', '"', '/', ':', '�', '�', '�'],
            ['ae', 'oe', 'ue', 'Ae', 'Oe', 'Ue', 'ss', '', '', '', '-', '-', 'e', 'e', 'a'],
            $name
        );
        $name = DataUtil::formatPermalink($name);
    
        return strtolower($name);
    }
}
