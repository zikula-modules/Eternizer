{* Purpose of this template: Display entries within an external context *}
{foreach item='item' from=$items}
<div class="eternizer_entry_block">
    {if $item.name ne ''}
    <dt>{$item.name|safetext}</dt>
    {else}
    <dt>{usergetvar name='uname' uid=$item.createdUserId assign='uname'}{$uname|safetext}</dt>
    {/if}
    <dd>{$item.text|safehtml|truncate:50:"..."}</dd>
</div>
{/foreach}
<p><a href="{modurl modname='Eternizer' type='user' func='view'}">{gt text='Visit our guestbook'}</a></p>
