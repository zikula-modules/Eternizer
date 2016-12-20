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

namespace MU\EternizerModule;

use MU\EternizerModule\Base\AbstractEternizerModuleInstaller;

/**
 * Installer implementation class.
 */
class EternizerModuleInstaller extends AbstractEternizerModuleInstaller
{
   /**
     * Upgrade the MUEternizerModule application from an older version.
     *
     * If the upgrade fails at some point, it returns the last upgraded version.
     *
     * @param integer $oldVersion Version to upgrade from
     *
     * @return boolean True on success, false otherwise
     *
     * @throws RuntimeException Thrown if database tables can not be updated
     */
    public function upgrade($oldVersion)
    {
        $logger = $this->container->get('logger');
    
        // Upgrade dependent on old version number
        switch ($oldVersion) {
            case '1.1.4':
                
            	// rename module for all modvars
            	$this->updateModVarsTo14();
            	
            	// update extension information about this app
            	$this->updateExtensionInfoFor14();
            	
            	// rename existing permission rules
            	$this->renamePermissionsFor14();
            	
            	// rename all tables
            	$this->renameTablesFor14();
            	
            	// remove event handler definitions from database
            	$this->dropEventHandlersFromDatabase();
            	
            	// update module name in the hook tables
            	$this->updateHookNamesFor14();
            	
            	// update module name in the workflows table
            	$this->updateWorkflowsFor14();

                // ...
                // update the database schema
                try {
                    $this->schemaTool->update($this->listEntityClasses());
                } catch (\Exception $e) {
                    $this->addFlash('error', $this->__('Doctrine Exception') . ': ' . $e->getMessage());
                    $logger->error('{app}: Could not update the database tables during the upgrade. Error details: {errorMessage}.', ['app' => 'MUEternizerModule', 'errorMessage' => $e->getMessage()]);
    
                    return false;
                }
                
            case '1.4.0':
            	// for later updates
        }
    

        return true;
    }
}
