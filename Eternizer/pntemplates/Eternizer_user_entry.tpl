<!--[* $Id$ *]-->
<!--[*  ---------------------------------------------------------------------------- *]-->
<!--[*  Author: $Author: philipp $                                        *]-->
<!--[*  Link: http://www.guite.de	                                                 *]-->
<!--[*  Copyright: Copyright (C) 2005 by Guite	                                     *]-->
<!--[*  License: http://www.gnu.org/copyleft/gpl.html GNU General Public License	 *]-->
<!--[*  ---------------------------------------------------------------------------- *]-->
<!--[*  Purpose of this template:	                                                 *]-->
<!--[*  Single entry	                                                             *]-->
<!--[*  ---------------------------------------------------------------------------- *]-->
<!--[pnpageaddvar name=stylesheet value=modules/Eternizer/pnstyle/linkicons_small.css]-->

<div class="eternizer-entry">
  <div class="entry-header">
    <strong><!--[$profile[$config.titlefield]]--></strong>, <!--[$cr_date|pndate_format:datetimebrief]-->
  </div>
  <div class="entry-profile">
    <!--[if $cr_uid > 1]-->
    <!--[pnusergetvar name=_YOURAVATAR uid=$cr_uid assign=avatar]-->
    <!--[if $avatar ne "blank.gif" && $avatar]-->
    <img src="images/avatar/<!--[$avatar]-->" />
    <!--[else]-->
    <!--[eternizergravatar email=$profile.1 assign=avatar]-->
    <img src="<!--[eternizergravatar email=$profile.1]-->" />
    <!--[/if]-->
    <!--[else]-->
    <!--[eternizergravatar email=$profile.1 assign=avatar]-->
    <img src="<!--[eternizergravatar email=$profile.1]-->" />
    <!--[/if]-->

    <dl>
      <!--[foreach from=$profile key=pid item=value]-->
      <!--[if $value != '' && $pid != $config.titlefield]-->
      <dt><!--[$config.profile.$pid.title]--></dt>
      <dd>
        <!--[if $config.profile.$pid.type eq 'mail']-->
        <a href="mailto:<!--[$value]-->"><!--[$value]--></a>
        <!--[elseif $config.profile.$pid.type eq 'url']-->
        <a href="<!--[$value]-->"><!--[$value]--></a>
        <!--[else]-->
        <!--[$value]-->
        <!--[/if]-->
      </dd>
      <!--[/if]-->
      <!--[/foreach]-->
    </dl>

    <!--[if $right_comment && !$right_edit]-->
    <a class="comment" href="<!--[pnmodurl modname=Eternizer type=admin func=modify id=$id]-->" title="<!--[gt text="Comment this entry"]-->"><!--[gt text="Comment"]--></a>
    <!--[/if]-->
    <!--[if $right_edit]-->
    <a class="edit" href="<!--[pnmodurl modname=Eternizer type=admin func=modify id=$id]-->" title="<!--[gt text="Edit this entry"]-->"><!--[gt text="Edit"]--></a>
    <!--[/if]-->
    <!--[if $right_delete]-->
    <a class="delete" href="<!--[pnmodurl modname=Eternizer type=admin func=suppress id=$id]-->" title="<!--[gt text="Delete this entry"]-->"><!--[gt text="Delete"]--></a>
    <!--[/if]-->
  </div>
  <div class="entry-text">
    <!--[$text]-->
    <!--[if $comment]-->
    <p style="margin-top: 2em;" class="entry-comment"><strong class="entry-comment-label"><!--[gt text="Comment"]-->:</strong><br />
      <!--[$comment]-->
    </p>
    <!--[/if]-->
  </div>
</div>
