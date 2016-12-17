{* purpose of this template: entries list view *}
{assign var='area' value='User'}
{if $routeArea eq 'admin'}
    {assign var='area' value='Admin'}
{/if}
{include file="`$area`/header.tpl"}
<div class="mueternizermodule-entry mueternizermodule-view">
    {gt text='Entry list' assign='templateTitle'}
    {pagesetvar name='title' value=$templateTitle}
    {if $routeArea eq 'admin'}
        <h3>
            <span class="fa fa-list"></span>
            {$templateTitle}
        </h3>
    {else}
        <h2>{$templateTitle}</h2>
    {/if}

    {if $canBeCreated}
        {checkpermissionblock component='MUEternizerModule:Entry:' instance='::' level='ACCESS_EDIT'}
            {gt text='Create entry' assign='createTitle'}
            <a href="{route name="mueternizermodule_entry_`$routeArea`edit"}" title="{$createTitle}" class="fa fa-plus">{$createTitle}</a>
        {/checkpermissionblock}
    {/if}
    {assign var='own' value=0}
    {if isset($showOwnEntries) && $showOwnEntries eq 1}
        {assign var='own' value=1}
    {/if}
    {assign var='all' value=0}
    {if isset($showAllEntries) && $showAllEntries eq 1}
        {gt text='Back to paginated view' assign='linkTitle'}
        <a href="{route name="mueternizermodule_entry_`$routeArea`view"}" title="{$linkTitle}" class="fa fa-table">{$linkTitle}</a>
        {assign var='all' value=1}
    {else}
        {gt text='Show all entries' assign='linkTitle'}
        <a href="{route name="mueternizermodule_entry_`$routeArea`view" all=1}" title="{$linkTitle}" class="fa fa-table">{$linkTitle}</a>
    {/if}

    {include file='Entry/view_quickNav.tpl' all=$all own=$own workflowStateFilter=false}{* see template file for available options *}

    {if $routeArea eq 'admin'}
    <form action="{route name="mueternizermodule_entry_`$routeArea`handleselectedentries"}" method="post" id="entriesViewForm" class="form-horizontal" role="form">
        <div>
            <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
    {/if}
        <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover table-condensed">
            <colgroup>
                {if $routeArea eq 'admin'}
                    <col id="cSelect" />
                {/if}
                <col id="cIp" />
                <col id="cName" />
                <col id="cEmail" />
                <col id="cHomepage" />
                <col id="cLocation" />
                <col id="cText" />
                <col id="cNotes" />
                <col id="cObj_status" />
                <col id="cItemActions" />
            </colgroup>
            <thead>
            <tr>
                {if $routeArea eq 'admin'}
                    <th id="hSelect" scope="col" align="center" valign="middle">
                        <input type="checkbox" id="toggleEntries" />
                    </th>
                {/if}
                <th id="hIp" scope="col" class="text-left">
                    <a href="{$sort.ip.url}" title="{gt text='Sort by %s' tag1='ip'}" class="{$sort.ip.class}">{gt text='Ip'}</a>
                </th>
                <th id="hName" scope="col" class="text-left">
                    <a href="{$sort.name.url}" title="{gt text='Sort by %s' tag1='name'}" class="{$sort.name.class}">{gt text='Name'}</a>
                </th>
                <th id="hEmail" scope="col" class="text-left">
                    <a href="{$sort.email.url}" title="{gt text='Sort by %s' tag1='email'}" class="{$sort.email.class}">{gt text='Email'}</a>
                </th>
                <th id="hHomepage" scope="col" class="text-left">
                    <a href="{$sort.homepage.url}" title="{gt text='Sort by %s' tag1='homepage'}" class="{$sort.homepage.class}">{gt text='Homepage'}</a>
                </th>
                <th id="hLocation" scope="col" class="text-left">
                    <a href="{$sort.location.url}" title="{gt text='Sort by %s' tag1='location'}" class="{$sort.location.class}">{gt text='Location'}</a>
                </th>
                <th id="hText" scope="col" class="text-left">
                    <a href="{$sort.text.url}" title="{gt text='Sort by %s' tag1='text'}" class="{$sort.text.class}">{gt text='Text'}</a>
                </th>
                <th id="hNotes" scope="col" class="text-left">
                    <a href="{$sort.notes.url}" title="{gt text='Sort by %s' tag1='notes'}" class="{$sort.notes.class}">{gt text='Notes'}</a>
                </th>
                <th id="hObj_status" scope="col" class="text-left">
                    <a href="{$sort.obj_status.url}" title="{gt text='Sort by %s' tag1='obj_status'}" class="{$sort.obj_status.class}">{gt text='Obj_status'}</a>
                </th>
                <th id="hItemActions" scope="col" class="z-order-unsorted">{gt text='Actions'}</th>
            </tr>
            </thead>
            <tbody>
        
        {foreach item='entry' from=$items}
            <tr>
                {if $routeArea eq 'admin'}
                    <td headers="hselect" align="center" valign="top">
                        <input type="checkbox" name="items[]" value="{$entry.id}" class="entries-checkbox" />
                    </td>
                {/if}
                <td headers="hIp" class="z-left">
                    {$entry.ip}
                </td>
                <td headers="hName" class="z-left">
                    <a href="{route name="mueternizermodule_entry_`$routeArea`display"  id=$entry.id}" title="{gt text='View detail page'}">{$entry.name|notifyfilters:'mueternizermodule.filterhook.entries'}</a>
                </td>
                <td headers="hEmail" class="z-left">
                    {if $entry.email ne ''}
                    <a href="mailto:{$entry.email}" title="{gt text='Send an email'}" class="fa fa-envelope"></a>
                    {else}&nbsp;{/if}
                </td>
                <td headers="hHomepage" class="z-left">
                    {if $entry.homepage ne ''}
                    <a href="{$entry.homepage}" title="{gt text='Visit this page'}" class="fa fa-external-link-square"></a>
                    {else}&nbsp;{/if}
                </td>
                <td headers="hLocation" class="z-left">
                    {$entry.location}
                </td>
                <td headers="hText" class="z-left">
                    {$entry.text}
                </td>
                <td headers="hNotes" class="z-left">
                    {$entry.notes}
                </td>
                <td headers="hObj_status" class="z-left">
                    {$entry.obj_status}
                </td>
                <td id="itemActions{$entry.id}" headers="hItemActions" class="actions nowrap z-w02">
                    {if count($entry._actions) gt 0}
                        <div class="dropdown">
                            <a id="itemActions{$entry.id}DropDownToggle" role="button" data-toggle="dropdown" data-target="#" href="javascript:void(0);" class="dropdown-toggle"><i class="fa fa-tasks"></i> <span class="caret"></span></a>
                            
                            <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="itemActions{$entry.id}DropDownToggle">
                                {foreach item='option' from=$entry._actions}
                                    <li role="presentation"><a href="{$option.url.type|mueternizermoduleActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}" role="menuitem" tabindex="-1" class="fa fa-{$option.icon}">{$option.linkText|safetext}</a></li>
                                    
                                {/foreach}
                            </ul>
                        </div>
                    {/if}
                </td>
            </tr>
        {foreachelse}
            <tr class="z-{if $routeArea eq 'admin'}admin{else}data{/if}tableempty">
              <td class="text-left" colspan="{if $routeArea eq 'admin'}10{else}9{/if}">
            {gt text='No entries found.'}
              </td>
            </tr>
        {/foreach}
        
            </tbody>
        </table>
        </div>
        
        {if !isset($showAllEntries) || $showAllEntries ne 1}
            {pager rowcount=$pager.numitems limit=$pager.itemsperpage display='page' route="mueternizermodule_entry_`$routeArea`view"}
        {/if}
    {if $routeArea eq 'admin'}
            <fieldset>
                <label for="mUEternizerModuleAction" class="col-sm-3 control-label">{gt text='With selected entries'}</label>
                <div class="col-sm-6">
                <select id="mUEternizerModuleAction" name="action" class="form-control input-sm">
                    <option value="">{gt text='Choose action'}</option>
                    <option value="delete" title="{gt text='Delete content permanently.'}">{gt text='Delete'}</option>
                </select>
                </div>
                <div class="col-sm-3">
                    <input type="submit" value="{gt text='Submit'}" class="btn btn-default btn-sm" />
                </div>
            </fieldset>
        </div>
    </form>
    {/if}

    
    {* here you can activate calling display hooks for the view page if you need it *}
    {*if $routeArea ne 'admin'}
        {notifydisplayhooks eventname='mueternizermodule.ui_hooks.entries.display_view' urlobject=$currentUrlObject assign='hooks'}
        {foreach key='providerArea' item='hook' from=$hooks}
            {$hook}
        {/foreach}
    {/if*}
</div>
{include file="`$area`/footer.tpl"}

<script type="text/javascript">
/* <![CDATA[ */
    ( function($) {
        $(document).ready(function() {
            $('.dropdown-toggle').dropdown();
            $('a.fa-zoom-in').attr('target', '_blank');
            {{if $routeArea eq 'admin'}}
                {{* init the "toggle all" functionality *}}
                if ($('#toggleEntries').length > 0) {
                    $('#toggleEntries').on('click', function (e) {
                        Zikula.toggleInput('entriesViewForm');
                        e.preventDefault();
                    });
                }
            {{/if}}
        });
    })(jQuery);
/* ]]> */
</script>
