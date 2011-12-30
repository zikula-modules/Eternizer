{* Purpose of this template: Display entries in text mailings *}
{foreach item='item' from=$items}
        {$item.ip}
        {modurl modname='Eternizer' type='user' func='display' ot=$objectType id=$item.id fqurl=true}
-----
{foreachelse}
    {gt text='No entries found.'}
{/foreach}
