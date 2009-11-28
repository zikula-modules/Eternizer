<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2007, Philipp Niethammer
 * @link http://www.guite.de
 * @version $Id$
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @author	Philipp Niethammer <webmaster@nochwer.de>
 * @package Zikula
 * @subpackage Eternizer
 */

function smarty_function_eternizerundo ($params, &$smarty) {
	if (!pnModAPILoad('ObjectData', 'log')) {
		if (isset($params['assign'])) {
			$smarty->assign($params['assign'], '');
			return;
		}
		return '';
	}

	$action = pnModAPIFunc('ObjectData', 'log', 'getLastAction', array('module' => 'Eternizer',
																		'uid'	=> pnUserGetVar('uid')));
	$out = '';
	if (is_array($action)) {
		$item = reset($action);
		$url = $_SERVER['REQUEST_URI'];
		$out = pnModURL('ObjectData', 'log', 'undo', array('logmodule'	=> 'Eternizer',
															'logaction'=> $item['action'],
															'redirect' => DataUtil::encode($url)));
	}

	if (isset($params['assign'])) {
		$smarty->assign($params['assign'], $out);
		return;
	}
	return $out;
}

