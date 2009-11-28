<!--[* $Id$ *]-->
<!--[*  ---------------------------------------------------------------------------- *]-->
<!--[*  Author: $Author: philipp $                                        *]-->
<!--[*  Link: http://www.guite.de	                                                 *]-->
<!--[*  Copyright: Copyright (C) 2005 by Guite	                                     *]-->
<!--[*  License: http://www.gnu.org/copyleft/gpl.html GNU General Public License	 *]-->
<!--[*  ---------------------------------------------------------------------------- *]-->
<!--[*  Purpose of this template:	                                                 *]-->
<!--[*  User main area	                                                             *]-->
<!--[*  ---------------------------------------------------------------------------- *]-->

<!--[gt text="Guestbook assign=title"]-->
<!--[pnpagesetvar name=title value=$title]-->
<!--[insert name="getstatusmsg"]-->

<div id="eternizer">
  <h2><!--[gt text="Guestbook"]--></h2>
  <!--[$form]-->

  <div id="eternizer-entries" class="pn-clearfix">
    <!--[foreach from=$entries item=entry]-->
    <!--[$entry]-->
    <!--[/foreach]-->
  </div>

  <!--[pager posvar="startnum" rowcount=$count limit=$config.perpage]-->
</div>
