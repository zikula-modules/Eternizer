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
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Wed Jan 04 16:43:44 CET 2012.
 */

/**
 * Event handler implementation class for page-related events.
 */
class Eternizer_Listener_Page
{
    /**
     * Listener for the `pageutil.addvar_filter` event.
     *
     * Used to override things like system or module stylesheets or javascript.
     * Subject is the `$varname`, and `$event->data` an array of values to be modified by the filter.
     *
     * This single filter can be used to override all css or js scripts or any other var types
     * sent to `PageUtil::addVar()`.
     */
    public static function pageutilAddvarFilter(Zikula_Event $event)
    {
        // Simply test with something like
        /*
               if (($key = array_search('system/Users/javascript/somescript.js', $event->data)) !== false) {
                   $event->data[$key] = 'config/javascript/myoverride.js';
               }
        */
    }

    /**
     * Listener for the `system.outputfilter` event.
     *
     * Filter type event for output filter HTML sanitisation.
     */
    public static function systemOutputFilter(Zikula_Event $event)
    {
    }
}
