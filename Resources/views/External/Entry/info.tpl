{* Purpose of this template: Display item information for previewing from other modules *}
<dl id="entry{$entry.id}">
<dt>{$entry->getTitleFromDisplayPattern()|notifyfilters:'eternizer.filter_hooks.entries.filter'|htmlentities}</dt>
{if $entry.text ne ''}<dd>{$entry.text}</dd>{/if}
</dl>
