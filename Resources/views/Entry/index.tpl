{* purpose of this template: entries index view *}
{assign var='area' value='User'}
{if $routeArea eq 'admin'}
    {assign var='area' value='Admin'}
{/if}
{include file="`$area`/header.tpl"}
<p>{gt text='Welcome to the entry section of the Eternizer application.'}</p>
{include file="`$area`/footer.tpl"}
