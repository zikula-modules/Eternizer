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
 * Delete operation.
 *
 * @param object $entity The treated object
 * @param array  $params Additional arguments
 *
 * @return bool False on failure or true if everything worked well
 *
 * @throws RuntimeException Thrown if executing the workflow action fails
 */
function MUEternizerModule_operation_delete(&$entity, $params)
{

    // get entity manager
    $serviceManager = \ServiceUtil::getManager();
    $entityManager = $serviceManager->get('doctrine.orm.default_entity_manager');
    $logger = $serviceManager->get('logger');
    $logArgs = ['app' => 'MUEternizerModule', 'user' => $serviceManager->get('zikula_users_module.current_user')->get('uname')];
    
    // delete entity
    try {
        $entityManager->remove($entity);
        $entityManager->flush();
        $result = true;
        $logger->notice('{app}: User {user} deleted an entity.', $logArgs);
    } catch (\Exception $e) {
        $logger->error('{app}: User {user} tried to delete an entity, but failed.', $logArgs);
        throw new \RuntimeException($e->getMessage());
    }

    // return result of this operation
    return $result;
}
