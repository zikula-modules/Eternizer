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

namespace MU\EternizerModule\Listener;

use MU\EternizerModule\Listener\Base\AbstractEntityLifecycleListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use ModUtil;
use UserUtil;
use ServiceUtil;

/**
 * Event subscriber implementation class for entity lifecycle events.
 */
class EntityLifecycleListener extends AbstractEntityLifecycleListener
{	
	/**
	 * The postPersist event occurs for an entity after the entity has been made persistent.
	 * It will be invoked after the database insert operations. Generated primary key values
	 * are available in the postPersist event.
	 *
	 * @param LifecycleEventArgs $args Event arguments
	 */
	public function postPersist(LifecycleEventArgs $args)
	{
		parent::postPersist($args);
		
		$entity = $args->getObject();
		$currentIp = $_SERVER["REMOTE_ADDR"];
		
		$saveIp = \ModUtil::getVar('MUEternizerModule', 'ipsave');
		$moderation = \ModUtil::getVar('MUEternizerModule', 'moderate');
		$userId = \UserUtil::getVar('uid');
		$groupIds = \UserUtil::getGroupsForUser($userId);
		$serviceManager = ServiceUtil::getManager();
		$notificationHelper = $serviceManager->get('mu_eternizer_module.notification_helper');
		
        if (method_exists($entity, 'get_objectType')) {
            if ($saveIp == true) {
		        $entity->setIp($currentIp);
            }
            switch ($moderation) {
        	case 'all':
        		if ($userId != 2) {
        		    $entity->setWorkflowState('waiting');
        		    $notificationHelper->moderationMailer($entity, $userId);
        		} else {
        			$entity->setWorkflowState('approved');
        			$notificationHelper->moderationMailer($entity, $userId);
        		}
        		break;
        	case 'guests':
        		if ($userId == 1) {
        			$entity->setWorkflowState('waiting');
        		}
        		break;
        	case 'nothing':
        		    $entity->setWorkflowState('approved');
        		break;
            }     		
            	
        }
        
        if (method_exists($entity, 'setState')) {         
            switch ($moderation) {
        	    case 'all':
        		    if ($userId != 2) {
        		        $entity->setState('waiting');
        		    } else {
        		    	$entity->setState('approved');
        		    }
        		    break;
        	    case 'guests':
        		    if ($userId == 1) {
        			    $entity->setState('waiting');
        		    }
        		    break;
        	    case 'nothing':
        		    $entity->setState('approved');
        		    break;
            }   
        }
	}
}
