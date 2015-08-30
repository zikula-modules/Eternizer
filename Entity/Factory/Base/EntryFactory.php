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

namespace MU\EternizerModule\Entity\Factory\Base;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Factory class used to retrieve entity and repository instances.
 *
 * This is the base factory class for entry entities.
 */
class EntryFactory
{
    /**
     * @var String Full qualified class name to be used for entries.
     */
    protected $className;

    /**
     * @var ObjectManager The object manager to be used for determining the repository.
     */
    protected $objectManager;

    /**
     * @var EntityRepository The currently used repository.
     */
    protected $repository;

    /**
     * Constructor.
     *
     * @param ObjectManager $om        The object manager to be used for determining the repository.
     * @param String        $className Full qualified class name to be used for entries.
     */
    public function __construct(ObjectManager $om, $className)
    {
        $this->className = $className;
        $this->om = $om;
        $this->repository = $this->om->getRepository($className);
    }

    public function createEntry()
    {
        $entityClass = $this->className;

        return new $entityClass();
    }

    /**
     * Get class name.
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }
    
    /**
     * Set class name.
     *
     * @param string $className.
     *
     * @return void
     */
    public function setClassName($className)
    {
        $this->className = $className;
    }
    
    /**
     * Get object manager.
     *
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }
    
    /**
     * Set object manager.
     *
     * @param ObjectManager $objectManager.
     *
     * @return void
     */
    public function setObjectManager($objectManager)
    {
        $this->objectManager = $objectManager;
    }
    
    /**
     * Get repository.
     *
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }
    
    /**
     * Set repository.
     *
     * @param EntityRepository $repository.
     *
     * @return void
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;
    }
    
}
