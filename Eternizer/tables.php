<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2007, Philipp Niethammer
 * @link http://www.guite.de
 * @version $Id$
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @author    Philipp Niethammer <webmaster@nochwer.de>
 * @package Zikula
 * @subpackage Eternizer
 */

/**
 * Diese Funktion wird intern vom Core aufgerufen, wenn das Modul geladen wird.
 * Sie kann auch explizit geladen werden, mit der pnModDBInfoLoad() API Funktion.
 *
 * @return       array       Die Tabellen-Infos
 */
function Eternizer_pntables() {
    $pntable = array();

    $pntable['Eternizer_entry'] = DBUtil::getLimitedTablename('eternizer_entry');
    $pntable['Eternizer_entry_column'] = array('id'        => 'pn_id',
                                               'ip'        => 'pn_ip',
                                               'text'      => 'pn_text',
                                               'comment'   => 'pn_comment');

    $pntable['Eternizer_entry_column_def'] = array('id'      => 'I        NOTNULL AUTO PRIMARY',
                                                   'ip'      => 'C(15)    NULL',
                                                   'text'    => 'X        NOTNULL',
                                                   'comment' => 'X        NULL');

    $pntable['Eternizer_entry_db_extra_enable_attribution'] = true;
    $pntable['Eternizer_entry_db_extra_enable_logging'] = true;

    ObjectUtil::addStandardFieldsToTableDefinition($pntable['Eternizer_entry_column'], 'pn_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['Eternizer_entry_column_def']);

    return $pntable;
}
