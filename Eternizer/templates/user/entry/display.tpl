{* purpose of this template: entries display view in user area *}
{include file='user/header.tpl'}
<div class="eternizer-entry eternizer-display">
{gt text='Entry' assign='templateTitle'}
{assign var='templateTitle' value=$entry.ip|default:$templateTitle}
{pagesetvar name='title' value=$templateTitle|@html_entity_decode}
<div class="z-frontendcontainer">
    <h2>{$templateTitle|notifyfilters:'eternizer.filter_hooks.entries.filter'}</h2>


<dl id="Eternizer_body">
    <dt>{gt text='Text'}</dt>
    <dd>{$entry.text}</dd>
    <dt>{gt text='Notes'}</dt>
    <dd>{$entry.notes}</dd>
</dl>
    {include file='user/include_standardfields_display.tpl' obj=$entry}

{if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
<p>
    {checkpermissionblock component='Eternizer::' instance='.*' level='ACCESS_EDIT'}

        <a href="{modurl modname='Eternizer' type='user' func='edit' ot='entry' id=$entry.id}" title="{gt text='Edit'}" class="z-icon-es-edit">
            {gt text='Edit'}
        </a>
        <a href="{modurl modname='Eternizer' type='user' func='edit' ot='entry' astemplate=$entry.id}" title="{gt text='Reuse for new item'}" class="z-icon-es-saveas">
            {gt text='Reuse'}
        </a>
    {/checkpermissionblock}
    <a href="{modurl modname='Eternizer' type='user' func='view' ot='entry'}" title="{gt text='Back to overview'}" class="z-icon-es-back">
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
</div>
{include file='user/footer.tpl'}

