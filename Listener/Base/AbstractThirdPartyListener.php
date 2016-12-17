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
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Zikula\Core\Event\GenericEvent;

/**
 * Event handler implementation class for special purposes and 3rd party api support.
 */
abstract class AbstractThirdPartyListener implements EventSubscriberInterface
{
    /**
     * Makes our handlers known to the event system.
     */
    public static function getSubscribedEvents()
    {
        return [
            'module.content.gettypes'               => ['contentGetTypes', 5],
            'module.scribite.editorhelpers'         => ['getEditorHelpers', 5],
            'moduleplugin.tinymce.externalplugins'  => ['getTinyMcePlugins', 5],
            'moduleplugin.ckeditor.externalplugins' => ['getCKEditorPlugins', 5]
        ];
    }
    
    
    /**
     * Listener for the `module.content.gettypes` event.
     *
     * This event occurs when the Content module is 'searching' for Content plugins.
     * The subject is an instance of Content_Types.
     * You can register custom content types as well as custom layout types.
     *
     * @param \Zikula_Event $event The event instance
     */
    public function contentGetTypes(\Zikula_Event $event)
    {
        // intended is using the add() method to add a plugin like below
        $types = $event->getSubject();
        
        
        // plugin for showing a single item
        $types->add('MUEternizerModule_ContentType_Item');
        
        // plugin for showing a list of multiple items
        $types->add('MUEternizerModule_ContentType_ItemList');
    }
    
    /**
     * Listener for the `module.scribite.editorhelpers` event.
     *
     * This occurs when Scribite adds pagevars to the editor page.
     * MUEternizerModule will use this to add a javascript helper to add custom items.
     *
     * @param \Zikula_Event $event The event instance
     */
    public function getEditorHelpers(\Zikula_Event $event)
    {
        // intended is using the add() method to add a helper like below
        $helpers = $event->getSubject();
        
        $helpers->add(
            [
                'module' => 'MUEternizerModule',
                'type'   => 'javascript',
                'path'   => 'modules/MUEternizerModule/Resources/public/js/MUEternizerModule.Finder.js'
            ]
        );
    }
    
    /**
     * Listener for the `moduleplugin.tinymce.externalplugins` event.
     *
     * Adds external plugin to TinyMCE.
     *
     * @param GenericEvent $event The event instance
     */
    public function getTinyMcePlugins(GenericEvent $event)
    {
        // intended is using the add() method to add a plugin like below
        $plugins = $event->getSubject();
        
        $plugins->add(
            [
                'name' => 'mueternizermodule',
                'path' => 'modules/MUEternizerModule/Resources/docs/scribite/plugins/TinyMce/plugins/mueternizermodule/editor_plugin.js'
            ]
        );
    }
    
    /**
     * Listener for the `moduleplugin.ckeditor.externalplugins` event.
     *
     * Adds external plugin to CKEditor.
     *
     * @param GenericEvent $event The event instance
     */
    public function getCKEditorPlugins(GenericEvent $event)
    {
        // intended is using the add() method to add a plugin like below
        $plugins = $event->getSubject();
        
        $plugins->add(
            [
                'name' => 'mueternizermodule',
                'path' => 'modules/MUEternizerModule/Resources/docs/scribite/plugins/CKEditor/vendor/ckeditor/plugins/mueternizermodule/',
                'file' => 'plugin.js',
                'img'  => 'ed_mueternizermodule.gif'
            ]
        );
    }
}
