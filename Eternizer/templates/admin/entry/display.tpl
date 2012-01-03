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
    <dt>{gt text='Text'}</dt>
    <dd>{$entry.text}</dd>
    <dt>{gt text='Notes'}</dt>
    <dd>{$entry.notes}</dd>
    <dt>{gt text='Obj_status'}</dt>
    <dd>{$entry.obj_status}</dd>
</dl>
    {include file='admin/include_standardfields_display.tpl' obj=$entry}

{if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
{if count($entry._actions) gt 0}
    <p>{strip}
    {foreach item='option' from=$entry._actions}
        <a href="{$option.url.type|eternizerActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}" class="z-icon-es-{$option.icon}">
            {$option.linkText|safetext}
        </a>
    {/foreach}
    {/strip}</p>
{/if}

{* include display hooks *}
{notifydisplayhooks eventname='eternizer.ui_hooks.entries.display_view' id=$entry.id urlobject=$currentUrlObject assign='hooks'}
{foreach key='hookname' item='hook' from=$hooks}
    {$hook}
{/foreach}

{/if}

</div>
{include file='admin/footer.tpl'}

