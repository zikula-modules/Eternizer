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

namespace MU\EternizerModule\Util;

use MU\EternizerModule\Util\Base\SettingsUtil as BaseSettingsUtil;

use ModUtil;
use UserUtil;

/**
 * Utility implementation class for settings helper methods.
 */
class SettingsUtil extends BaseSettingsUtil
{
    public function handleModvarsPreSave()
    {
        $modvar = $this->getModvars();

        if ($modvar['ipsave'] == true) {
            $ip = System::serverGetVar('REMOTE_ADDR');
            $this->setIp($ip);
        }

        if ($modvar['moderate'] == 'guests') {

            if (UserUtil::isLoggedIn() === false) {
                $this->setObj_status('M');
            }
            else {
                $this->setObj_status('A');
            }
        }
        elseif ($modvar['moderate'] == 'all') {

            $this->setObj_status('M');
        }

        return true;
    }

    public function handleModvarsPostPersist($args)
    {
    	$handler = new Zikula_Form_View($serviceManager, 'Eternizer');

        $modvar = $this->getModvars();

        $userid = $this->getCreatedUserId();

        $id = $args['id'];
        $text = $args['text'];

        $host = System::serverGetVar('HTTP_HOST') . '/';
        $url = 'http://' . $host . ModUtil::url('Eternizer', 'user', 'view');
        $editurl = 'http://' . $host . ModUtil::url('Eternizer', 'admin', 'edit', array('ot' => 'entry', 'id' => $id));

        $from = ModUtil::getVar('ZConfig', 'sitename');
        $fromaddress = ModUtil::getVar('ZConfig', 'adminmail');
        $toaddress = $modvar['mail'];

        $messagecontent = array();
        $messagecontent['from'] = $from;
        $messagecontent['fromaddress'] = $fromaddress;
        $messagecontent['toname'] = 'Webmaster';
        $messagecontent['toaddress'] = $toaddress;
        $messagecontent['subject'] = $handler->__('New Entry in Guestbook on ') . $from;
        $messagecontent['body'] = $handler->__('Another entry was created by an user on '). '<h2>' . $from . '</h2>' .
                $handler->__('Text') . '<br />' . $text . '<br /><br />' . $handler->__('Visit our guestbook:') .
                '<br />' . '<a href="' . $url . '">' . $url . '</a><br />' . $handler->__('Moderate this entry:') .
                '<br />' . '<a href="' . $editurl . '">' . $editurl . '</a>';
        $messagecontent['altbody'] = '';
        $messagecontent['html'] = true;

        // We send a mail if an email address is saved
        if ($toaddress != '') {

            if (!ModUtil::apiFunc('ZikulaMailerModule', 'user', 'sendmessage', $messagecontent)) {
                LogUtil::registerError($handler->__('Unable to send message'));
            }
        }

        // Formating of email text
        $message = $handler->__('Your entry was published!');

        if ($modvar['moderate'] == 'guests') {

            if ($userid < 2) {
                $message = $handler->__('Your entry was saved and must be confirmed by our team');
            }
        }
        elseif ($modvar['moderate'] == 'all') {

            $message = $handler->__('Your entry was saved and must be confirmed by our team');
        }

        LogUtil::registerStatus($message);

        return true;

    }

    /**
     *
     * this method will handle a moderation on the view template
     * Enter description here ...
     */
    public function handleChange()
    {
        // TODO
    }

    public function getModvars()
    {

        $modvar['ipsave'] = ModUtil::getVar('MUEternizerModule', 'ipsave');
        $modvar['moderate'] = ModUtil::getVar('MUEternizerModule', 'moderate');
        $modvar['mail'] = ModUtil::getVar('MUEternizerModule', 'mail');
        $modvar['editentries'] = ModUtil::getVar('MUEternizerModule', 'editentries');
        $modvar['period'] = ModUtil::getVar('MUEternizerModule', 'period');

        return $modvar;
    }
}