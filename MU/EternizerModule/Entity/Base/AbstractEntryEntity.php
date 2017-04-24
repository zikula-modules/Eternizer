<?php
/**
 * Eternizer.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link http://homepages-mit-zikula.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (http://modulestudio.de).
 */

namespace MU\EternizerModule\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Zikula\Core\Doctrine\EntityAccess;
use MU\EternizerModule\Traits\EntityWorkflowTrait;
use MU\EternizerModule\Traits\StandardFieldsTrait;
use MU\EternizerModule\Validator\Constraints as EternizerAssert;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the base entity class for entry entities.
 * The following annotation marks it as a mapped superclass so subclasses
 * inherit orm properties.
 *
 * @ORM\MappedSuperclass
 *
 * @abstract
 */
abstract class AbstractEntryEntity extends EntityAccess
{
    /**
     * Hook entity workflow field and behaviour.
     */
    use EntityWorkflowTrait;

    /**
     * Hook standard fields behaviour embedding createdBy, updatedBy, createdDate, updatedDate fields.
     */
    use StandardFieldsTrait;

    /**
     * @var string The tablename this object maps to
     */
    protected $_objectType = 'entry';
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", unique=true)
     * @Assert\Type(type="integer")
     * @Assert\NotNull()
     * @Assert\LessThan(value=1000000000)
     * @var integer $id
     */
    protected $id = 0;
    
    /**
     * the current workflow state
     * @ORM\Column(length=20)
     * @Assert\NotBlank()
     * @EternizerAssert\ListEntry(entityName="entry", propertyName="workflowState", multiple=false)
     * @var string $workflowState
     */
    protected $workflowState = 'initial';
    
    /**
     * @ORM\Column(length=15, nullable=true)
     * @Assert\Length(min="0", max="15")
     * @var string $ip
     */
    protected $ip = '';
    
    /**
     * @ORM\Column(length=100)
     * @Assert\NotNull()
     * @Assert\Length(min="0", max="100")
     * @var string $name
     */
    protected $name = '';
    
    /**
     * @ORM\Column(length=100)
     * @Assert\NotNull()
     * @Assert\Length(min="0", max="100")
     * @var string $email
     */
    protected $email = '';
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotNull()
     * @Assert\Length(min="0", max="255")
     * @Assert\Url(checkDNS=false)
     * @var string $homepage
     */
    protected $homepage = '';
    
    /**
     * @ORM\Column(length=100)
     * @Assert\NotNull()
     * @Assert\Length(min="0", max="100")
     * @var string $location
     */
    protected $location = '';
    
    /**
     * @ORM\Column(type="text", length=2000)
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="2000")
     * @var text $text
     */
    protected $text = '';
    
    /**
     * @ORM\Column(type="text", length=2000, nullable=true)
     * @Assert\Length(min="0", max="2000")
     * @var text $notes
     */
    protected $notes = '';
    
    
    
    /**
     * EntryEntity constructor.
     *
     * Will not be called by Doctrine and can therefore be used
     * for own implementation purposes. It is also possible to add
     * arbitrary arguments as with every other class method.
     */
    public function __construct()
    {
        $this->initWorkflow();
    }
    
    /**
     * Returns the _object type.
     *
     * @return string
     */
    public function get_objectType()
    {
        return $this->_objectType;
    }
    
    /**
     * Sets the _object type.
     *
     * @param string $_objectType
     *
     * @return void
     */
    public function set_objectType($_objectType)
    {
        if ($this->_objectType != $_objectType) {
            $this->_objectType = $_objectType;
        }
    }
    
    
    /**
     * Returns the id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Sets the id.
     *
     * @param integer $id
     *
     * @return void
     */
    public function setId($id)
    {
        if (intval($this->id) !== intval($id)) {
            $this->id = intval($id);
        }
    }
    
    /**
     * Returns the workflow state.
     *
     * @return string
     */
    public function getWorkflowState()
    {
        return $this->workflowState;
    }
    
    /**
     * Sets the workflow state.
     *
     * @param string $workflowState
     *
     * @return void
     */
    public function setWorkflowState($workflowState)
    {
        if ($this->workflowState !== $workflowState) {
            $this->workflowState = isset($workflowState) ? $workflowState : '';
        }
    }
    
    /**
     * Returns the ip.
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }
    
    /**
     * Sets the ip.
     *
     * @param string $ip
     *
     * @return void
     */
    public function setIp($ip)
    {
        if ($this->ip !== $ip) {
            $this->ip = $ip;
        }
    }
    
    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Sets the name.
     *
     * @param string $name
     *
     * @return void
     */
    public function setName($name)
    {
        if ($this->name !== $name) {
            $this->name = isset($name) ? $name : '';
        }
    }
    
    /**
     * Returns the email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * Sets the email.
     *
     * @param string $email
     *
     * @return void
     */
    public function setEmail($email)
    {
        if ($this->email !== $email) {
            $this->email = isset($email) ? $email : '';
        }
    }
    
    /**
     * Returns the homepage.
     *
     * @return string
     */
    public function getHomepage()
    {
        return $this->homepage;
    }
    
    /**
     * Sets the homepage.
     *
     * @param string $homepage
     *
     * @return void
     */
    public function setHomepage($homepage)
    {
        if ($this->homepage !== $homepage) {
            $this->homepage = isset($homepage) ? $homepage : '';
        }
    }
    
    /**
     * Returns the location.
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }
    
    /**
     * Sets the location.
     *
     * @param string $location
     *
     * @return void
     */
    public function setLocation($location)
    {
        if ($this->location !== $location) {
            $this->location = isset($location) ? $location : '';
        }
    }
    
    /**
     * Returns the text.
     *
     * @return text
     */
    public function getText()
    {
        return $this->text;
    }
    
    /**
     * Sets the text.
     *
     * @param text $text
     *
     * @return void
     */
    public function setText($text)
    {
        if ($this->text !== $text) {
            $this->text = isset($text) ? $text : '';
        }
    }
    
    /**
     * Returns the notes.
     *
     * @return text
     */
    public function getNotes()
    {
        return $this->notes;
    }
    
    /**
     * Sets the notes.
     *
     * @param text $notes
     *
     * @return void
     */
    public function setNotes($notes)
    {
        if ($this->notes !== $notes) {
            $this->notes = $notes;
        }
    }
    
    
    
    /**
     * Returns the formatted title conforming to the display pattern
     * specified for this entity.
     *
     * @return string The display title
     */
    public function getTitleFromDisplayPattern()
    {
        $formattedTitle = ''
                . $this->getName();
    
        return $formattedTitle;
    }
    
    /**
     * Return entity data in JSON format.
     *
     * @return string JSON-encoded data
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
    
    /**
     * Creates url arguments array for easy creation of display urls.
     *
     * @return array The resulting arguments list
     */
    public function createUrlArgs()
    {
        $args = [];
    
        $args['id'] = $this['id'];
    
        if (property_exists($this, 'slug')) {
            $args['slug'] = $this['slug'];
        }
    
        return $args;
    }
    
    /**
     * Create concatenated identifier string (for composite keys).
     *
     * @return String concatenated identifiers
     */
    public function createCompositeIdentifier()
    {
        $itemId = $this['id'];
    
        return $itemId;
    }
    
    /**
     * Determines whether this entity supports hook subscribers or not.
     *
     * @return boolean
     */
    public function supportsHookSubscribers()
    {
        return true;
    }
    
    /**
     * Return lower case name of multiple items needed for hook areas.
     *
     * @return string
     */
    public function getHookAreaPrefix()
    {
        return 'mueternizermodule.ui_hooks.entries';
    }
    
    /**
     * Returns an array of all related objects that need to be persisted after clone.
     * 
     * @param array $objects The objects are added to this array. Default: []
     * 
     * @return array of entity objects
     */
    public function getRelatedObjectsToPersist(&$objects = []) 
    {
        return [];
    }
    
    /**
     * ToString interceptor implementation.
     * This method is useful for debugging purposes.
     *
     * @return string The output string for this entity
     */
    public function __toString()
    {
        return 'Entry ' . $this->createCompositeIdentifier() . ': ' . $this->getTitleFromDisplayPattern();
    }
    
    /**
     * Clone interceptor implementation.
     * This method is for example called by the reuse functionality.
     * Performs a quite simple shallow copy.
     *
     * See also:
     * (1) http://docs.doctrine-project.org/en/latest/cookbook/implementing-wakeup-or-clone.html
     * (2) http://www.php.net/manual/en/language.oop5.cloning.php
     * (3) http://stackoverflow.com/questions/185934/how-do-i-create-a-copy-of-an-object-in-php
     */
    public function __clone()
    {
        // if the entity has no identity do nothing, do NOT throw an exception
        if (!($this->id)) {
            return;
        }
    
        // otherwise proceed
    
        // unset identifiers
        $this->setId(0);
    
        // reset workflow
        $this->resetWorkflow();
    
        $this->setCreatedBy(null);
        $this->setCreatedDate(null);
        $this->setUpdatedBy(null);
        $this->setUpdatedDate(null);
    
    }
}
