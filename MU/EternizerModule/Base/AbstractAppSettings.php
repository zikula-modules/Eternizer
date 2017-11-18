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

namespace MU\EternizerModule\Base;

use Symfony\Component\Validator\Constraints as Assert;
use Zikula\ExtensionsModule\Api\ApiInterface\VariableApiInterface;
use Zikula\GroupsModule\Constant as GroupsConstant;
use Zikula\GroupsModule\Entity\RepositoryInterface\GroupRepositoryInterface;
use MU\EternizerModule\Validator\Constraints as EternizerAssert;

/**
 * Application settings class for handling module variables.
 */
abstract class AbstractAppSettings
{
    /**
     * @var VariableApiInterface
     */
    protected $variableApi;
    
    /**
     * @var GroupRepositoryInterface
     */
    protected $groupRepository;
    
    /**
     * @Assert\NotBlank()
     * @EternizerAssert\ListEntry(entityName="appSettings", propertyName="orderOfEntries", multiple=false)
     * @var string $orderOfEntries
     */
    protected $orderOfEntries = '';
    
    /**
     * @Assert\NotBlank()
     * @EternizerAssert\ListEntry(entityName="appSettings", propertyName="moderate", multiple=false)
     * @var string $moderate
     */
    protected $moderate = '';
    
    /**
     * @Assert\NotBlank()
     * @EternizerAssert\ListEntry(entityName="appSettings", propertyName="formposition", multiple=false)
     * @var string $formposition
     */
    protected $formposition = '';
    
    /**
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     * @var boolean $ipsave
     */
    protected $ipsave = false;
    
    /**
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     * @var boolean $editentries
     */
    protected $editentries = false;
    
    /**
     * @Assert\Type(type="integer")
     * @Assert\NotNull()
     * @Assert\LessThan(value=100000000000)
     * @var integer $period
     */
    protected $period = 0;
    
    /**
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     * @var boolean $simplecaptcha
     */
    protected $simplecaptcha = false;
    
    /**
     * Used to determine moderator user accounts for sending email notifications.
     *
     * @Assert\NotBlank()
     * @var integer $moderationGroupForEntries
     */
    protected $moderationGroupForEntries = 2;
    
    /**
     * The amount of entries shown per page
     *
     * @Assert\Type(type="integer")
     * @Assert\NotBlank()
     * @Assert\NotEqualTo(value=0)
     * @Assert\LessThan(value=100000000000)
     * @var integer $entryEntriesPerPage
     */
    protected $entryEntriesPerPage = 10;
    
    /**
     * Whether to add a link to entries of the current user on his account page
     *
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     * @var boolean $linkOwnEntriesOnAccountPage
     */
    protected $linkOwnEntriesOnAccountPage = true;
    
    /**
     * Which sections are supported in the Finder component (used by Scribite plug-ins).
     *
     * @Assert\NotNull()
     * @EternizerAssert\ListEntry(entityName="appSettings", propertyName="enabledFinderTypes", multiple=true)
     * @var string $enabledFinderTypes
     */
    protected $enabledFinderTypes = 'entry';
    
    
    /**
     * AppSettings constructor.
     *
     * @param VariableApiInterface $variableApi VariableApi service instance
     * @param GroupRepositoryInterface $groupRepository GroupRepository service instance
     */
    public function __construct(
        VariableApiInterface $variableApi,
        GroupRepositoryInterface $groupRepository
    ) {
        $this->variableApi = $variableApi;
        $this->groupRepository = $groupRepository;
    
        $this->load();
    }
    
    /**
     * Returns the order of entries.
     *
     * @return string
     */
    public function getOrderOfEntries()
    {
        return $this->orderOfEntries;
    }
    
    /**
     * Sets the order of entries.
     *
     * @param string $orderOfEntries
     *
     * @return void
     */
    public function setOrderOfEntries($orderOfEntries)
    {
        if ($this->orderOfEntries !== $orderOfEntries) {
            $this->orderOfEntries = isset($orderOfEntries) ? $orderOfEntries : '';
        }
    }
    
    /**
     * Returns the moderate.
     *
     * @return string
     */
    public function getModerate()
    {
        return $this->moderate;
    }
    
    /**
     * Sets the moderate.
     *
     * @param string $moderate
     *
     * @return void
     */
    public function setModerate($moderate)
    {
        if ($this->moderate !== $moderate) {
            $this->moderate = isset($moderate) ? $moderate : '';
        }
    }
    
    /**
     * Returns the formposition.
     *
     * @return string
     */
    public function getFormposition()
    {
        return $this->formposition;
    }
    
    /**
     * Sets the formposition.
     *
     * @param string $formposition
     *
     * @return void
     */
    public function setFormposition($formposition)
    {
        if ($this->formposition !== $formposition) {
            $this->formposition = isset($formposition) ? $formposition : '';
        }
    }
    
    /**
     * Returns the ipsave.
     *
     * @return boolean
     */
    public function getIpsave()
    {
        return $this->ipsave;
    }
    
    /**
     * Sets the ipsave.
     *
     * @param boolean $ipsave
     *
     * @return void
     */
    public function setIpsave($ipsave)
    {
        if (boolval($this->ipsave) !== boolval($ipsave)) {
            $this->ipsave = boolval($ipsave);
        }
    }
    
    /**
     * Returns the editentries.
     *
     * @return boolean
     */
    public function getEditentries()
    {
        return $this->editentries;
    }
    
    /**
     * Sets the editentries.
     *
     * @param boolean $editentries
     *
     * @return void
     */
    public function setEditentries($editentries)
    {
        if (boolval($this->editentries) !== boolval($editentries)) {
            $this->editentries = boolval($editentries);
        }
    }
    
    /**
     * Returns the period.
     *
     * @return integer
     */
    public function getPeriod()
    {
        return $this->period;
    }
    
    /**
     * Sets the period.
     *
     * @param integer $period
     *
     * @return void
     */
    public function setPeriod($period)
    {
        if (intval($this->period) !== intval($period)) {
            $this->period = intval($period);
        }
    }
    
    /**
     * Returns the simplecaptcha.
     *
     * @return boolean
     */
    public function getSimplecaptcha()
    {
        return $this->simplecaptcha;
    }
    
    /**
     * Sets the simplecaptcha.
     *
     * @param boolean $simplecaptcha
     *
     * @return void
     */
    public function setSimplecaptcha($simplecaptcha)
    {
        if (boolval($this->simplecaptcha) !== boolval($simplecaptcha)) {
            $this->simplecaptcha = boolval($simplecaptcha);
        }
    }
    
    /**
     * Returns the moderation group for entries.
     *
     * @return integer
     */
    public function getModerationGroupForEntries()
    {
        return $this->moderationGroupForEntries;
    }
    
    /**
     * Sets the moderation group for entries.
     *
     * @param integer $moderationGroupForEntries
     *
     * @return void
     */
    public function setModerationGroupForEntries($moderationGroupForEntries)
    {
        if ($this->moderationGroupForEntries !== $moderationGroupForEntries) {
            $this->moderationGroupForEntries = $moderationGroupForEntries;
        }
    }
    
    /**
     * Returns the entry entries per page.
     *
     * @return integer
     */
    public function getEntryEntriesPerPage()
    {
        return $this->entryEntriesPerPage;
    }
    
    /**
     * Sets the entry entries per page.
     *
     * @param integer $entryEntriesPerPage
     *
     * @return void
     */
    public function setEntryEntriesPerPage($entryEntriesPerPage)
    {
        if (intval($this->entryEntriesPerPage) !== intval($entryEntriesPerPage)) {
            $this->entryEntriesPerPage = intval($entryEntriesPerPage);
        }
    }
    
    /**
     * Returns the link own entries on account page.
     *
     * @return boolean
     */
    public function getLinkOwnEntriesOnAccountPage()
    {
        return $this->linkOwnEntriesOnAccountPage;
    }
    
    /**
     * Sets the link own entries on account page.
     *
     * @param boolean $linkOwnEntriesOnAccountPage
     *
     * @return void
     */
    public function setLinkOwnEntriesOnAccountPage($linkOwnEntriesOnAccountPage)
    {
        if (boolval($this->linkOwnEntriesOnAccountPage) !== boolval($linkOwnEntriesOnAccountPage)) {
            $this->linkOwnEntriesOnAccountPage = boolval($linkOwnEntriesOnAccountPage);
        }
    }
    
    /**
     * Returns the enabled finder types.
     *
     * @return string
     */
    public function getEnabledFinderTypes()
    {
        return $this->enabledFinderTypes;
    }
    
    /**
     * Sets the enabled finder types.
     *
     * @param string $enabledFinderTypes
     *
     * @return void
     */
    public function setEnabledFinderTypes($enabledFinderTypes)
    {
        if ($this->enabledFinderTypes !== $enabledFinderTypes) {
            $this->enabledFinderTypes = isset($enabledFinderTypes) ? $enabledFinderTypes : '';
        }
    }
    
    
    /**
     * Loads module variables from the database.
     */
    protected function load()
    {
        $moduleVars = $this->variableApi->getAll('MUEternizerModule');
    
        if (isset($moduleVars['orderOfEntries'])) {
            $this->setOrderOfEntries($moduleVars['orderOfEntries']);
        }
        if (isset($moduleVars['moderate'])) {
            $this->setModerate($moduleVars['moderate']);
        }
        if (isset($moduleVars['formposition'])) {
            $this->setFormposition($moduleVars['formposition']);
        }
        if (isset($moduleVars['ipsave'])) {
            $this->setIpsave($moduleVars['ipsave']);
        }
        if (isset($moduleVars['editentries'])) {
            $this->setEditentries($moduleVars['editentries']);
        }
        if (isset($moduleVars['period'])) {
            $this->setPeriod($moduleVars['period']);
        }
        if (isset($moduleVars['simplecaptcha'])) {
            $this->setSimplecaptcha($moduleVars['simplecaptcha']);
        }
        if (isset($moduleVars['moderationGroupForEntries'])) {
            $this->setModerationGroupForEntries($moduleVars['moderationGroupForEntries']);
        }
        if (isset($moduleVars['entryEntriesPerPage'])) {
            $this->setEntryEntriesPerPage($moduleVars['entryEntriesPerPage']);
        }
        if (isset($moduleVars['linkOwnEntriesOnAccountPage'])) {
            $this->setLinkOwnEntriesOnAccountPage($moduleVars['linkOwnEntriesOnAccountPage']);
        }
        if (isset($moduleVars['enabledFinderTypes'])) {
            $this->setEnabledFinderTypes($moduleVars['enabledFinderTypes']);
        }
    
        // prepare group selectors, fallback to admin group for undefined values
        $adminGroupId = GroupsConstant::GROUP_ID_ADMIN;
        $groupId = $this->getModerationGroupForEntries();
        if ($groupId < 1) {
            $groupId = $adminGroupId;
        }
    
        $this->setModerationGroupForEntries($this->groupRepository->find($groupId));
    }
    
    /**
     * Saves module variables into the database.
     */
    public function save()
    {
        // normalise group selector values
        $group = $this->getModerationGroupForEntries();
        $group = is_object($group) ? $group->getGid() : intval($group);
        $this->setModerationGroupForEntries($group);
    
        $this->variableApi->set('MUEternizerModule', 'orderOfEntries', $this->getOrderOfEntries());
        $this->variableApi->set('MUEternizerModule', 'moderate', $this->getModerate());
        $this->variableApi->set('MUEternizerModule', 'formposition', $this->getFormposition());
        $this->variableApi->set('MUEternizerModule', 'ipsave', $this->getIpsave());
        $this->variableApi->set('MUEternizerModule', 'editentries', $this->getEditentries());
        $this->variableApi->set('MUEternizerModule', 'period', $this->getPeriod());
        $this->variableApi->set('MUEternizerModule', 'simplecaptcha', $this->getSimplecaptcha());
        $this->variableApi->set('MUEternizerModule', 'moderationGroupForEntries', $this->getModerationGroupForEntries());
        $this->variableApi->set('MUEternizerModule', 'entryEntriesPerPage', $this->getEntryEntriesPerPage());
        $this->variableApi->set('MUEternizerModule', 'linkOwnEntriesOnAccountPage', $this->getLinkOwnEntriesOnAccountPage());
        $this->variableApi->set('MUEternizerModule', 'enabledFinderTypes', $this->getEnabledFinderTypes());
    }
}
