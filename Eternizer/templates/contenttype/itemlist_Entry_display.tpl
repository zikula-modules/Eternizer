{* Purpose of this template: Display entries within an external context *}

{foreach item='item' from=$items}
    <h3>{$item.ip}</h3>
    <p><a href="{modurl modname='Eternizer' type='user' func='display' ot=$objectType id=$item.id}">{gt text='Read more'}</a></p>
{/foreach}
