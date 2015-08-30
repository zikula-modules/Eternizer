{* purpose of this template: entries index view *}
{assign var='lct' value='user'}
{if isset($smarty.get.lct) && $smarty.get.lct eq 'admin'}
    {assign var='lct' value='admin'}
{/if}
{assign var='lctUc' value=$lct|ucfirst}
{include file="`$lctUc`/header.tpl"}
<p>{gt text='Welcome to the entry section of the Eternizer application.'}</p>
{include file="`$lctUc`/footer.tpl"}
