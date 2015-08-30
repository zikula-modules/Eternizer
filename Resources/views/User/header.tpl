{* purpose of this template: header for user area *}
{pageaddvar name='stylesheet' value='web/bootstrap/css/bootstrap.min.css'}
{pageaddvar name='stylesheet' value='web/bootstrap/css/bootstrap-theme.min.css'}
{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='web/bootstrap/js/bootstrap.min.js'}
{pageaddvar name='javascript' value='zikula'}{* still required for Gettext *}
{pageaddvar name='stylesheet' value='web/bootstrap-jqueryui/bootstrap-jqueryui.min.css'}
{pageaddvar name='javascript' value='web/bootstrap-jqueryui/bootstrap-jqueryui.min.js'}
{if isset($smarty.get.func) && $smarty.get.func eq 'edit'}
    {pageaddvar name='javascript' value='polyfill' features='forms'}
{/if}
{pageaddvar name='javascript' value='@MUEternizerModule/Resources/public/js/MUEternizerModule.js'}

{* initialise additional gettext domain for translations within javascript *}
{pageaddvar name='jsgettext' value='mueternizermodule_js:MUEternizerModule'}

{if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
    <h2 class="userheader">{gt text='Eternizer' comment='This is the title of the header template'}</h2>
    {modulelinks modname='MUEternizerModule' type='user'}
{/if}
{insert name='getstatusmsg'}
