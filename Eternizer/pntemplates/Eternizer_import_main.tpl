<!--[* $Id$ *]-->
<!--[*  ----------------------------------------------------------------------------- *]-->
<!--[*  Author: $Author: philipp $										  *]-->
<!--[*  Link: http://www.guite.de													  *]-->
<!--[*  Copyright: Copyright (C) 2005 by Guite										  *]-->
<!--[*  License: http://www.gnu.org/copyleft/gpl.html GNU General Public License	  *]-->
<!--[*  ----------------------------------------------------------------------------- *]-->
<!--[*  Purpose of this template:													  *]-->
<!--[*  Import Main					                                                  *]-->
<!--[*  ----------------------------------------------------------------------------- *]-->

<!--[modulestylesheet modname="Admin" stylesheet="admin.css"]-->
<!--[include file="Eternizer_admin_menu.tpl"]-->

<form class="pn-adminform" action="<!--[pnmodurl modname="Eternizer" type="import" func="display"]-->" method="post">
  <dl class="pn-adminformrow">
    <dt><!--[gt text="Import from"]--></dt>
    <!--[foreach from=$books key="name" item="name"]-->
    <dd><input type="radio" name="plugin" value="<!--[$name]-->" /> <!--[$name]--> </dd>
    <!--[/foreach]-->
  </dl>

  <div class="pn-adminformbuttons">
    <input type="submit" value="<!--[gt text='Continue']-->" />
  </div>
</form>

<!--[include file="Eternizer_admin_footer.tpl"]-->