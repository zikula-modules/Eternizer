<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2008-2011, philipp
 * @link http://code.zikula.org/eternizer
 * @version $Id$
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula
 * @subpackage Eternizer
*/
class Eternizer_Version extends Zikula_AbstractVersion {

	public function getMetaData() {	
	
	$dom = ZLanguage::getModuleDomain('Eternizer');

	$meta['name']           = 'Eternizer';
	$meta['version']        = '1.1.1'; //not more than 10 chars
	$meta['description']    = $this->__('A modern guestbook for Zikula', $dom);
	$meta['displayname']    = $this->__('Eternizer', $dom);
	//! module url must be different from displayname, even if just in casing
	$meta['url']    = __('eternizer', $dom);

	$meta['credits']        = 'docs/credits.txt';
	$meta['help']           = 'docs/install.txt';
	$meta['license']        = 'docs/license.txt';
	$meta['official']       = '1';
	$meta['author']         = 'Philipp Niethammer';
	$meta['contact']        = 'http://code.zikula.org/eternizer';

	$meta['admin']          = '1';
	$meta['securityschema'] = array('Eternizer::' => 'Entry ID::');

	$meta['dependencies']   = array(array('modname'    => 'spamFree',
                                            'minversion' => '0.1',
                                            'maxversion' => '',
                                            'status'     => PNMODULE_DEPENDENCY_RECOMMENDED
                                            ),
                                      array('modname'    => 'akismet',
                                            'minversion' => '1.0',
                                            'maxversion' => '',
                                            'status'     => PNMODULE_DEPENDENCY_RECOMMENDED
                                            )
                                      );
    return $meta;
	}
}