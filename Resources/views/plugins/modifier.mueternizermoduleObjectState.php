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

/**
 * The mueternizermoduleObjectState modifier displays the name of a given object's workflow state.
 * Examples:
 *    {$item.workflowState|mueternizermoduleObjectState}       {* with visual feedback *}
 *    {$item.workflowState|mueternizermoduleObjectState:false} {* no ui feedback *}
 *
 * @param string  $state      Name of given workflow state.
 * @param boolean $uiFeedback Whether the output should include some visual feedback about the state.
 *
 * @return string Enriched and translated workflow state ready for display.
 */
function smarty_modifier_mueternizermoduleObjectState($state = 'initial', $uiFeedback = true)
{
    $serviceManager = ServiceUtil::getManager();
    $workflowHelper = $serviceManager->get('mueternizermodule.workflow_helper');

    $stateInfo = $workflowHelper->getStateInfo($state);

    $result = $stateInfo['text'];
    if ($uiFeedback === true) {
        $result = '<span class="label label-' . $stateInfo['ui'] . '">' . $result . '</span>';
    }

    return $result;
}