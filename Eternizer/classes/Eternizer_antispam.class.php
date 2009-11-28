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


class Eternizer_antispam {

    function scan($data) {
        if (!isset($data['text'])) {
            return false;
        }
        if (!isset($data['profile'])) {
            return false;
        }
        $profile = pnModGetVar('Eternizer', 'profile');
        foreach ($profile as $id => $state) {
            if ($state == 0) {
                unset ($profile[$id]);
            }
        }

        //Call scan methods
        if (pnModAvailable('spamFree')) {
            Loader::loadClass('spamFree', 'modules/spamFree/classes');
            $sf = new spamFree();
            $sfdata = new spamFree_data();
            $sfdata->add_text($data['text']);
            foreach ($data['profile'] as $k => $v) {
                switch ($profile[$k]['type']) {
                    case 'name':
                        $sfdata->add_name($v);
                        break;
                    case 'mail':
                        $sfdata->add_email($v);
                        break;
                    case 'url':
                        $sfdata->add_page($v);
                        break;
                    default:
                        $sfdata->add_text($v);
                }
            }
            return !$sf->scan($sfdata);
        } elseif (pnModAvailable('akismet')) {
            $set = array('content' => $data['text']);
            foreach ($data['profile'] as $k => $v) {
                switch ($profile[$k]['type']) {
                    case 'name':
                        if (!isset($set['author'])) {
                            $set['author'] = $v;
                        }
                        break;
                    case 'mail':
                        if (!isset($set['authoremail'])) {
                            $set['authoremail'] = $v;
                        }
                        break;
                    case 'url':
                        if (!isset($set['authorurl'])) {
                            $set['authorurl'] = $v;
                        }
                        break;
                }
            }
            return pnModAPIFunc('akismet', 'user', 'isspam', $set);
        } else {
            return false;
        }
    }
}