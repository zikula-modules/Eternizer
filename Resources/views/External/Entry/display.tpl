{* Purpose of this template: Display one certain entry within an external context *}
<div id="entry{$entry.id}" class="mueternizermodule-external-entry">
{if $displayMode eq 'link'}
    <p class="mueternizermodule-external-link">
    <a href="{route name='mueternizermodule_entry_display'  id=$entry.id}" title="{$entry->getTitleFromDisplayPattern()|replace:"\"":""}">
    {$entry->getTitleFromDisplayPattern()|notifyfilters:'eternizer.filter_hooks.entries.filter'}
    </a>
    </p>
{/if}
{checkpermissionblock component='MUEternizerModule::' instance='::' level='ACCESS_EDIT'}
    {if $displayMode eq 'embed'}
        <p class="mueternizermodule-external-title">
            <strong>{$entry->getTitleFromDisplayPattern()|notifyfilters:'eternizer.filter_hooks.entries.filter'}</strong>
        </p>
    {/if}
{/checkpermissionblock}

{if $displayMode eq 'link'}
{elseif $displayMode eq 'embed'}
    <div class="mueternizermodule-external-snippet">
        &nbsp;
    </div>

    {* you can distinguish the context like this: *}
    {*if $source eq 'contentType'}
        ...
    {elseif $source eq 'scribite'}
        ...
    {/if*}

    {* you can enable more details about the item: *}
    {*
        <p class="mueternizermodule-external-description">
            {if $entry.text ne ''}{$entry.text}<br />{/if}
        </p>
    *}
{/if}
</div>
