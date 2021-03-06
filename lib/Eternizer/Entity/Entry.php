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

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use DoctrineExtensions\StandardFields\Mapping\Annotation as ZK;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the concrete entity class for entry entities.
 * @ORM\Entity(repositoryClass="Eternizer_Entity_Repository_Entry")
 * @ORM\Table(name="eternizer_entry")
 * @ORM\HasLifecycleCallbacks
 */
class Eternizer_Entity_Entry extends Eternizer_Entity_Base_Entry
{

    /**
     * Collect available actions for this entity.
     */
    protected function prepareItemActions()
    {
        if (!empty($this->_actions)) {
            return;
        }

        $currentType = FormUtil::getPassedValue('type', 'user', 'GETPOST', FILTER_SANITIZE_STRING);
        $currentFunc = FormUtil::getPassedValue('func', 'main', 'GETPOST', FILTER_SANITIZE_STRING);
        $dom = ZLanguage::getModuleDomain('Eternizer');
        if ($currentType == 'admin') {

            if (in_array($currentFunc, array('main', 'view', 'display'))) {
                if (SecurityUtil::checkPermission('Eternizer::', '.*', ACCESS_EDIT)) {

                    $this->_actions[] = array(
                        'url' => array('type' => 'admin', 'func' => 'edit', 'arguments' => array('ot' => 'entry', 'id' => $this['id'])),
                        'icon' => 'edit',
                        'linkTitle' => __('Edit', $dom),
                        'linkText' => __('Edit', $dom)
                    );
                }
                if (SecurityUtil::checkPermission('Eternizer::', '.*', ACCESS_DELETE)) {
                    $this->_actions[] = array(
                        'url' => array('type' => 'admin', 'func' => 'delete', 'arguments' => array('ot' => 'entry', 'id' => $this['id'])),
                        'icon' => 'delete',
                        'linkTitle' => __('Delete', $dom),
                        'linkText' => __('Delete', $dom)
                    );
                }
            }
        }
        if ($currentType == 'user') {

            if (in_array($currentFunc, array('main', 'view', 'display'))) {
                if (SecurityUtil::checkPermission('Eternizer::', '.*', ACCESS_EDIT)) {

                    $this->_actions[] = array(
                        'url' => array('type' => 'user', 'func' => 'edit', 'arguments' => array('ot' => 'entry', 'id' => $this['id'])),
                        'icon' => 'edit',
                        'linkTitle' => __('Edit', $dom),
                        'linkText' => __('Edit', $dom)
                    );
                }
            }
        }
    }


    /**
     * Post-Process the data after the entity has been constructed by the entity manager.
     *
     * @ORM\PostLoad
     * @see Eternizer_Entity_Base_Entry::performPostLoadCallback()
     * @return void.
     */
    public function postLoadCallback()
    {
        $this->performPostLoadCallback();
    }

    /**
     * Pre-Process the data prior to an insert operation.
     *
     * @ORM\PrePersist
     * @see Eternizer_Entity_Base_Entry::performPrePersistCallback()
     * @return void.
     */
    public function prePersistCallback()
    {
        Eternizer_Util_Base_Settings::handleModvarsPreSave();
        $this->performPrePersistCallback();
    }

    /**
     * Post-Process the data after an insert operation.
     *
     * @ORM\PostPersist
     * @see Eternizer_Entity_Base_Entry::performPostPersistCallback()
     * @return void.
     */
    public function postPersistCallback()
    {
        $args['id'] = $this->getId();
        $args['text'] = $this->getText();

        Eternizer_Util_Base_Settings::handleModvarsPostPersist($args);
        $this->performPostPersistCallback();
    }

    /**
     * Pre-Process the data prior a delete operation.
     *
     * @ORM\PreRemove
     * @see Eternizer_Entity_Base_Entry::performPreRemoveCallback()
     * @return void.
     */
    public function preRemoveCallback()
    {
        $this->performPreRemoveCallback();
    }

    /**
     * Post-Process the data after a delete.
     *
     * @ORM\PostRemove
     * @see Eternizer_Entity_Base_Entry::performPostRemoveCallback()
     * @return void
     */
    public function postRemoveCallback()
    {
        $this->performPostRemoveCallback();
    }

    /**
     * Pre-Process the data prior to an update operation.
     *
     * @ORM\PreUpdate
     * @see Eternizer_Entity_Base_Entry::performPreUpdateCallback()
     * @return void.
     */
    public function preUpdateCallback()
    {
        $this->performPreUpdateCallback();
    }

    /**
     * Post-Process the data after an update operation.
     *
     * @ORM\PostUpdate
     * @see Eternizer_Entity_Base_Entry::performPostUpdateCallback()
     * @return void.
     */
    public function postUpdateCallback()
    {
        $this->performPostUpdateCallback();
    }

    /**
     * Pre-Process the data prior to a save operation.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @see Eternizer_Entity_Base_Entry::performPreSaveCallback()
     * @return void.
     */
    public function preSaveCallback()
    {
        $this->performPreSaveCallback();
    }

    /**
     * Post-Process the data after a save operation.
     *
     * @ORM\PostPersist
     * @ORM\PostUpdate
     * @see Eternizer_Entity_Base_Entry::performPostSaveCallback()
     * @return void.
     */
    public function postSaveCallback()
    {
        $this->performPostSaveCallback();
    }

}
