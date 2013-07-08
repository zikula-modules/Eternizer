{* purpose of this template: entries view view in user area *}
<div class="eternizer-entry eternizer-view">
{include file='user/header.tpl'}
{gt text='Entry list' assign='templateTitle'}
{pagesetvar name='title' value=$templateTitle}
    <div id="eternizer">
        <h2>{$templateTitle}</h2>

    {if isset($showAllEntries) && $showAllEntries eq 1}
        {gt text='Back to paginated view' assign='linkTitle'}
        <a href="{modurl modname='Eternizer' type='user' func='view' ot='entry'}" title="{$linkTitle}" class="z-icon-es-view">{$linkTitle}</a>
        {else}
        {gt text='Show all entries' assign='linkTitle'}
        <a href="{modurl modname='Eternizer' type='user' func='view' ot='entry' all=1}" title="{$linkTitle}" class="z-icon-es-view">{$linkTitle}</a>
    {/if}

    {if $formposition eq 'above'}
        {modfunc modname='Eternizer' type='user' func='edit'}
        {notifydisplayhooks eventname='eternizer.ui_hooks.entries.display_view' urlobject=$currentUrlObject assign='hooks'}
        {foreach key='hookname' item='hook' from=$hooks}
            {$hook}
        {/foreach}
    {/if}

    {foreach item='entry' from=$items}
        <div class="etz_entry z-clearfix {cycle values='etz_bg1,etz_bg2'}">
            <div class="etz_author">
                <div class="etz_avatar">{useravatar uid=$entry.createdUserId}</div>
                {if $entry.email ne '' || $entry.homepage ne '' || $entry.location ne ''}
                    <dl class="etz_options">
                        {if $entry.email ne ''}
                            <dt>{gt text='Email'}</dt>
                            <dd>
                                <a href="mailto:{$entry.email|safehtml}" title="{gt text='Send an email'}">{icon type='mail' size='extrasmall' __alt='Email'}</a>
                            </dd>
                        {/if}
                        {if $entry.homepage ne ''}
                            <dt>{gt text='Homepage'}</dt>
                            <dd>
                                <a href="{$entry.homepage|safehtml}" title="{gt text='Visit this page'}">{icon type='url' size='extrasmall' __alt='Homepage'}</a>
                            </dd>
                        {/if}
                        {if $entry.location ne ''}
                            <dt>{gt text='Location'}</dt>
                            <dd>{$entry.location|safehtml}</dd>
                        {/if}
                    </dl>
                {/if}
            </div>
            <div class="etz_body">
                <div class="etz_info">
                    <div class="etz_title">
                        <div class="etz_name">
                            {if $entry.name ne ''}
                                <span class="etz_attr">{$entry.name|safehtml}</span>
                                {else}
                                {if $entry.createdUserId eq 0}
                                    {modgetvar module='Users' name='anonymous' assign='guest'}
                                    {$guest|safehtml}
                                    {else}
                                    {usergetvar name='uname' uid=$entry.createdUserId assign='uname'}
                                    {if $uname ne ''}
                                        {$uname|safehtml}
                                        {else}
                                        {$guest|safehtml}
                                    {/if}
                                {/if}
                            {/if}
                            {gt text='on %s' tag1=$entry.createdDate|dateformat:datetimelong}
                        </div>
                    </div>
                    <div class="etz_action">
                        {if count($entry._actions) gt 0}
                            {if $entry.createdUserId eq $userid && $coredata.logged_in eq true && $editentries eq 1}
                            <div class="eternizer-user-entry-edit">{$entry.id|eternizerGetStateOfEditOfEntry}</div>
                               {* {strip}
                                    {foreach item='option' from=$entry._actions}
                                        <a href="{$option.url.type|eternizerActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}"{if $option.icon eq 'preview'} target="_blank"{/if}>{icon type=$option.icon size='extrasmall' alt=$option.linkText|safetext}</a>
                                    {/foreach}
                                {/strip} *}
                            {/if}
                        {/if}
                    </div>
                </div>
                <div class="etz_content">
                    {$entry.text|notifyfilters:'eternizer.filter_hooks.entries.filter'|safehtml|nl2br}
                    {if $entry.notes ne ''}
                        {if $entry.updatedUserId eq $entry.createdUserId}
                        <p style="margin-top: 2em;" class="entry-comment">
                            <strong class="entry-comment-label">{gt text="Comment:"}</strong>
                            <br/>
                            {$entry.notes|safehtml}
                        </p>
                        {/if}
                    {/if}
                    {if $entry.updatedDate > $entry.createdDate && $entry.createdUserId eq $entry.updatedUserId}
                        <br/>
                        <span class="etz_updated">{gt text='Updated on %s' tag1=$entry.updatedDate|dateformat:datetimelong}</span>
                    {/if}
                </div>
            </div>
        </div>
        {foreachelse}
        <tr class="z-datatableempty">
            <td class="z-left" colspan="9">{gt text='No entries found.'}</td>
        </tr>
    {/foreach}

    {if !isset($showAllEntries) || $showAllEntries ne 1}
        {pager rowcount=$pager.numitems limit=$pager.itemsperpage display='page'}
    {/if}

    {if $formposition eq 'below'}
        {modfunc modname='Eternizer' type='user' func='edit'}
        {notifydisplayhooks eventname='eternizer.ui_hooks.entries.display_view' urlobject=$currentUrlObject assign='hooks'}
        {foreach key='hookname' item='hook' from=$hooks}
            {$hook}
        {/foreach}
    {/if}

    </div>
</div>
{include file='user/footer.tpl'}