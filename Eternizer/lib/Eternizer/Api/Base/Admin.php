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
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Sat Dec 31 13:59:03 CET 2011.
 */

/**
 * This is the Admin api helper class.
 */
class Eternizer_Api_Base_Admin extends Zikula_AbstractApi
{
    /**
     * get available Admin panel links
     *
     * @return array Array of admin links
     */
    public function getlinks()
    {
        $links = array();

        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_READ)) {
            $links[] = array('url' => ModUtil::url($this->name, 'user', 'main'),
                             'text' => $this->__('Frontend'),
                             'title' => $this->__('Switch to user area.'),
                             'class' => 'z-icon-es-home');
        }
        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            $links[] = array('url' => ModUtil::url($this->name, 'admin', 'view', array('ot' => 'entry')),
                             'text' => $this->__('Entries'),
                             'title' => $this->__('Entry list'));
        }
        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            $links[] = array('url' => ModUtil::url($this->name, 'admin', 'config'),
                             'text' => $this->__('Configuration'),
                             'title' => $this->__('Manage settings for this application'));
        }
        return $links;
    }
}
