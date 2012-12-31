{* Purpose of this template: Display entries within an external context *}
<dl>
    {foreach item='item' from=$items}
    <dt>{$item.ip|safetext}</dt>
    {if $item.text}
    <dd>{$item.text|safehtml|truncate:200:"..."}</dd>
    {/if}
    <dd><a href="{modurl modname='Eternizer' type='user' func='display' ot=$objectType id=$item.id}">{gt text='Read more'}</a></dd>
    {foreachelse}
    <dt>{gt text='No entries found.'}</dt>
    {/foreach}
</dl>