<?php
/**
 * Eternizer.
 *
 * @copyright Michael Ueberschaer
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package MUBoard
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://www.webdesign-in-bremen.com
 * @link http://zikula.org
 */

use MU\Eternizer\Util;
/**
 * The eternizerGetStateOfEditOfIssue return the state of edit -> yes or not.
 *
 * @param  int       $id      entry id
 *
 * @return out html
 */
function smarty_modifier_mueternizermoduleGetStateOfEditOfEntry($id)
{
    $serviceManager = ServiceUtil::getManager();
    $viewHelper = $serviceManager->get('mueternizermodule.view_helper');
    
	$out = $viewHelper->getStateOfEditOfEntry($id);

	return $out;
}
