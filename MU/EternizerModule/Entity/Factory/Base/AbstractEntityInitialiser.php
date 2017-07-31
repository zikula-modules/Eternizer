<?php
/**
 * Eternizer.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link https://homepages-mit-zikula.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (https://modulestudio.de).
 */

namespace MU\EternizerModule\Entity\Factory\Base;

use MU\EternizerModule\Entity\EntryEntity;

/**
 * Entity initialiser class used to dynamically apply default values to newly created entities.
 */
abstract class AbstractEntityInitialiser
{
    /**
     * Initialises a given entry instance.
     *
     * @param EntryEntity $entity The newly created entity instance
     *
     * @return EntryEntity The updated entity instance
     */
    public function initEntry(EntryEntity $entity)
    {

        return $entity;
    }

}
