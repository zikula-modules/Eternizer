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

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use ServiceUtil;

/**
 * Installer implementation class.
 */
class EternizerModuleInstaller extends AbstractEternizerModuleInstaller
{
	/**
	 * Install the MUEternizerModule application.
	 *
	 * @return boolean True on success, or false
	 *
	 * @throws RuntimeException Thrown if database tables can not be created or another error occurs
	 */
	public function install()
	{
		// try to create the cache directory
		$this->createCacheDirectory();
		
		return parent::install();
	}
	
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
        	case '1.0a':
        	case '1.0':
        	case '1.1':
        		$profile = ModUtil::getVar('Eternizer', 'profile');
        	
        		if (DataUtil::is_serialized($profile)) {
        			$profile = unserialize($profile);
        		}
        	
        		$profile = ModUtil::setVar('Eternizer', 'profile', $profile);
        		
        	case '1.1.1':
        	
        		// remove all module vars
        		$this->delVars();
        	
        		// create all tables from according entity definitions
        		try {
        			DoctrineHelper::createSchema($this->entityManager, $this->listEntityClasses());
        		} catch (Exception $e) {
        			if (System::isDevelopmentMode()) {
        				LogUtil::registerError($this->__('Doctrine Exception: ') . $e->getMessage());
        			}
        			return LogUtil::registerError($this->__f('An error was encountered while creating the tables for the %s module.', array($this->getName())));
        		}
        	
        		// set up all our vars with initial values
        		Eternizer_Util_Controller::setModVars();
        	
        		// create the default data for Eternizer
        		$this->createDefaultData();
        	
        		// register persistent event handlers
        		$this->registerPersistentEventHandlers();
        	
        		// register hook subscriber bundles
        		HookUtil::registerSubscriberBundles($this->version->getHookSubscriberBundles());
        	
        	case '1.1.2':
        		ModUtil::setVar('Eternizer', 'period', 12);
        		ModUtil::setVar('Eternizer', 'simplecaptcha', false);
        		$this->createTempDir();
        	
        	case '1.1.3':
        		// nothing to do
        		
            case '1.1.4':
                
            	// rename module for all modvars
            	$this->updateModVarsTo14();
            	
            	// handle renamed and deleted modvars
            	$pageSize = $this->getVar('pagesize');
            	if ($pageSize != '') {
            	    $this->setVar('entryEntriesPerPage', $pageSize);
            	} else {
            		$this->setVar('entryEntriesPerPage', 10);
            	}
            	$this->delVar('pagesize');
            	
            	$this->setVar('moderationGroupForEntries', 2);
            	$this->addFlash('status', __('The group of adminstrators will get emails now, if someone submit an entry. The setting variable for an email was deleted! You are able to change the group of moderators in the settings of this module'));           	
            	$this->delVar('mail');
            	
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
            	
            	// try to create the cache directory
            	$this->createCacheDirectory();

                // update the database schema
                try {
                    $this->schemaTool->update($this->listEntityClasses());
                } catch (\Exception $e) {
                    $this->addFlash('error', $this->__('Doctrine Exception') . ': ' . $e->getMessage());
                    $logger->error('{app}: Could not update the database tables during the upgrade. Error details: {errorMessage}.', ['app' => 'MUEternizerModule', 'errorMessage' => $e->getMessage()]);
    
                    return false;
                }
                                
                $entries = $this->entityManager->getRepository('MU\EternizerModule\Entity\EntryEntity')->findAll();
                $connection = $this->container->get('doctrine.entitymanager')->getConnection();
                
		        // we set the workflow
                foreach ($entries as $entry) {
                	$entity = $this->entityManager->getRepository('MU\EternizerModule\Entity\EntryEntity')->find($entry['id']);
                	$entity->setWorkflowState('approved');
                	$this->entityManager->flush();
                	$sql = "INSERT INTO workflows (metaid, module, schemaname, state, type, obj_table, obj_idcolumn, obj_id, busy, debug) VALUES (0, 'MUEternizerModule', 'standard', 'approved', 1, 'entry', 'id'," . $entity['id'] . ", 0, NULL)"; 
                	$smt = $connection->prepare($sql);
                	$smt->execute();

                }
                
            case '1.4.0':
            	// for later updates
        }
    
        // update successful
        return true;
    }
    
    /**
     * Returns path to cache directory.
     *
     * @return string Path to temporary cache directory
     */
    private function getCacheDirectory()
    {
    	return 'app/cache/eternizer';
    }
    /**
     * Creates the cache directory.
     *
     * @return void
     */
    private function createCacheDirectory()
    {
    	$cacheDirectory = $this->getCacheDirectory();
    	$fs = new Filesystem();
    	try {
    		if (!$fs->exists($cacheDirectory)) {
    			$fs->mkdir($cacheDirectory);
    			$fs->chmod($cacheDirectory, 0777);
    		}
    	} catch (IOExceptionInterface $e) {
    		$this->addFlash('error', $this->__f('An error occurred while creating the cache directory at %s.', ['%s' => $e->getPath()]));
    	}
    	try {
    		if ($fs->exists($cacheDirectory . '/.htaccess')) {
    			return;
    		}
    		$fs->dumpFile($cacheDirectory . '/.htaccess', 'SetEnvIf Request_URI "\.gif$" object_is_gif=gif
SetEnvIf Request_URI "\.png$" object_is_png=png
SetEnvIf Request_URI "\.jpg$" object_is_jpg=jpg
SetEnvIf Request_URI "\.jpeg$" object_is_jpeg=jpeg
Order deny,allow
Deny from all
Allow from env=object_is_gif
Allow from env=object_is_png
Allow from env=object_is_jpg
Allow from env=object_is_jpeg
');
    		$this->addFlash('status', $this->__('Successfully created the cache directory with a .htaccess file in it.'));
    	} catch (IOExceptionInterface $e) {
    		$this->addFlash('error', $this->__f('Could not create .htaccess file in %s, please refer to the manual before using the module!', ['%s' => $e->getPath()]));
    	}
    }
}
