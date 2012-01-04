{* purpose of this template: entries display view in user area *}
{include file='user/header.tpl'}
<div class="eternizer-entry eternizer-display">
{gt text='Entry' assign='templateTitle'}
{assign var='templateTitle' value=$entry.ip|default:$templateTitle}
{pagesetvar name='title' value=$templateTitle|@html_entity_decode}
<div class="z-frontendcontainer">
    <h2>{$templateTitle|notifyfilters:'eternizer.filter_hooks.entries.filter'}</h2>


<dl id="Eternizer_body">
    <dt>{gt text='Name'}</dt>
    <dd>{$entry.name}</dd>
    <dt>{gt text='Email'}</dt>
    <dd>  {if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
    <a href="mailto:{$entry.email}" title="{gt text='Send an email'}">
        {icon type='mail' size='extrasmall' __alt='Email'}
    </a>
  {else}
    {$entry.email}
  {/if}
</dd>
    <dt>{gt text='Homepage'}</dt>
    <dd>{if $entry.homepage ne ''}
  {if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
    <a href="{$entry.homepage}" title="{gt text='Visit this page'}">
        {icon type='url' size='extrasmall' __alt='Homepage'}
    </a>
  {else}
    {$entry.homepage}
  {/if}
{else}&nbsp;{/if}</dd>
    <dt>{gt text='Location'}</dt>
    <dd>{$entry.location}</dd>
    <dt>{gt text='Text'}</dt>
    <dd>{$entry.text}</dd>
    <dt>{gt text='Notes'}</dt>
    <dd>{$entry.notes}</dd>
    <dt>{gt text='Obj_status'}</dt>
    <dd>{$entry.obj_status}</dd>
</dl>
    {include file='user/include_standardfields_display.tpl' obj=$entry}

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
</div>
{include file='user/footer.tpl'}

