<?php
/**
 * Copyright Zikula Foundation 2009 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package Zikula_Form
 * @subpackage Template_Plugins
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * Smarty function to wrap Zikula_Form_View generated form controls with suitable form tags.
 *
 * @param array            $params  Parameters passed in the block tag.
 * @param string           $content Content of the block.
 * @param Zikula_Form_View $view    Reference to Zikula_Form_View object.
 *
 * @return string The rendered output.
 */

function smarty_block_mueternizermoduleform($params, $content, $view)
{
	if ($content) {
		PageUtil::AddVar('stylesheet', 'system/Zikula/Module/ThemeModule/Resources/public/css/form/style.css');
		$action = htmlspecialchars(System::getCurrentUri());
		$classString = '';
		$roleString = '';
		if (isset($params['cssClass'])) {
			$classString = "class=\"{$params['cssClass']}\" ";
		}
		if (isset($params['role'])) {
			$roleString = "role=\"{$params['role']}\" ";
		}

		$enctype = array_key_exists('enctype', $params) ? $params['enctype'] : null;
		// if enctype is not set directly, check whenever upload plugins were used;
		// if so - set proper enctype for file upload
		if (is_null($enctype)) {
			$uploadPlugins = array_filter($view->plugins, function($plugin) {
				return $plugin instanceof Zikula_Form_Plugin_UploadInput;
			});
			if (!empty($uploadPlugins)) {
				$enctype = 'multipart/form-data';
			}
		}
		$encodingHtml = !is_null($enctype) ? " enctype=\"{$enctype}\"" : '';

		$onSubmit = isset($params['onsubmit']) ? " onSubmit=\"{$params['onsubmit']}\"" : '';

        // we check if the entrypoint is part of the url
        $stripentrypoint = ModUtil::getVar('ZConfig', 'shorturlsstripentrypoint');
        
        // get url name
        $tables = DBUtil::getTables();
        $modcolumn = $tables['modules_column'];
        $module = 'MUEternizerModule';
        $where = "$modcolumn[name] = '" . DataUtil::formatForStore($module) . "'";
        $module = DBUtil::selectObject('modules', $where);
        $urlname = $module['url'];
        
		if (ModUtil::getVar('ZConfig', 'shorturls') == 0) {
			if (strpos($action, "func=view")!== false ) {
				$action = 'index.php?module=' . $urlname . '&amp;type=entry&amp;func=edit';
			}
		} else {
			if (strpos($action, "entries/view")!== false ) {
				if ($stripentrypoint == 1) {
					$action = $urlname . '/entry/edit?lct=user';
				}
				elseif ($stripentrypoint == 0) {
					$action = 'index.php/' . $urlname  . '/entry/edit?lct=user';
				}
			}
		}

		$view->postRender();

		$formId = $view->getFormId();
		$out = "
<form id=\"{$formId}\" {$roleString}{$classString}action=\"$action\" method=\"post\"{$encodingHtml}{$onSubmit}>
$content
    <div>
    {$view->getStateHTML()}
    {$view->getStateDataHTML()}
    {$view->getIncludesHTML()}
    {$view->getCsrfTokenHtml()}
        <input type=\"hidden\" name=\"__formid\" id=\"form__id\" value=\"{$formId}\" />
        <input type=\"hidden\" name=\"FormEventTarget\" id=\"FormEventTarget\" value=\"\" />
        <input type=\"hidden\" name=\"FormEventArgument\" id=\"FormEventArgument\" value=\"\" />
        <script type=\"text/javascript\">
        <!--
            function FormDoPostBack(eventTarget, eventArgument)
            {
                var f = document.getElementById('{$formId}');
                if (!f.onsubmit || f.onsubmit()) {
                    f.FormEventTarget.value = eventTarget;
                    f.FormEventArgument.value = eventArgument;
                    f.submit();
                }
            }
        // -->
        </script>
    </div>
</form>
";

    return $out;
	}
}
