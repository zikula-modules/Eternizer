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

namespace MU\EternizerModule\Controller;

use MU\EternizerModule\Controller\Base\EntryController as BaseEntryController;

use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use MU\EternizerModule\Entity\EntryEntity;

use ModUtil;
use UserUtil;

/**
 * Entry controller class providing navigation and interaction functionality.
 */
class EntryController extends BaseEntryController
{
    /**
     * This method is the default function handling the main area called without defining arguments.
     *
     * @Route("/entries",
     *        methods = {"GET"}
     * )
     *
     * @param Request  $request      Current request instance
     *
     * @return mixed Output.
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions.
     */
    public function indexAction(Request $request)
    {
        return parent::indexAction($request);
    }
    
    /**
     * This method provides a item list overview.
     *
     * @Route("/entries/view/{sort}/{sortdir}/{pos}/{num}.{_format}",
     *        requirements = {"sortdir" = "asc|desc|ASC|DESC", "pos" = "\d+", "num" = "\d+", "_format" = "html|csv|rss|atom|xml|json|kml"},
     *        defaults = {"sort" = "", "sortdir" = "asc", "pos" = 1, "num" = 0, "_format" = "html"},
     *        methods = {"GET"}
     * )
     *
     * @param Request  $request      Current request instance
     * @param string  $sort         Sorting field.
     * @param string  $sortdir      Sorting direction.
     * @param int     $pos          Current pager position.
     * @param int     $num          Amount of entries to display.
     * @param string  $tpl          Name of alternative template (to be used instead of the default template).
     *
     * @return mixed Output.
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions.
     */
    public function viewAction(Request $request, $sort, $sortdir, $pos, $num)
    {
    	// We rule the position of the form
        $formposition = ModUtil::getVar($this->name, 'formposition');

        //We check the userid for ruling the edit button
        $userid = UserUtil::getVar('uid');

        //We check for editing of entries
        $editentries = ModUtil::getVar($this->name, 'editentries');

        // We assign to the template
        $this->view->assign('formposition', $formposition);
        $this->view->assign('userid', $userid);
        $this->view->assign('editentries', $editentries);

        $order = ModUtil::getVar($this->name, 'order');
        if ($order == 'descending') {
            $args['sortdir'] = 'desc';
        }
        else {
            $args['sortdir'] = 'asc';
        }

        return parent::viewAction($request, $sort, $sortdir, $pos, $num);
    }
    
    /**
     * This method provides a item detail view.
     *
     * @Route("/entry/{id}.{_format}",
     *        requirements = {"id" = "\d+", "_format" = "html|xml|json|kml|ics"},
     *        defaults = {"_format" = "html"},
     *        methods = {"GET"}
     * )
     *
     * @param Request  $request      Current request instance
     * @param EntryEntity $entry      Treated entry instance.
     * @param string  $tpl          Name of alternative template (to be used instead of the default template).
     *
     * @return mixed Output.
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions.
     * @throws NotFoundHttpException Thrown by param converter if item to be displayed isn't found.
     */
    public function displayAction(Request $request, EntryEntity $entry)
    {
        return parent::displayAction($request, $entry);
    }
    
    /**
     * This method provides a handling of edit requests.
     *
     * @Route("/entry/edit/{id}.{_format}",
     *        requirements = {"id" = "\d+", "_format" = "html"},
     *        defaults = {"id" = "0", "_format" = "html"},
     *        methods = {"GET", "POST"}
     * )
     *
     * @param Request  $request      Current request instance
     * @param string  $tpl          Name of alternative template (to be used instead of the default template).
     *
     * @return mixed Output.
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions.
     * @throws NotFoundHttpException Thrown by form handler if item to be edited isn't found.
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available).
     */
    public function editAction(Request $request)
    {
        return parent::editAction($request);
    }
    
    /**
     * This method provides a handling of simple delete requests.
     *
     * @Route("/entry/delete/{id}.{_format}",
     *        requirements = {"id" = "\d+", "_format" = "html"},
     *        defaults = {"_format" = "html"},
     *        methods = {"GET", "POST"}
     * )
     *
     * @param Request  $request      Current request instance
     * @param EntryEntity $entry      Treated entry instance.
     * @param boolean $confirmation Confirm the deletion, else a confirmation page is displayed.
     * @param string  $tpl          Name of alternative template (to be used instead of the default template).
     *
     * @return mixed Output.
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions.
     * @throws NotFoundHttpException Thrown by param converter if item to be deleted isn't found.
     * @throws RuntimeException      Thrown if another critical error occurs (e.g. workflow actions not available).
     */
    public function deleteAction(Request $request, EntryEntity $entry)
    {
        return parent::deleteAction($request, $entry);
    }
    

    /**
     * Process status changes for multiple items.
     *
     * This function processes the items selected in the admin view page.
     * Multiple items may have their state changed or be deleted.
     *
     * @Route("/entries/handleSelectedEntries",
     *        methods = {"POST"}
     * )
     *
     * @param string $action The action to be executed.
     * @param array  $items  Identifier list of the items to be processed.
     *
     * @return bool true on sucess, false on failure.
     *
     * @throws RuntimeException Thrown if executing the workflow action fails
     */
    public function handleSelectedEntriesAction(Request $request)
    {
        return parent::handleSelectedEntriesAction($request);
    }

    /**
     * This method cares for a redirect within an inline frame.
     *
     * @Route("/entry/handleInlineRedirect/{idPrefix}/{commandName}/{id}",
     *        requirements = {"id" = "\d+"},
     *        defaults = {"commandName" = "", "id" = 0},
     *        methods = {"GET"}
     * )
     *
     * @param string  $idPrefix    Prefix for inline window element identifier.
     * @param string  $commandName Name of action to be performed (create or edit).
     * @param integer $id          Id of created item (used for activating auto completion after closing the modal window).
     *
     * @return boolean Whether the inline redirect has been performed or not.
     */
    public function handleInlineRedirectAction($idPrefix, $commandName, $id = 0)
    {
        return parent::handleInlineRedirectAction($idPrefix, $commandName, $id);
    }

    // feel free to add your own controller methods here
}
