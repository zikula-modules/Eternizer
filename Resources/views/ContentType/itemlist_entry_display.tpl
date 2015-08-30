{* Purpose of this template: Display entries within an external context *}
{foreach item='entry' from=$items}
    <h3>{$entry->getTitleFromDisplayPattern()}</h3>
    <p><a href="{route name='mueternizermodule_entry_display'  id=$entry.id}">{gt text='Read more'}</a>
    </p>
{/foreach}
