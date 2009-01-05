<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2008, philipp
 * @link http://code.zikula.org/eternizer
 * @version $Id: $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula
 * @subpackage Eternizer
*/

$modversion['name']           = 'Eternizer';
$modversion['version']        = '1.0'; //not more than 10 chars
$modversion['description']    = _ETERNIZER_DESC;

$modversion['credits']        = 'pndocs/credits.txt';
$modversion['help']           = 'pndocs/install.txt';
$modversion['license']        = 'pndocs/license.txt';
$modversion['official']       = '1';
$modversion['author']         = 'Philipp Niethammer';
$modversion['contact']        = 'http://code.zikula.org/eternizer';

$modversion['admin']          = '1';
$modversion['securityschema'] = array('Eternizer::' => 'Entry ID::');

$modversion['dependencies']   = array(array('modname'    => 'spamFree',
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
?>
