<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2008, philipp
 * @link http://code.zikula.org/eternizer
 * @version $Id$
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula
 * @subpackage Eternizer
 */

class Eternizer_Block_List extends Zikula_Controller_AbstractBlock {
	
/**
 * initialise block
 *
 */
public function init() {
    // Security
    SecurityUtil::registerPermissionSchema('Eternizer:Listblock:', 'Block title::');
}

/**
 * get information on block
 *
 * @return       array       The block information
 */
public function info()
{
    $dom = ZLanguage::getModuleDomain('Eternizer');
    return array('text_type'      => 'List',
                 'module'         => 'Eternizer',
                 'text_type_long' => __('Show Eternizer items', $dom),
                 'allow_multiple' => true,
                 'form_content'   => false,
                 'form_refresh'   => false,
                 'show_preview'   => true);
}

/**
 * display block
 *
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output      the rendered bock
 */
public function display($blockinfo)
{
    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing.
    // Note that we have Eternizer:Listblock: as the component.
    if (!SecurityUtil::checkPermission('Eternizer:Listblock:', $blockinfo['title'] . '::', ACCESS_READ)) {
        return;
    }

    // Get variables from content block
    $vars = pnBlockVarsFromContent($blockinfo['content']);

    $numitems = is_numeric($vars['numitems'])?$vars['numitems']:pnModGetVar('Eternizer', 'itemsperpage');
    $tpl = $vars['tpl']?$vars['tpl']:'listblock';

    // Check if the Eternizer module is available.
    if (!pnModAvailable('Eternizer')) {
        return false;
    }

    // Populate block info and pass to theme
    $blockinfo['content'] = pnModFunc('Eternizer', 'user', 'main', array('tpl' => $tpl, 'perpage' => $numitems));

    return themesideblock($blockinfo);
}


/**
 * modify block settings
 *
 * @author       The Zikula Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output      the bock form
 */
public function modify($blockinfo)
{
    $render = new pnRender('Eternizer');

    $vars = pnBlockVarsFromContent($blockinfo['content']);

    $render->assign('numitems', $vars['numitems']);
    $render->assign('tpl', $vars['tpl']);

    return $render->fetch('Eternizer_listblock_modify.tpl');
}


/**
 * update block settings
 *
 * @author       The Zikula Development Team
 * @param        array       $blockinfo     a blockinfo structure
 * @return       $blockinfo  the modified blockinfo structure
 */
public function update($blockinfo)
{
    // Get current content
    $vars = pnBlockVarsFromContent($blockinfo['content']);

    // alter the corresponding variable
    $vars['numitems'] = (int) FormUtil::GetPassedValue('numitems', 5);
    $vars['tpl']      = FormUtil::GetPassedValue('tpl', 'listblock');

    // write back the new contents
    $blockinfo['content'] = pnBlockVarsToContent($vars);

    return $blockinfo;
}
}
