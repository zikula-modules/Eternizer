{* purpose of this template: entries view json view *}
[
{foreach item='item' from=$items name='entries'}
    {if not $smarty.foreach.entries.first},{/if}
    {$item->toJson()}
{/foreach}
]
