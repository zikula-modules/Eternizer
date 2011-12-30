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
</dl>
    {include file='admin/include_standardfields_display.tpl' obj=$entry}

{if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
<p>
    {checkpermissionblock component='Eternizer::' instance='.*' level='ACCESS_EDIT'}

        <a href="{modurl modname='Eternizer' type='admin' func='edit' ot='entry' id=$entry.id}" title="{gt text='Edit'}" class="z-icon-es-edit">
            {gt text='Edit'}
        </a>
        <a href="{modurl modname='Eternizer' type='admin' func='edit' ot='entry' astemplate=$entry.id}" title="{gt text='Reuse for new item'}" class="z-icon-es-saveas">
            {gt text='Reuse'}
        </a>
    {/checkpermissionblock}
    <a href="{modurl modname='Eternizer' type='admin' func='view' ot='entry'}" title="{gt text='Back to overview'}" class="z-icon-es-back">
        {gt text='Back to overview'}
    </a>
</p>

{* include display hooks *}
{notifydisplayhooks eventname='eternizer.ui_hooks.entries.display_view' id=$entry.id urlobject=$currentUrlObject assign='hooks'}
{foreach key='hookname' item='hook' from=$hooks}
    {$hook}
{/foreach}

{/if}

</div>
{include file='admin/footer.tpl'}

