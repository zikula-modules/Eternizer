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

class Eternizer_admin_configHandler
{
    var $init;

    function Eternizer_admin_configHandler($init=false)
    {
        $this->init = (bool) $init;
    }

    function initialize(&$render) {
        $dom = ZLanguage::getModuleDomain('Eternizer');
        if (!SecurityUtil::checkPermission(($this->init?'':'Eternizer').'::', '::', ACCESS_ADMIN)) {
            return $render->pnFormSetErrorMsg(__('Sorry! No authorization to access this module.', $dom));
        }

        if (!$this->init) {
            $modvars = pnModGetVar('Eternizer');
        } else {
            $modvars = pnModFunc('Eternizer', 'init', 'getDefaultConfig');
        }

        $modvars['orderItems']         = array(array( 'value' => 'asc',
                                                      'text'  => __('ascending', $dom)),
                                            array(    'value' => 'desc',
                                                      'text'  => __('descending', $dom)));

        $modvars['spammodeItems']   = array(array(    'value' => '0',
                                                      'text'  => __('No', $dom)),
                                            array(    'value' => '1',
                                                      'text'  => __('Guests only, Moderate', $dom)),
                                            array(    'value' => '2',
                                                      'text'  => __('Guests only, Reject', $dom)),
                                            array(    'value' => '3',
                                                      'text'  => __('All, Moderate', $dom)),
                                            array(    'value' => '4',
                                                      'text'  => __('All, Reject', $dom)));

        $modvars['moderateItems']    = array(array(   'value' => '0',
                                                      'text'  => __('No', $dom)),
                                            array(    'value' => '1',
                                                      'text'  => __('Guests only', $dom)),
                                            array(    'value' => '2',
                                                      'text'  => __('All users', $dom)));

        $modvars['wwactionItems']     = array(array(  'value'  => 'nothing',
                                                      'text'   => __('no', $dom)),
                                            array(    'value'  => 'truncate',
                                                      'text'   => __('truncate', $dom)),
                                            array(    'value'  => 'wrap',
                                                      'text'   => __('wrap', $dom)));

        $modvars['pnnotify_available'] = (bool) pnModAvailable('pn_notify');

        $selects['mandatoryItems']    = array(array(  'value'   => 0,
                                                      'text'    => __('Optional', $dom)),
                                            array(    'value'   => 1,
                                                      'text'    => __('anonymous users', $dom)),
                                            array(    'value'   => 2,
                                                      'text'    => __('All', $dom)));
        $selects['typeItems']   = array(array(        'value'   => 'name',
                                                      'text'    => __('Name', $dom)),
                                            array(    'value'   => 'mail',
                                                      'text'    => __('E-mail address', $dom)),
                                            array(    'value'   => 'url',
                                                      'text'    => __('URL', $dom)),
                                            array(    'value'   => 'text',
                                                      'text'    => __('Text', $dom)));

        $selects['titlefieldItems'] = array();
        foreach ($modvars['profile'] as $k => $v) {
            $selects['titlefieldItems'][] = array('value' => $k, 'text' => pnML($v['title']));
        }

        $profileItems = pnModAPIFunc('Profile', 'user', 'getallactive');

        $selects['profileItems'] = array(array('value' => '0', 'text' => __('No', $dom)));
        foreach ($profileItems as $field) {
            if ($field['prop_viewby'] == 0 && $field['prop_displaytype'] < 2) {
                $selects['profileItems'][] = array('value' => $field['prop_label'],
                                                    'text'  => pnML($field['prop_label']));
            }
        }

        $modvars['init'] = $this->init;

        $render->assign($modvars);
        $render->assign($selects);

        return true;
    }

    function handleCommand(&$render, $args)
      {
        switch($args['commandName']) {
        case 'update':
            if (!$render->pnFormIsValid())
            return false;

              $data = $render->pnFormGetValues();

              pnModDelVar('Eternizer');

            $profile = array();
            $newprofile = array();
              foreach ($data['profile'] as $k => $v) {
                  $info = explode('_', $k);

                  if (substr($info[1], 0, 1) == 'n') {
                      $newprofile[$info[1]][$info[2]] = $v;
                  } else {
                      $profile[$info[1]][$info[2]] = $v;
                  }
              }
              foreach ($newprofile as $v) {
                  if (!empty($v['title']))
                      $profile[] = $v;
              }

              $posarray = array();
            foreach (array_keys($profile) as $k) {
                if (!empty($profile[$k]['title'])) {
                    $posarray[$profile[$k]['pos']] = $k;
                }
            }

            ksort($posarray);

            $sortprofile = array();
            foreach ($posarray as $k) {
                $sortprofile[$k] = $profile[$k];
            }
            $profile =& $sortprofile;

              unset($data['profile']);
              foreach ($data as $k => $v) {
                  pnModSetVar('Eternizer', $k, $v);
              }

              pnModSetVar('Eternizer', 'profile', $profile);

              if (!$this->init) {
                  $url = pnModUrl('Eternizer', 'admin', 'config');
              } else {
                  $url = pnModUrl('Eternizer', 'init', 'step3');
              }

              return $render->pnFormRedirect($url);
              break;
          case 'cancel':
          default:
              if (!$this->init) {
                  $url = pnModUrl('Eternizer', 'admin', 'config');
              } else {
                  $url = pnModUrl('Eternizer', 'init', 'step2');
              }
              return $render->pnFormRedirect($url);
        }

        return true;
      }
}