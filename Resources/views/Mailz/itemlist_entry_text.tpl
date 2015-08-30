{* Purpose of this template: Display entries in text mailings *}
{foreach item='entry' from=$items}
{$entry->getTitleFromDisplayPattern()}
{route name='mueternizermodule_entry_display' id=$entry.id absolute=true}
-----
{foreachelse}
{gt text='No entries found.'}
{/foreach}
