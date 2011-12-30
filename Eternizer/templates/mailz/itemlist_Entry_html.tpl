{* Purpose of this template: Display entries in html mailings *}
{*
<ul>
{foreach item='item' from=$items}
    <li>
        <a href="{modurl modname='Eternizer' type='user' func='display' ot=$objectType id=$item.id fqurl=true}">{$item.ip}</a>
    </li>
{foreachelse}
    <li>{gt text='No entries found.'}</li>
{/foreach}
</ul>
*}

{include file='contenttype/itemlist_Entry_display_description.tpl'}
