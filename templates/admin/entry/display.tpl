{* purpose of this template: entries display view in admin area *}
{include file='admin/header.tpl'}
<div class="eternizer-entry eternizer-display">
    {gt text='Entry' assign='templateTitle'}
    {assign var='templateTitle' value=$entry.ip|default:$templateTitle}
    {pagesetvar name='title' value=$templateTitle|@html_entity_decode}
    <div class="z-admin-content-pagetitle">
        {icon type='display' size='small' __alt='Details'}
        <h3>{$templateTitle|notifyfilters:'eternizer.filter_hooks.entries.filter'}</h3>
    </div>

    <dl id="Eternizer_body">
        <dt>{gt text='Name'}</dt>
        <dd>{$entry.name|safetext}</dd>
        <dt>{gt text='Email'}</dt>
        <dd>
            {if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
            <a href="mailto:{$entry.email|safetext}" title="{gt text='Send an email'}">{icon type='mail' size='extrasmall' __alt='Email'}</a>
            {else}
            {$entry.email|safehtml}
            {/if}
        </dd>
        <dt>{gt text='Homepage'}</dt>
        <dd>
            {if $entry.homepage ne ''}
            {if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
            <a href="{$entry.homepage|safehtml}" title="{gt text='Visit this page'}">{icon type='url' size='extrasmall' __alt='Homepage'}</a>
            {else}
            {$entry.homepage|safehtml}
            {/if}
            {else}
            &nbsp;
            {/if}
        </dd>
        <dt>{gt text='Location'}</dt>
        <dd>{$entry.location|safehtml}</dd>
        <dt>{gt text='Text'}</dt>
        <dd>{$entry.text|safehtml}</dd>
        <dt>{gt text='Notes'}</dt>
        <dd>{$entry.notes|safehtml}</dd>
        <dt>{gt text='Status'}</dt>
        <dd>{$entry.obj_status|safehtml}</dd>
    </dl>
    {include file='admin/include_standardfields_display.tpl' obj=$entry}

    {if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
    {if count($entry._actions) gt 0}
    <p>
        {strip}
        {foreach item='option' from=$entry._actions}
        <a href="{$option.url.type|eternizerActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}" class="z-icon-es-{$option.icon}">{$option.linkText|safetext}</a>
        {/foreach}
        {/strip}
    </p>
    {/if}

    {* include display hooks *}
    {notifydisplayhooks eventname='eternizer.ui_hooks.entries.display_view' id=$entry.id urlobject=$currentUrlObject assign='hooks'}
    {foreach key='hookname' item='hook' from=$hooks}
    {$hook}
    {/foreach}

    {/if}

</div>
{include file='admin/footer.tpl'}

