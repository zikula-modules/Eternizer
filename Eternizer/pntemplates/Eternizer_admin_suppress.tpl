<!--[* $Id$ *]-->
<!--[*  ---------------------------------------------------------------------------- *]-->
<!--[*  Author: $Author: philipp $                                        *]-->
<!--[*  Link: http://www.guite.de	                                                 *]-->
<!--[*  Copyright: Copyright (C) 2005 by Guite	                                     *]-->
<!--[*  License: http://www.gnu.org/copyleft/gpl.html GNU General Public License	 *]-->
<!--[*  ---------------------------------------------------------------------------- *]-->
<!--[*  Purpose of this template:	                                                 *]-->
<!--[*  Suppress the deletion of an entry		                                     *]-->
<!--[*  ---------------------------------------------------------------------------- *]-->

<!--[gt text="Confirm deletion?"]--><br />
<a href="<!--[pnmodurl modname="Eternizer" type="admin" func="delete" id=$id goback=$goback]-->"><!--[gt text="Yes"]--></a>
<!--[pnmodurl modname="Eternizer" type="user" func="main" assign=defurl]-->
<a href="<!--[$cancelurl]-->"><!--[gt text="No"]--></a>
