{* Purpose of this template: Display entries in html mailings *}
{*
<ul>
{foreach item='entry' from=$items}
    <li>
        <a href="{route name='mueternizermodule_entry_display' id=$entry.id absolute=true}
        ">{$entry->getTitleFromDisplayPattern()}
        </a>
    </li>
{foreachelse}
    <li>{gt text='No entries found.'}</li>
{/foreach}
</ul>
*}

{include file='ContentType/itemlist_entry_display_description.tpl'}
