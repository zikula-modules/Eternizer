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

define('_ETERNIZER_INIT', 'Installation von '._ETERNIZER);
define('_ETERNIZER_INIT_ACTIVATE', _ETERNIZER.' nach der Installation aktivieren');
define('_ETERNIZER_INIT_STEP2', 'Konfiguration');
define('_ETERNIZER_INIT_STEP3', 'Letzer Schritt');
define('_ETERNIZER_INIT_THANKS', 'Installation abgeschlossen. Danke, dass Sie '._ETERNIZER.' benutzen.');
define('_ETERNIZER_INIT_WELCOME', "Willkommen zur interaktiven Installation von "._ETERNIZER);