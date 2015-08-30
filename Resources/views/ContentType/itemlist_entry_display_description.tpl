{* Purpose of this template: Display entries within an external context *}
<dl>
    {foreach item='entry' from=$items}
        <dt>{$entry->getTitleFromDisplayPattern()}</dt>
        {if $entry.text}
            <dd>{$entry.text|strip_tags|truncate:200:'&hellip;'}</dd>
        {/if}
        <dd><a href="{route name='mueternizermodule_entry_display'  id=$entry.id}">{gt text='Read more'}</a>
        </dd>
    {foreachelse}
        <dt>{gt text='No entries found.'}</dt>
    {/foreach}
</dl>
