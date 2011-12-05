{* $Id$ *}
<dl id="bookentries">
    {foreach from=$entryarray item=entry}
    <dt>{$entry.cr_date|date_format:"%x %X"}: {$entry.profile.1|safetext}</dt>
    <dd>{$entry.text|safetext}</dd>
    {/foreach}
</dl>