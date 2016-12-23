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

namespace MU\EternizerModule\Container;

use MU\EternizerModule\Container\Base\AbstractLinkContainer;
use Zikula\Core\LinkContainer\LinkContainerInterface;
use ModUtil;

/**
 * This is the link container service implementation class.
 */
class LinkContainer extends AbstractLinkContainer
{
    /**
     * Returns available header links.
     *
     * @param string $type The type to collect links for
     *
     * @return array Array of header links
     */
    public function getLinks($type = LinkContainerInterface::TYPE_ADMIN)
    {
        $utilArgs = ['api' => 'linkContainer', 'action' => 'getLinks'];
        $allowedObjectTypes = $this->controllerHelper->getObjectTypes('api', $utilArgs);

        $permLevel = LinkContainerInterface::TYPE_ADMIN == $type ? ACCESS_ADMIN : ACCESS_READ;

        // Create an array of links to return
        $links = [];

        if (LinkContainerInterface::TYPE_ACCOUNT == $type) {
            $useAccountPage = $this->variableApi->get('MUEternizerModule', 'useAccountPage', true);
            if (false === $useAccountPage) {
                return $links;
            }

            $userName = isset($args['uname']) ? $args['uname'] : $this->currentUserApi->get('uname');
            // does this user exist?
            if (false === UserUtil::getIdFromName($userName)) {
                // user does not exist
                return $links;
            }

            if (!$this->permissionApi->hasPermission($this->getBundleName() . '::', '::', ACCESS_OVERVIEW)) {
                return $links;
            }

            if ($this->permissionApi->hasPermission($this->getBundleName() . '::', '::', ACCESS_ADMIN)) {
                $links[] = [
                    'url' => $this->router->generate('mueternizermodule_admin_index'),
                    'text' => $this->__('Eternizer Backend'),
                    'icon' => 'wrench'
                ];
            }

            return $links;
        }

        
        if (LinkContainerInterface::TYPE_ADMIN == $type) {
            if ($this->permissionApi->hasPermission($this->getBundleName() . '::', '::', ACCESS_READ)) {
                $links[] = [
                    'url' => $this->router->generate('mueternizermodule_user_index'),
                    'text' => $this->__('Frontend'),
                    'title' => $this->__('Switch to user area.'),
                    'icon' => 'home'
                ];
            }
            
            if (in_array('entry', $allowedObjectTypes)
                && $this->permissionApi->hasPermission($this->getBundleName() . ':Entry:', '::', $permLevel)) {
                $links[] = [
                    'url' => $this->router->generate('mueternizermodule_entry_adminview'),
                     'text' => $this->__('Entries'),
                     'title' => $this->__('Entry list')
                 ];
            }
            if ($this->permissionApi->hasPermission($this->getBundleName() . '::', '::', ACCESS_ADMIN)) {
                $links[] = [
                    'url' => $this->router->generate('mueternizermodule_config_config'),
                    'text' => $this->__('Configuration'),
                    'title' => $this->__('Manage settings for this application'),
                    'icon' => 'wrench'
                ];
            }
        }
        if (LinkContainerInterface::TYPE_USER == $type) {
            if ($this->permissionApi->hasPermission($this->getBundleName() . '::', '::', ACCESS_ADMIN)) {
                $links[] = [
                    'url' => $this->router->generate('mueternizermodule_admin_index'),
                    'text' => $this->__('Backend'),
                    'title' => $this->__('Switch to administration area.'),
                    'icon' => 'wrench'
                ];
            }
            
            if (in_array('entry', $allowedObjectTypes)
                && $this->permissionApi->hasPermission($this->getBundleName() . ':Entry:', '::', $permLevel)) {
                $links[] = [
                    'url' => $this->router->generate('mueternizermodule_entry_view'),
                     'text' => $this->__('Entries'),
                     'title' => $this->__('Entry list')
                 ];
            }
            if (in_array('entry', $allowedObjectTypes)
            		&& $this->permissionApi->hasPermission($this->getBundleName() . ':Entry:', '::', $permLevel)  && \ModUtil::getVar('MUEternizerModule', 'formposition') == 'menue') {
            			$links[] = [
            					'url' => $this->router->generate('mueternizermodule_entry_edit'),
            					'text' => $this->__('New entry'),
            					'title' => $this->__('Create entry')
            			];
            }
            if ($this->permissionApi->hasPermission($this->getBundleName() . '::', '::', ACCESS_ADMIN)) {
                $links[] = [
                    'url' => $this->router->generate('mueternizermodule_config_config'),
                    'text' => $this->__('Configuration'),
                    'title' => $this->__('Manage settings for this application'),
                    'icon' => 'wrench'
                ];
            }
        }

        return $links;
    }
}
