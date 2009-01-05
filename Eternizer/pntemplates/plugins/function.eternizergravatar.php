<?php
/**
 * Smarty function to return the url of a users gravatar
 *
 * This function takes a identifier and returns the corresponding language constant.
 *
 * For information on the usage of this plugin witin Zikula please see
 * http://www.markwest.me.uk/Article37.phtml
 *
 * based on gravatar plugin for Wordpress
 *	Author: Tom Werner
 *	Author URI: http://www.mojombo.com/
 *
 * Available parameters:
 *   - email:    E-mail address of the user to get the gravatar for
 *   - html:     Treat the language define as HTML
 *   - assign:   If set, the results are assigned to the corresponding variable instead of printed out
 *
 * Example
 *	<!--[pnusergetvar name=email uid=$info.aid assign=email]-->
 *	<img src="<!--[gravatar email=$email]-->" alt="" />
 *
 *
 * @author       Mark West
 * @since        30/08/2005
 * @link         http://www.gravatar.com/
 * @link         http://www.markwest.me.uk/Article37.phtml
 * @param        array       $params      All attributes passed to this function from the template
 * @param        object      &$smarty     Reference to the Smarty object
 * @return       string      the url to display the users gravatar
 */
function smarty_function_eternizergravatar($params, &$smarty)
{
    extract($params);
    unset($params);

    if (!isset($email)) {
        $smarty->trigger_error('gravatar: attribute email required');
        return false;
    }
    
    $email = html_entity_decode($email);

	if (!isset($rating)) $rating = false;
	if (!isset($size)) $size = false;
	if (!isset($default)) $default = false;
	if (!isset($border)) $border = false;

	$out = 'http://www.gravatar.com/avatar.php?gravatar_id='.md5($email);
	if($rating && $rating != '')
		$out .= "&rating=".$rating;
	if($size && $size != '')
		$out .="&size=".$size;
	if($default && $default != '')
		$out .= "&default=".urlencode($default);
	if($border && $border != '')
		$out .= "&border=".$border;
	
	if (!empty($assign)) {
	    $smarty->assign($assign, pnVarPrepForDisplay($out));
	} else {
	    return pnVarPrepForDisplay($out);
	}
}

?>