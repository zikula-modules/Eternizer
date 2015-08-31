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

namespace MU\EternizerModule\Api\Base;

use ModUtil;
use SecurityUtil;
use ServiceUtil;
use UserUtil;
use Zikula\Core\Api\AbstractApi;

/**
 * Account api base class.
 */
class AccountApi extends AbstractApi
{
    /**
     * Return an array of items to show in the your account panel.
     *
     * @param array $args List of arguments.
     *
     * @return array List of collected account items
     */
    public function getall(array $args = array())
    {
        // collect items in an array
        $items = array();
    
        $useAccountPage = ModUtil::getVar('MUEternizerModule', 'useAccountPage', true);
        if ($useAccountPage === false) {
            return $items;
        }
    
        $userName = (isset($args['uname'])) ? $args['uname'] : UserUtil::getVar('uname');
        // does this user exist?
        if (UserUtil::getIdFromName($userName) === false) {
            // user does not exist
            return $items;
        }
    
        if (!SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_OVERVIEW)) {
            return $items;
        }
    
        // Create an array of links to return
        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            $items[] = array(
                'url'   => $this->get('router')->generate('mueternizermodule_admin_index'),
                'title'  => $this->__('Eternizer Backend'),
                'icon'   => 'configure.png',
                'module' => 'core',
                'set'    => 'icons/large'
            );
        }
    
        // return the items
        return $items;
    }
}