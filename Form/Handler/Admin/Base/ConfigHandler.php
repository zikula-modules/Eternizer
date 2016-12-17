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

namespace MU\EternizerModule\Form\Handler\Admin\Base;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use ModUtil;
use ServiceUtil;
use System;
use UserUtil;
use Zikula_Form_AbstractHandler;
use Zikula_Form_View;

/**
 * Configuration handler base class.
 */
class ConfigHandler extends Zikula_Form_AbstractHandler
{
    /**
     * Post construction hook.
     *
     * @return mixed
     */
    public function setup()
    {
    }

    /**
     * Initialize form handler.
     *
     * This method takes care of all necessary initialisation of our data and form states.
     *
     * @param Zikula_Form_View $view The form view instance.
     *
     * @return boolean False in case of initialization errors, otherwise true.
     *
     * @throws AccessDeniedException Thrown if the user doesn't have admin permissions
     * @throws RuntimeException          Thrown if persisting configuration vars fails
     */
    public function initialize(Zikula_Form_View $view)
    {
        // permission check
        $serviceManager = ServiceUtil::getManager();
        if (!$serviceManager->get('zikula_permissions_module.api.permission')->hasPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            throw new AccessDeniedException();
        }

        // retrieve module vars
        $modVars = $this->getVars();

        // initialise list entries for the 'order' setting
        
        $modVars['orderItems'] = array(array('value' => 'descending', 'text' => 'Descending'),
        array('value' => 'ascending', 'text' => 'Ascending')
        );
        // initialise list entries for the 'moderate' setting
        
        $modVars['moderateItems'] = array(array('value' => 'nothing', 'text' => 'Nothing'),
        array('value' => 'guests', 'text' => 'Guests'),
        array('value' => 'all', 'text' => 'All')
        );
        // initialise list entries for the 'formposition' setting
        
        $modVars['formpositionItems'] = array(array('value' => 'above', 'text' => 'Above'),
        array('value' => 'below', 'text' => 'Below'),
        array('value' => 'menue', 'text' => 'Menue')
        );

        // assign all module vars
        $this->view->assign('config', $modVars);

        // custom initialisation aspects
        $this->initializeAdditions();

        // everything okay, no initialization errors occured
        return true;
    }

    /**
     * Method stub for own additions in subclasses.
     */
    protected function initializeAdditions()
    {
    }

    /**
     * Pre-initialise hook.
     *
     * @return void
     */
    public function preInitialize()
    {
    }

    /**
     * Post-initialise hook.
     *
     * @return void
     */
    public function postInitialize()
    {
    }

    /**
     * Command event handler.
     *
     * This event handler is called when a command is issued by the user. Commands are typically something
     * that originates from a {@link Zikula_Form_Plugin_Button} plugin. The passed args contains different properties
     * depending on the command source, but you should at least find a <var>$args['commandName']</var>
     * value indicating the name of the command. The command name is normally specified by the plugin
     * that initiated the command.
     *
     * @param Zikula_Form_View $view The form view instance.
     * @param array            $args Additional arguments.
     *
     * @see Zikula_Form_Plugin_Button
     * @see Zikula_Form_Plugin_ImageButton
     *
     * @return mixed Redirect or false on errors.
     */
    public function handleCommand(Zikula_Form_View $view, &$args)
    {
        $serviceManager = ServiceUtil::getManager();
        $flashBag = $serviceManager->get('request')->getSession()->getFlashBag();

        if ($args['commandName'] == 'save') {
            // check if all fields are valid
            if (!$this->view->isValid()) {
                return false;
            }

            // retrieve form data
            $data = $this->view->getValues();

            // update all module vars
            try {
                $serviceManager->get('zikula_extensions_module.api.variable')->setAll('MUEternizerModule', $data['config']);
            } catch (\Exception $e) {
                $msg = $this->__('Error! Failed to set configuration variables.');
                if (System::isDevelopmentMode()) {
                    $msg .= ' ' . $e->getMessage();
                }
                $flashBag->add('error', $msg);
                return false;
            }

            $flashBag->add('status', $this->__('Done! Module configuration updated.'));

            $logger = $serviceManager->get('logger');
            $logger->notice('{app}: User {user} updated the configuration.', array('app' => 'MUEternizerModule', 'user' => UserUtil::getVar('uname')));
        } else if ($args['commandName'] == 'cancel') {
            // nothing to do there
        }

        // redirect back to the config page
        $url = $serviceManager->get('router')->generate('mueternizermodule_admin_config');

        return $this->view->redirect($url);
    }
}
