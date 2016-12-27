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

namespace MU\EternizerModule\Controller;

use MU\EternizerModule\Controller\Base\AbstractConfigController;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Zikula\ThemeModule\Engine\Annotation\Theme;

/**
 * config controller class providing navigation and interaction functionality.
 */
class ConfigController extends AbstractConfigController
{

    /**
     * This method takes care of the application configuration.
     *
     * @Route("/config",
     *        methods = {"GET", "POST"}
     * )
     * @Theme("admin")
     *
     * @param Request $request Current request instance
     *
     * @return string Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function configAction(Request $request)
    {
    	$simpleCaptcha = \ModUtil::getVar($this->name, 'simplecaptcha');
    	if ($simpleCaptcha == true) {
    	    $environmentHelper = $this->get('mu_eternizer_module.environment_helper');
    	    $environment = $environmentHelper->check();
    	}
    	
        return parent::configAction($request);
    }
    // feel free to add your own controller methods here
}
