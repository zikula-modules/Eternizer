<?php
/**
 * $Id: admin.php,v 0.1.1 02/23/05 Philipp Niethammer
 *
 * * Eternizer *
 *
 * Attach comments to any module calling hooks
 *
 *
 * * License *
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License (GPL)
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 *
 * @author      Philipp Niethammer <webmaster@nochwer.de>
 * @version     0.1
 * @link        htto://www.guite.de
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @package     Zikula
 * @subpackage  Eternizer
 */

pnModLangLoad('Eternizer', 'global');
pnModLangLoad('Eternizer', 'admin');

define('_ETERNIZER_INIT', 'Installation of '._ETERNIZER);
define('_ETERNIZER_INIT_ACTIVATE', 'Activate '._ETERNIZER.' after installation');
define('_ETERNIZER_INIT_STEP2', 'Configuration');
define('_ETERNIZER_INIT_STEP3', 'Last step');
define('_ETERNIZER_INIT_THANKS', 'All configuration is done. Thanks for using '._ETERNIZER);
define('_ETERNIZER_INIT_WELCOME', "Welcome to the interactive installation of "._ETERNIZER);