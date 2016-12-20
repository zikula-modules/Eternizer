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

namespace MU\EternizerModule\Helper\Base;

use ModUtil;
use UserUtil;

use Swift_Message;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Zikula\Core\Doctrine\EntityAccess;
use Zikula\ExtensionsModule\Api\VariableApi;
use Zikula\MailerModule\Api\MailerApi;
use Zikula\UsersModule\Api\CurrentUserApi;
use MU\EternizerModule\Helper\WorkflowHelper;

/**
 * Notification helper base class.
 */
abstract class AbstractNotificationHelper
{
    use TranslatorTrait;
    
    /**
     * @var SessionInterface
     */
    protected $session;
    
    /**
     * @var RouterInterface
     */
    protected $router;
    
    /**
     * @var KernelInterface
     */
    protected $kernel;
    
    /**
     * @var Request
     */
    protected $request;
    
    /**
     * @var VariableApi
     */
    protected $variableApi;
    
    /**
     * @var CurrentUserApi
     */
    private $currentUserApi;
    
    /**
     * @var Twig_Environment
     */
    protected $templating;
    
    /**
     * @var MailerApi
     */
    protected $mailer;
    
    /**
     * @var WorkflowHelper
     */
    protected $workflowHelper;
    
    /**
     * List of notification recipients.
     *
     * @var array $recipients
     */
    private $recipients = [];
    
    /**
     * Which type of recipient is used ("creator", "moderator" or "superModerator").
     *
     * @var string recipientType
     */
    private $recipientType = '';
    
    /**
     * The entity which has been changed before.
     *
     * @var EntityAccess entity
     */
    private $entity = '';
    
    /**
     * Name of workflow action which is being performed.
     *
     * @var string action
     */
    private $action = '';
    
    /**
     * Constructor.
     * Initialises member vars.
     *
     * @param TranslatorInterface $translator     Translator service instance
     * @param SessionInterface    $session        Session service instance
     * @param Routerinterface     $router         Router service instance
     * @param KernelInterface     $kernel         Kernel service instance
     * @param RequestStack        $requestStack   RequestStack service instance
     * @param VariableApi         $variableApi    VariableApi service instance
     * @param CurrentUserApi      $currentUserApi CurrentUserApi service instance
     * @param Twig_Environment    $twig           Twig service instance
     * @param MailerApi           $mailerApi      MailerApi service instance
     * @param WorkflowHelper      $workflowHelper WorkflowHelper service instance
     */
    public function __construct(
        TranslatorInterface $translator,
        SessionInterface $session,
        RouterInterface $router,
        KernelInterface $kernel,
        RequestStack $requestStack,
        VariableApi $variableApi,
        CurrentUserApi $currentUserApi,
        Twig_Environment $twig,
        MailerApi $mailerApi,
        WorkflowHelper $workflowHelper)
    {
        $this->setTranslator($translator);
        $this->session = $session;
        $this->router = $router;
        $this->kernel = $kernel;
        $this->request = $requestStack->getMasterRequest();
        $this->variableApi = $variableApi;
        $this->currentUserApi = $currentUserApi;
        $this->templating = $twig;
        $this->mailerApi = $mailerApi;
        $this->workflowHelper = $workflowHelper;
    }
    
    /**
     * Sets the translator.
     *
     * @param TranslatorInterface $translator Translator service instance
     */
    public function setTranslator(/*TranslatorInterface */$translator)
    {
        $this->translator = $translator;
    }
    
    /**
     * Sends a mail to either an item's creator or a group of moderators.
     */
    public function process($args)
    {
        if (!isset($args['recipientType']) || !$args['recipientType']) {
            return false;
        }
    
        if (!isset($args['action']) || !$args['action']) {
            return false;
        }
    
        if (!isset($args['entity']) || !$args['entity']) {
            return false;
        }
    
        $this->recipientType = $args['recipientType'];
        $this->action = $args['action'];
        $this->entity = $args['entity'];
    
        $uid = $this->currentUserApi->get('uid');
    
        $this->collectRecipients();
    
        if (!count($this->recipients)) {
            return true;
        }
    
        if (null === $this->kernel->getModule('ZikulaMailerModule')) {
            $this->session->getFlashBag()->add('error', $this->__('Could not inform other persons about your amendments, because the Mailer module is not available - please contact an administrator about that!'));
    
            return false;
        }
    
        $result = $this->sendMails();
    
        $this->session->del($this->name . 'AdditionalNotificationRemarks');
    
        return $result;
    }
    
    /**
     * Collects the recipients.
     */
    protected function collectRecipients()
    {
        $this->recipients = [];
    
        if ($this->recipientType == 'moderator' || $this->recipientType == 'superModerator') {
            $objectType = $this->entity['_objectType'];
            $moderatorGroupId = $this->variableApi->get('MUEternizerModule', 'moderationGroupFor' . $objectType, 2);
            if ($this->recipientType == 'superModerator') {
                $moderatorGroupId = $this->variableApi->get('MUEternizerModule', 'superModerationGroupFor' . $objectType, 2);
            }
    
            $moderatorGroup = ModUtil::apiFunc('ZikulaGroupsModule', 'user', 'get', ['gid' => $moderatorGroupId]);
            foreach (array_keys($moderatorGroup['members']) as $uid) {
                $this->addRecipient($uid);
            }
        } elseif ($this->recipientType == 'creator' && isset($this->entity['createdUserId'])) {
            $creatorUid = $this->entity['createdUserId'];
    
            $this->addRecipient($creatorUid);
        }
    
        if (isset($args['debug']) && $args['debug']) {
            // add the admin, too
            $this->addRecipient(2);
        }
    }
    
    /**
     * Collects data for building the recipients array.
     *
     * @param $userId Id of treated user
     */
    protected function addRecipient($userId)
    {
        $userVars = UserUtil::getVars($userId);
    
        $recipient = [
            'name' => (isset($userVars['name']) && !empty($userVars['name']) ? $userVars['name'] : $userVars['uname']),
            'email' => $userVars['email']
        ];
        $this->recipients[] = $recipient;
    
        return $recipient;
    }
    
    /**
     * Performs the actual mailing.
     */
    protected function sendMails()
    {
        $objectType = $this->entity['_objectType'];
        $siteName = $this->variableApi->getSystemVar('sitename_' . $this->request->getLocale(), $this->variableApi->getSystemVar('sitename_en'));
        $adminMail = $this->variableApi->getSystemVar('adminmail');
    
        $templateType = $this->recipientType == 'creator' ? 'Creator' : 'Moderator';
        $template = 'Email/notify' . ucfirst($objectType) . $templateType .  '.html.twig';
    
        $mailData = $this->prepareEmailData();
        $subject = $this->getMailSubject();
    
        // send one mail per recipient
        $totalResult = true;
        foreach ($this->recipients as $recipient) {
            if (!isset($recipient['username']) || !$recipient['username']) {
                continue;
            }
            if (!isset($recipient['email']) || !$recipient['email']) {
                continue;
            }
    
            $templateParameters = [
                'recipient' => $recipient,
                'mailData' => $mailData
            ];
    
            $body = $this->templating->render('@MUEternizerModule/' . $template, $templateParameters);
            $altBody = '';
            $html = true;
    
            // create new message instance
            /** @var Swift_Message */
            $message = Swift_Message::newInstance();
    
            $message->setFrom([$adminMail => $siteName]);
            $message->setTo([$recipient['email'] => $recipient['name']]);
    
            $totalResult &= $this->mailerApi->sendMessage($message, $subject, $msgBody, $altBody, $html);
        }
    
        return $totalResult;
    }
    
    protected function getMailSubject()
    {
        $mailSubject = '';
        if ($this->recipientType == 'moderator' || $this->recipientType == 'superModerator') {
            if ($this->action == 'submit') {
                $mailSubject = $this->__('New content has been submitted');
            } else {
                $mailSubject = $this->__('Content has been updated');
            }
        } elseif ($this->recipientType == 'creator') {
            $mailSubject = $this->__('Your submission has been updated');
        }
    
        return $mailSubject;
    }
    
    protected function prepareEmailData()
    {
        $objectType = $this->entity['_objectType'];
        $state = $this->entity['workflowState'];
        $stateInfo = $this->workflowHelper->getStateInfo($state);
    
        $remarks = $this->session->get($this->name . 'AdditionalNotificationRemarks', '');
    
        $urlArgs = $this->entity->createUrlArgs();
        $displayUrl = '';
        $editUrl = '';
    
        if ($this->recipientType == 'moderator' || $this->recipientType == 'superModerator') {
            $routeArea = 'admin';
            $displayUrl = $this->router->generate('mueternizermodule_' . strtolower($objectType) . '_' . $routeArea . 'display', $urlArgs, true);
            $editUrl = $this->router->generate('mueternizermodule_' . strtolower($objectType) . '_' . $routeArea . 'edit', $urlArgs, true);
        } elseif ($this->recipientType == 'creator') {
            $displayUrl = $this->router->generate('mueternizermodule_' . strtolower($objectType) . '_display', $urlArgs, true);
            $editUrl = $this->router->generate('mueternizermodule_' . strtolower($objectType) . '_edit', $urlArgs, true);
        }
    
        $emailData = [
            'name' => $this->entity->getTitleFromDisplayPattern(),
            'newState' => $stateInfo['text'],
            'remarks' => $remarks,
            'displayUrl' => $displayUrl,
            'editUrl' => $editUrl
        ];
    
        return $emailData;
    }
}
