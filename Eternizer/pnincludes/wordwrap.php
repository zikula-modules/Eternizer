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
 * Run an action for long words to a string
 *
 * @author    Philipp Niethammer <webmaster@nochwer.de>
 * @param    (string)    $string
 * @return    (string)    $string
 */
function Eternizer_WWAction ($string) {

     $wwaction = pnModGetVar("Eternizer", "wwaction");
     $wwlimit = pnModGetVar("Eternizer", "wwlimit");

     switch ($wwaction) {
        case 'truncate':
            $string = preg_replace_callback("~(\S{".$wwlimit.",})~", 'Eternizer_WWTruncate', $string);
            break;
        case 'wrap':
            $string = preg_replace_callback("~(\S{".$wwlimit.",})~", 'Eternizer_WWWrap', $string);
            break;
    }

    return $string;
}

function Eternizer_WWTruncate ($match) {
    $wwshortto = pnModGetVar("Eternizer", "wwshortto");

    $string = $match[1];

    if (stripos($string, '[/url]') === false && stripos($string, '[/img]') === false && stripos($string, '[/email]') === false)
        return $string;

    return substr($string, 0, $wwshortto);
}

function Eternizer_WWWrap ($match) {
    $wwshortto = pnModGetVar("Eternizer", "wwshortto");
    $string = $match[1];
    if (stripos($string, '[/url]') !== false && stripos($string, '[/img]') !== false && stripos($string, '[/email]') !== false)
        return $string;

    return wordwrap($string, $wwshortto, " ", 1);
}
