<!--[ $Id$ ]-->
<div class="etz_entry z-clearfix <!--[cycle values='etz_bg1,etz_bg2']-->" >
    <div class="etz_author">
        <div class="etz_avatar">
            <strong><!--[$profile[$config.titlefield]|pnvarprepfordisplay]--></strong>
            <br />
            <!--[if $cr_uid > 1]-->
            <!--[pnusergetvar name=_YOURAVATAR uid=$cr_uid assign=avatar]-->
            <!--[if $avatar ne "blank.gif" && $avatar]-->
            <img src="<!--[pngetbaseurl]--><!--[$avatarpath]-->/<!--[$avatar]-->" alt="avatar" />
            <!--[else]-->
            <!--[eternizergravatar email=$profile.1 assign=avatar]-->
            <img src="<!--[eternizergravatar email=$profile.1]-->" alt="avatar" />
            <!--[/if]-->
            <!--[else]-->
            <!--[eternizergravatar email=$profile.1 assign=avatar]-->
            <img src="<!--[eternizergravatar email=$profile.1]-->" alt="avatar" />
            <!--[/if]-->
        </div>
        <dl class="etz_options">
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
            <!--[if $pncore.logged_in eq true]-->
            <dd>
                <!--[if $right_comment && !$right_edit]-->
                <a href="<!--[pnmodurl modname=Eternizer type=admin func=modify id=$id]-->" title="<!--[gt text="Comment this entry"]-->"><!--[pnimg modname=core src=comment.gif set='icons/extrasmall' __title="Comment this entry" __alt="Comment this entry"]--></a>
                <!--[/if]-->
                <!--[if $right_edit]-->
                <a href="<!--[pnmodurl modname=Eternizer type=admin func=modify id=$id]-->" title="<!--[gt text="Edit this entry"]-->"><!--[pnimg modname=core src=edit.gif set='icons/extrasmall' __title="Edit this entry" __alt="Edit this entry"]--></a>
                <!--[/if]-->
                <!--[if $right_delete]-->
                <a href="<!--[pnmodurl modname=Eternizer type=admin func=suppress id=$id]-->" title="<!--[gt text="Delete this entry"]-->"><!--[pnimg modname=core src=14_layer_deletelayer.gif set='icons/extrasmall' __title="Delete this entry" __alt="Delete this entry"]--></a>
                <!--[/if]-->
            </dd>
            <!--[/if]-->
        </dl>
    </div>

    <div class="etz_body">
        <div class="etz_info">
            <strong class="etz_title"><!--[$cr_date|pndate_format:datetimebrief]--></strong>
        </div>
        <div class="etz_content">
            <!--[$text|pnvarprepfordisplay|nl2br|pnmodcallhooks]-->
            <!--[if $comment]-->
            <p style="margin-top: 2em;" class="entry-comment"><strong class="entry-comment-label"><!--[gt text="Comment"]-->:</strong><br />
                <!--[$comment]-->
            </p>
            <!--[/if]-->
        </div>
    </div>
</div>
