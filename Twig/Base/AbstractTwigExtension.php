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

namespace MU\EternizerModule\Twig\Base;

use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Zikula\Core\Doctrine\EntityAccess;
use Zikula\ExtensionsModule\Api\VariableApi;
use MU\EternizerModule\Helper\ListEntriesHelper;
use MU\EternizerModule\Helper\WorkflowHelper;
use MU\EternizerModule\Container\LinkContainer;

/**
 * Twig extension base class.
 */
abstract class AbstractTwigExtension extends \Twig_Extension
{
    use TranslatorTrait;
    
    /**
     * @var VariableApi
     */
    protected $variableApi;
    
    /**
     * @var LinkContainer
     */
    protected $linkContainer;
    
    /**
     * @var WorkflowHelper
     */
    protected $workflowHelper;
    
    /**
     * @var ListEntriesHelper
     */
    protected $listHelper;
    
    /**
     * Constructor.
     * Initialises member vars.
     *
     * @param TranslatorInterface $translator     Translator service instance
     * @param VariableApi         $variableApi    VariableApi service instance
     * @param LinkContainer       $linkContainer  LinkContainer service instance
     * @param WorkflowHelper      $workflowHelper WorkflowHelper service instance
     * @param ListEntriesHelper   $listHelper     ListEntriesHelper service instance
     */
    public function __construct(TranslatorInterface $translator, VariableApi $variableApi, LinkContainer $linkContainer, WorkflowHelper $workflowHelper, ListEntriesHelper $listHelper)
    {
        $this->setTranslator($translator);
        $this->variableApi = $variableApi;
        $this->linkContainer = $linkContainer;
        $this->workflowHelper = $workflowHelper;
        $this->listHelper = $listHelper;
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
     * Returns a list of custom Twig functions.
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('mueternizermodule_actions', [$this, 'getActionLinks']),
            new \Twig_SimpleFunction('mueternizermodule_objectTypeSelector', [$this, 'getObjectTypeSelector']),
            new \Twig_SimpleFunction('mueternizermodule_templateSelector', [$this, 'getTemplateSelector']),
            new \Twig_SimpleFunction('mueternizermodule_userVar', [$this, 'getUserVar']),
            new \Twig_SimpleFunction('mueternizermodule_userAvatar', [$this, 'getUserAvatar'], ['is_safe' => ['html']])
        ];
    }
    
    /**
     * Returns a list of custom Twig filters.
     *
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('mueternizermodule_listEntry', [$this, 'getListEntry']),
            new \Twig_SimpleFilter('mueternizermodule_objectState', [$this, 'getObjectState'], ['is_safe' => ['html']])
        ];
    }
    
    /**
     * Returns action links for a given entity.
     *
     * @param EntityAccess $entity  The entity
     * @param string       $area    The context area name (e.g. admin or nothing for user)
     * @param string       $context The context page name (e.g. view, display, edit, delete)
     *
     * @return array Array of action links
     */
    public function getActionLinks(/*EntityAccess */$entity, $area = '', $context = 'view')
    {
        return $this->linkContainer->getActionLinks($entity, $area, $context);
    }
    
    /**
     * The mueternizermodule_objectState filter displays the name of a given object's workflow state.
     * Examples:
     *    {{ item.workflowState|mueternizermodule_objectState }}        {# with visual feedback #}
     *    {{ item.workflowState|mueternizermodule_objectState(false) }} {# no ui feedback #}
     *
     * @param string  $state      Name of given workflow state
     * @param boolean $uiFeedback Whether the output should include some visual feedback about the state
     *
     * @return string Enriched and translated workflow state ready for display
     */
    public function getObjectState($state = 'initial', $uiFeedback = true)
    {
        $stateInfo = $this->workflowHelper->getStateInfo($state);
    
        $result = $stateInfo['text'];
        if ($uiFeedback === true) {
            $result = '<span class="label label-' . $stateInfo['ui'] . '">' . $result . '</span>';
        }
    
        return $result;
    }
    
    
    /**
     * The mueternizermodule_listEntry filter displays the name
     * or names for a given list item.
     * Example:
     *     {{ entity.listField|mueternizermodule_listEntry('entityName', 'fieldName') }}
     *
     * @param string $value      The dropdown value to process
     * @param string $objectType The treated object type
     * @param string $fieldName  The list field's name
     * @param string $delimiter  String used as separator for multiple selections
     *
     * @return string List item name
     */
    public function getListEntry($value, $objectType = '', $fieldName = '', $delimiter = ', ')
    {
        if ((empty($value) && $value != '0') || empty($objectType) || empty($fieldName)) {
            return $value;
        }
    
        return $this->listHelper->resolve($value, $objectType, $fieldName, $delimiter);
    }
    
    
    /**
     * The mueternizermodule_objectTypeSelector function provides items for a dropdown selector.
     *
     * @return string The output of the plugin
     */
    public function getObjectTypeSelector()
    {
        $result = [];
    
        $result[] = ['text' => $this->__('Entries'), 'value' => 'entry'];
    
        return $result;
    }
    
    
    /**
     * The mueternizermodule_templateSelector function provides items for a dropdown selector.
     *
     * @return string The output of the plugin
     */
    public function getTemplateSelector()
    {
        $result = [];
    
        $result[] = ['text' => $this->__('Only item titles'), 'value' => 'itemlist_display.html.twig'];
        $result[] = ['text' => $this->__('With description'), 'value' => 'itemlist_display_description.html.twig'];
        $result[] = ['text' => $this->__('Custom template'), 'value' => 'custom'];
    
        return $result;
    }
    
    /**
     * Returns the value of a user variable.
     *
     * @param string     $name    Name of desired property
     * @param int        $uid     The user's id
     * @param string|int $default The default value
     *
     * @return string
     */
    public function getUserVar($name, $uid = -1, $default = '')
    {
        if (!$uid) {
            $uid = -1;
        }
    
        $result = \UserUtil::getVar($name, $uid, $default);
    
        return $result;
    }
    
    /**
     * Display the avatar of a user.
     *
     * @param int    $uid    The user's id
     * @param int    $width  Image width (optional)
     * @param int    $height Image height (optional)
     * @param int    $size   Gravatar size (optional)
     * @param string $rating Gravatar self-rating [g|pg|r|x] see: http://en.gravatar.com/site/implement/images/ (optional)
     *
     * @return string
     */
    public function getUserAvatar($uid, $width = 0, $height = 0, $size = 0, $rating = '')
    {
        $params = ['uid' => $uid];
        if ($width > 0) {
            $params['width'] = $width;
        }
        if ($height > 0) {
            $params['height'] = $height;
        }
        if ($size > 0) {
            $params['size'] = $size;
        }
        if ($rating != '') {
            $params['rating'] = $rating;
        }
    
        include_once 'lib/legacy/viewplugins/function.useravatar.php';
    
        $view = \Zikula_View::getInstance('MUEternizerModule');
        $result = smarty_function_useravatar($params, $view);
    
        return $result;
    }
}