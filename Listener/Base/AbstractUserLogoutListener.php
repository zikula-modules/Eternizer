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

namespace MU\EternizerModule\Listener\Base;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zikula\Core\Event\GenericEvent;
use Zikula\UsersModule\AccessEvents;

/**
 * Event handler base class for user logout events.
 */
abstract class AbstractUserLogoutListener implements EventSubscriberInterface
{
    /**
     * Makes our handlers known to the event system.
     */
    public static function getSubscribedEvents()
    {
        return [
            AccessEvents::LOGOUT_SUCCESS => ['succeeded', 5]
        ];
    }
    
    /**
     * Listener for the `module.users.ui.logout.succeeded` event.
     *
     * Occurs right after a successful logout. All handlers are notified.
     * The event's subject contains the user's UserEntity.
     * Args contain array of `['authentication_method' => $authenticationMethod,
     *                         'uid'                   => $uid];`
     *
     * @param GenericEvent $event The event instance
     */
    public function succeeded(GenericEvent $event)
    {
    }
}
