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

<!--[pnml name=_ETERNIZER_TITLE assign=title]-->
<!--[pnpagesetvar name=title value=$title]-->
<!--[insert name="getstatusmsg"]-->

<div id="eternizer">
  <h2><!--[pnml name=_ETERNIZER_TITLE]--></h2>
  <!--[$form]-->

  <div id="eternizer-entries" class="pn-clearfix">
    <!--[foreach from=$entries item=entry]-->
    <!--[$entry]-->
    <!--[/foreach]-->
  </div>

  <!--[pager posvar="startnum" rowcount=$count limit=$config.perpage]-->
</div>
