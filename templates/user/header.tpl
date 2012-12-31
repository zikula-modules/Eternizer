{* purpose of this template: header for user area *}
{pageaddvar name='javascript' value='prototype'}
{pageaddvar name='javascript' value='validation'}
{pageaddvar name='javascript' value='zikula'}
{pageaddvar name='javascript' value='livepipe'}
{pageaddvar name='javascript' value='zikula.ui'}
{pageaddvar name='javascript' value='zikula.imageviewer'}

{if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
<div class="z-frontendbox">
    <h2>{gt text='Eternizer' comment='This is the title of the header template'}</h2>
    {modulelinks modname='Eternizer' type='user'}
</div>
{/if}
{insert name='getstatusmsg'}
