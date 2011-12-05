<?php
/**
 * PostNuke Application Framework
 *
 * @copyright (c) 2008, philipp
 * @link http://www.postnuke.com
 * @version $Id$
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package PostNuke
 * @subpackage Eternizer
 */

/**
 * Search plugin info
 **/
function Eternizer_searchapi_info()
{
    return array('title' => 'Eternizer',
                'functions' => array('Eternizer' => 'search'));
}

/**
 * Search form component
 **/
function Eternizer_searchapi_options($args)
{
    if (SecurityUtil::checkPermission( 'Eternizer::', '::', ACCESS_READ)) {
        $pnRender = new pnRender('Eternizer');
        return $pnRender->fetch('Eternizer_search_options.tpl');
    }

    return '';
}
/**
 * Search plugin main function
 **/
function Eternizer_searchapi_search($args)
{
    $dom = ZLanguage::getModuleDomain('Eternizer');

    // TODO A - if we use the new search module from Daniel will this break?
    pnModDBInfoLoad('Search');
    $pntable = pnDBGetTables();
    $table = $pntable['Eternizer_entry'];
    $column = $pntable['Eternizer_entry_column'];
    $searchTable = $pntable['search_result'];
    $searchColumn = $pntable['search_result_column'];

    $where = search_construct_where($args,
    array($column['text']),
    null);

    $where .= " AND $column[obj_status] = 'A'";

    $sessionId = session_id();
    // define the permission filter to apply
    $permFilter = array(array('realm'          => 0,
                             'component_left' => 'Eternizer',
                             'instance_left'  => 'id',
                             'instance_right' => '',
                             'level'          => ACCESS_READ));
    // get the result set
    $objArray = DBUtil::selectObjectArray('Eternizer_entry', $where, 'id', 1, -1, '', $permFilter);
    if ($objArray === false) {
        return LogUtil::registerError (__('Error! Could not load items.', $dom));
    }

    $insertSql = "INSERT INTO  $searchTable
    (    $searchColumn[title],
    $searchColumn[text],
    $searchColumn[extra],
    $searchColumn[created],
    $searchColumn[module],
    $searchColumn[session])
    VALUES ";

    $titlefield = pnModGetVar('Eternizer', 'titlefield');

    // Process the result set and insert into search result table
    foreach ($objArray as $obj) {
        $extra = serialize(array('id' => $obj['id']));
        $sql = $insertSql . '('
        . '\'' . DataUtil::formatForStore($obj['__ATTRIBUTES__'][$titlefield]) . '\', '
        . '\'' . DataUtil::formatForStore($obj['text']) . '\', '
        . '\'' . DataUtil::formatForStore($extra) . '\', '
        . '\'' . DataUtil::formatForStore($obj['cr_date']) . '\', '
        . '\'' . 'Eternizer' . '\', '
        . '\'' . DataUtil::formatForStore($sessionId) . '\')';
        $insertResult = DBUtil::executeSQL($sql);
        if (!$insertResult) {
            return LogUtil::registerError (__('Error! Could not load items.', $dom));
        }
    }

    return true;
}

/**
 * Do last minute access checking and assign URL to items
 *
 * Access checking is ignored since access check has
 * already been done. But we do add a URL to the found item
 */
function Eternizer_searchapi_search_check(&$args)
{
    $datarow = &$args['datarow'];
    $extra = unserialize($datarow['extra']);

    $datarow['url'] = pnModUrl('Eternizer', 'user', 'main');

    return true;
}