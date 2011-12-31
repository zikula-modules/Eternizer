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
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Sat Dec 31 13:59:03 CET 2011.
 */


/**
 * Validator class for encapsulating entity validation methods.
 *
 * This is the base validation class for entry entities.
 */
class Eternizer_Entity_Validator_Base_Entry extends Eternizer_Validator
{


    /**
     * Performs all validation rules.
     *
     * @return mixed either array with error information or true on success
     */
    public function validateAll()
    {
        $errorInfo = array('message' => '', 'code' => 0, 'debugArray' => array());
        $dom = ZLanguage::getModuleDomain('Eternizer');
        if (!$this->isValidInteger('id')) {
            $errorInfo['message'] = __f('Error! Field value may only contain digits (%s).', array('id'), $dom);
            return $errorInfo;
        }
        if (!$this->isNumberNotLongerThan('id', 9)) {
            $errorInfo['message'] = __f('Error! Length of field value must not be higher than %2$s (%1$s).', array('id', 9), $dom);
            return $errorInfo;
        }
        if (!$this->isStringNotLongerThan('ip', 15)) {
            $errorInfo['message'] = __f('Error! Length of field value must not be higher than %2$s (%1$s).', array('ip', 15), $dom);
            return $errorInfo;
        }
        if (!$this->isStringNotLongerThan('text', 2000)) {
            $errorInfo['message'] = __f('Error! Length of field value must not be higher than %2$s (%1$s).', array('text', 2000), $dom);
            return $errorInfo;
        }
        if (!$this->isStringNotEmpty('text')) {
            $errorInfo['message'] = __f('Error! Field value must not be empty (%s).', array('text'), $dom);
            return $errorInfo;
        }
        if (!$this->isStringNotLongerThan('notes', 2000)) {
            $errorInfo['message'] = __f('Error! Length of field value must not be higher than %2$s (%1$s).', array('notes', 2000), $dom);
            return $errorInfo;
        }
        if (!$this->isValidInteger('state')) {
            $errorInfo['message'] = __f('Error! Field value may only contain digits (%s).', array('state'), $dom);
            return $errorInfo;
        }
        if (!$this->isNumberNotEmpty('state')) {
            $errorInfo['message'] = __f('Error! Field value must not be 0 (%s).', array('state'), $dom);
            return $errorInfo;
        }
        if (!$this->isNumberNotLongerThan('state', 11)) {
            $errorInfo['message'] = __f('Error! Length of field value must not be higher than %2$s (%1$s).', array('state', 11), $dom);
            return $errorInfo;
        }
        return true;
    }


    /**
     * Check for unique values.
     *
     * This method determines if there already exist entries with the same entry.
     *
     * @param string $fieldName The name of the property to be checked
     * @return boolean result of this check, true if the given entry does not already exist
     */
    public function isUniqueValue($fieldName)
    {
        if (empty($this->entity[$fieldName])) {
            return false;
        }

        $serviceManager = ServiceUtil::getManager();
        $entityManager = $serviceManager->getService('doctrine.entitymanager');
        $repository = $entityManager->getRepository('Eternizer_Entity_Entry');

        $excludeid = $this->entity['id'];
        return $repository->detectUniqueState($fieldName, $this->entity[$fieldName], $excludeid);
    }

    /**
     * Get entity.
     *
     * @return Zikula_EntityAccess
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set entity.
     *
     * @param Zikula_EntityAccess $entity.
     *
     * @return void
     */
    public function setEntity(Zikula_EntityAccess $entity = null)
    {
        $this->entity = $entity;
    }


}
