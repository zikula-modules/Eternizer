{* purpose of this template: entries display view *}
{assign var='area' value='User'}
{if $routeArea eq 'admin'}
    {assign var='area' value='Admin'}
{/if}
{include file="`$area`/header.tpl"}
<div class="mueternizermodule-entry mueternizermodule-display">
    {gt text='Entry' assign='templateTitle'}
    {assign var='templateTitle' value=$entry->getTitleFromDisplayPattern()|default:$templateTitle}
    {pagesetvar name='title' value=$templateTitle|@html_entity_decode}
    {if $routeArea eq 'admin'}
        <h3>
            <span class="fa fa-eye"></span>
            {$templateTitle|notifyfilters:'mueternizermodule.filter_hooks.entries.filter'}
            {if count($entry._actions) gt 0}
                <div class="dropdown">
                    <a id="itemActions{$entry.id}DropDownToggle" role="button" data-toggle="dropdown" data-target="#" href="javascript:void(0);" class="dropdown-toggle"><i class="fa fa-tasks"></i> {gt text='Actions'} <span class="caret"></span></a>
                    
                    <ul class="dropdown-menu" role="menu" aria-labelledby="itemActions{$entry.id}DropDownToggle">
                        {foreach item='option' from=$entry._actions}
                            <li role="presentation"><a href="{$option.url.type|mueternizermoduleActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}" role="menuitem" tabindex="-1" class="fa fa-{$option.icon}">{$option.linkText|safetext}</a></li>
                            
                        {/foreach}
                    </ul>
                </div>
                <script type="text/javascript">
                /* <![CDATA[ */
                    ( function($) {
                        $(document).ready(function() {
                            $('.dropdown-toggle').dropdown();
                            $('a.fa-zoom-in').attr('target', '_blank');
                        });
                    })(jQuery);
                /* ]]> */
                </script>
            {/if}
        </h3>
    {else}
        <h2>
            {$templateTitle|notifyfilters:'mueternizermodule.filter_hooks.entries.filter'}
            {if count($entry._actions) gt 0}
                <div class="dropdown">
                    <a id="itemActions{$entry.id}DropDownToggle" role="button" data-toggle="dropdown" data-target="#" href="javascript:void(0);" class="dropdown-toggle"><i class="fa fa-tasks"></i> {gt text='Actions'} <span class="caret"></span></a>
                    
                    <ul class="dropdown-menu" role="menu" aria-labelledby="itemActions{$entry.id}DropDownToggle">
                        {foreach item='option' from=$entry._actions}
                            <li role="presentation"><a href="{$option.url.type|mueternizermoduleActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}" role="menuitem" tabindex="-1" class="fa fa-{$option.icon}">{$option.linkText|safetext}</a></li>
                            
                        {/foreach}
                    </ul>
                </div>
                <script type="text/javascript">
                /* <![CDATA[ */
                    ( function($) {
                        $(document).ready(function() {
                            $('.dropdown-toggle').dropdown();
                            $('a.fa-zoom-in').attr('target', '_blank');
                        });
                    })(jQuery);
                /* ]]> */
                </script>
            {/if}
        </h2>
    {/if}


    <dl>
        <dt>{gt text='Ip'}</dt>
        <dd>{$entry.ip}</dd>
        <dt>{gt text='Name'}</dt>
        <dd>{$entry.name}</dd>
        <dt>{gt text='Email'}</dt>
        <dd>{if $entry.email ne ''}
        {if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
        <a href="mailto:{$entry.email}" title="{gt text='Send an email'}" class="fa fa-envelope"></a>
        {else}
          {$entry.email}
        {/if}
        {else}&nbsp;{/if}
        </dd>
        <dt>{gt text='Homepage'}</dt>
        <dd>{if $entry.homepage ne ''}
        {if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
        <a href="{$entry.homepage}" title="{gt text='Visit this page'}" class="fa fa-external-link-square"></a>
        {else}
          {$entry.homepage}
        {/if}
        {else}&nbsp;{/if}
        </dd>
        <dt>{gt text='Location'}</dt>
        <dd>{$entry.location}</dd>
        <dt>{gt text='Text'}</dt>
        <dd>{$entry.text}</dd>
        <dt>{gt text='Notes'}</dt>
        <dd>{$entry.notes}</dd>
        <dt>{gt text='Obj_status'}</dt>
        <dd>{$entry.obj_status}</dd>
        
    </dl>
    {include file='Helper/include_standardfields_display.tpl' obj=$entry}

    {if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
        {* include display hooks *}
        {notifydisplayhooks eventname='mueternizermodule.ui_hooks.entries.display_view' id=$entry.id urlobject=$currentUrlObject assign='hooks'}
        {foreach name='hookLoop' key='providerArea' item='hook' from=$hooks}
            {if $providerArea ne 'provider.scribite.ui_hooks.editor'}{* fix for #664 *}
                {$hook}
            {/if}
        {/foreach}
    {/if}
</div>
{include file="`$area`/footer.tpl"}
