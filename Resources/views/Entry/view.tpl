{* purpose of this template: entries list view *}
{assign var='lct' value='user'}
{if isset($smarty.get.lct) && $smarty.get.lct eq 'admin'}
    {assign var='lct' value='admin'}
{/if}
{assign var='lctUc' value=$lct|ucfirst}
{include file="`$lctUc`/header.tpl"}
<div class="mueternizermodule-entry mueternizermodule-view">
    {gt text='Entry list' assign='templateTitle'}
    {pagesetvar name='title' value=$templateTitle}
    {if $lct eq 'admin'}
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
            <a href="{route name='mueternizermodule_entry_edit' lct=$lct}" title="{$createTitle}" class="fa fa-plus">{$createTitle}</a>
        {/checkpermissionblock}
    {/if}
    {assign var='own' value=0}
    {if isset($showOwnEntries) && $showOwnEntries eq 1}
        {assign var='own' value=1}
    {/if}
    {assign var='all' value=0}
    {if isset($showAllEntries) && $showAllEntries eq 1}
        {gt text='Back to paginated view' assign='linkTitle'}
        <a href="{route name='mueternizermodule_entry_view' lct=$lct}" title="{$linkTitle}" class="fa fa-table">{$linkTitle}</a>
        {assign var='all' value=1}
    {else}
        {gt text='Show all entries' assign='linkTitle'}
        <a href="{route name='mueternizermodule_entry_view' lct=$lct all=1}" title="{$linkTitle}" class="fa fa-table">{$linkTitle}</a>
    {/if}

    {include file='Entry/view_quickNav.tpl' all=$all own=$own workflowStateFilter=false}{* see template file for available options *}

    {if $lct eq 'admin'}
    <form action="{route name='mueternizermodule_entry_handleselectedentries' lct=$lct}" method="post" id="entriesViewForm" class="form-horizontal" role="form">
        <div>
            <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
    {/if}
    {if $lct eq 'admin'}
        <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover table-condensed">
            <colgroup>             
                <col id="cSelect" />
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
                <th id="hSelect" scope="col" align="center" valign="middle">
                    <input type="checkbox" id="toggleEntries" />
                </th>
                <th id="hIp" scope="col" class="text-left">
                    {sortlink __linktext='Ip' currentsort=$sort modname='MUEternizerModule' type='entry' func='view' sort='ip' sortdir=$sdir all=$all own=$own workflowState=$workflowState q=$q pageSize=$pageSize lct=$lct}
                </th>
                <th id="hName" scope="col" class="text-left">
                    {sortlink __linktext='Name' currentsort=$sort modname='MUEternizerModule' type='entry' func='view' sort='name' sortdir=$sdir all=$all own=$own workflowState=$workflowState q=$q pageSize=$pageSize lct=$lct}
                </th>
                <th id="hEmail" scope="col" class="text-left">
                    {sortlink __linktext='Email' currentsort=$sort modname='MUEternizerModule' type='entry' func='view' sort='email' sortdir=$sdir all=$all own=$own workflowState=$workflowState q=$q pageSize=$pageSize lct=$lct}
                </th>
                <th id="hHomepage" scope="col" class="text-left">
                    {sortlink __linktext='Homepage' currentsort=$sort modname='MUEternizerModule' type='entry' func='view' sort='homepage' sortdir=$sdir all=$all own=$own workflowState=$workflowState q=$q pageSize=$pageSize lct=$lct}
                </th>
                <th id="hLocation" scope="col" class="text-left">
                    {sortlink __linktext='Location' currentsort=$sort modname='MUEternizerModule' type='entry' func='view' sort='location' sortdir=$sdir all=$all own=$own workflowState=$workflowState q=$q pageSize=$pageSize lct=$lct}
                </th>
                <th id="hText" scope="col" class="text-left">
                    {sortlink __linktext='Text' currentsort=$sort modname='MUEternizerModule' type='entry' func='view' sort='text' sortdir=$sdir all=$all own=$own workflowState=$workflowState q=$q pageSize=$pageSize lct=$lct}
                </th>
                <th id="hNotes" scope="col" class="text-left">
                    {sortlink __linktext='Notes' currentsort=$sort modname='MUEternizerModule' type='entry' func='view' sort='notes' sortdir=$sdir all=$all own=$own workflowState=$workflowState q=$q pageSize=$pageSize lct=$lct}
                </th>
                <th id="hObj_status" scope="col" class="text-left">
                    {sortlink __linktext='Obj_status' currentsort=$sort modname='MUEternizerModule' type='entry' func='view' sort='obj_status' sortdir=$sdir all=$all own=$own workflowState=$workflowState q=$q pageSize=$pageSize lct=$lct}
                </th>
                <th id="hItemActions" scope="col" class="z-order-unsorted">{gt text='Actions'}</th>
            </tr>
            </thead>
            <tbody>
        
        {foreach item='entry' from=$items}
            <tr>
                <td headers="hselect" align="center" valign="top">
                    <input type="checkbox" name="items[]" value="{$entry.id}" class="entries-checkbox" />
                </td>
                <td headers="hIp" class="z-left">
                    {$entry.ip}
                </td>
                <td headers="hName" class="z-left">
                    <a href="{route name='mueternizermodule_entry_display'  id=$entry.id lct=$lct}" title="{gt text='View detail page'}">{$entry.name|notifyfilters:'mueternizermodule.filterhook.entries'}</a>
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
            <tr class="z-{if $lct eq 'admin'}admin{else}data{/if}tableempty">
              <td class="text-left" colspan="{if $lct eq 'admin'}10{else}9{/if}">
            {gt text='No entries found.'}
              </td>
            </tr>
        {/foreach}
        
            </tbody>
        </table>
        </div>
        

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
    {if $lct eq 'user'}
        {if $formposition eq 'above'}
        {modfunc modname='MUEternizerModule' type='entry' func='editAction'}
        {notifydisplayhooks eventname='mueternizermodule.ui_hooks.entries' urlobject=$currentUrlObject assign='hooks'}
        {foreach key='hookname' item='hook' from=$hooks}
            {$hook}
        {/foreach}
    	{/if} 
    {foreach item='entry' from=$items}
    <div id="eternizer">
        <div class="etz_entry z-clearfix {cycle values='etz_bg1,etz_bg2'}">
            <div class="etz_author">
                <div class="etz_avatar">{useravatar uid=$entry.createdUserId}</div>
                {if $entry.email ne '' || $entry.homepage ne '' || $entry.location ne ''}
                    <dl class="etz_options">
                        {if $entry.email ne ''}
                            <dt>{gt text='Email'}</dt>
                            <dd>
                                <a href="mailto:{$entry.email|safehtml}" title="{gt text='Send an email'}">{icon type='mail' size='extrasmall' __alt='Email'}</a>
                            </dd>
                        {/if}
                        {if $entry.homepage ne ''}
                            <dt>{gt text='Homepage'}</dt>
                            <dd>
                                <a href="{$entry.homepage|safehtml}" title="{gt text='Visit this page'}">{icon type='url' size='extrasmall' __alt='Homepage'}</a>
                            </dd>
                        {/if}
                        {if $entry.location ne ''}
                            <dt>{gt text='Location'}</dt>
                            <dd>{$entry.location|safehtml}</dd>
                        {/if}
                    </dl>
                {/if}
            </div>
            <div class="etz_body">
                <div class="etz_info">
                    <div class="etz_title">
                        <div class="etz_name">
                            {if $entry.name ne ''}
                                <span class="etz_attr">{$entry.name|safehtml}</span>
                                {else}
                                {if $entry.createdUserId eq 0}
                                    {modgetvar module='Users' name='anonymous' assign='guest'}
                                    {$guest|safehtml}
                                    {else}
                                    {usergetvar name='uname' uid=$entry.createdUserId assign='uname'}
                                    {if $uname ne ''}
                                        {$uname|safehtml}
                                        {else}
                                        {$guest|safehtml}
                                    {/if}
                                {/if}
                            {/if}
                            {gt text='on %s' tag1=$entry.createdDate|dateformat:datetimelong}
                        </div>
                    </div>
                    <div class="etz_action">
                        {if $entry.createdUserId eq $userid && $coredata.logged_in eq true && $modvars.MUEternizerModule.editentries eq true}
                            <div class="eternizer-user-entry-edit">{$entry.id|mueternizermoduleGetStateOfEditOfEntry}</div>
                        {/if}
                    </div>
                </div>
                <div class="etz_content">
                    {$entry.text|notifyfilters:'eternizer.filter_hooks.entries.filter'|safehtml|nl2br}
                    {if $entry.notes ne ''}
                        {if $entry.updatedUserId eq $entry.createdUserId}
                        <p style="margin-top: 2em;" class="entry-comment">
                            <strong class="entry-comment-label">{gt text="Comment:"}</strong>
                            <br/>
                            {$entry.notes|safehtml}
                        </p>
                        {/if}
                    {/if}
                    {if $entry.updatedDate > $entry.createdDate && $entry.createdUserId eq $entry.updatedUserId}
                        <br/>
                        <span class="etz_updated">{gt text='Updated on %s' tag1=$entry.updatedDate|dateformat:datetimelong}</span>
                    {/if}
                </div>
            </div>
        </div>
        </div>
        {foreachelse}
        <tr class="z-datatableempty">
            <td class="z-left" colspan="9">{gt text='No entries found.'}</td>
        </tr>
    {/foreach}

    {if $formposition eq 'below'}
        {modfunc modname='MUEternizerModule' type='entry' func='edit'}
        {notifydisplayhooks eventname='eternizer.ui_hooks.entries.display_view' urlobject=$currentUrlObject assign='hooks'}
        {foreach key='hookname' item='hook' from=$hooks}
            {$hook}
        {/foreach}
    {/if}    
    {/if}
    
    {if !isset($showAllEntries) || $showAllEntries ne 1}
        {pager rowcount=$pager.numitems limit=$pager.itemsperpage display='page' lct=$lct route='mueternizermodule_entry_view'}
    {/if}    
    {* here you can activate calling display hooks for the view page if you need it *}
    {*if $lct ne 'admin'}
        {notifydisplayhooks eventname='mueternizermodule.ui_hooks.entries.display_view' urlobject=$currentUrlObject assign='hooks'}
        {foreach key='providerArea' item='hook' from=$hooks}
            {$hook}
        {/foreach}
    {/if*}
</div>
{include file="`$lctUc`/footer.tpl"}

<script type="text/javascript">
/* <![CDATA[ */
    ( function($) {
        $(document).ready(function() {
            $('.dropdown-toggle').dropdown();
            $('a.fa-zoom-in').attr('target', '_blank');
            {{if $lct eq 'admin'}}
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
