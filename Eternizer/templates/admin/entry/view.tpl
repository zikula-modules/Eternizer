{* purpose of this template: entries view view in admin area *}
<div class="eternizer-entry eternizer-view">
{include file='admin/header.tpl'}
{gt text='Entry list' assign='templateTitle'}
{pagesetvar name='title' value=$templateTitle}
<div class="z-admin-content-pagetitle">
    {icon type='view' size='small' alt=$templateTitle}
    <h3>{$templateTitle}</h3>
</div>


    {checkpermissionblock component='Eternizer::' instance='.*' level="ACCESS_ADD"}
        {gt text='Create entry' assign='createTitle'}
        <a href="{modurl modname='Eternizer' type='admin' func='edit' ot='entry'}" title="{$createTitle}" class="z-icon-es-add">
            {$createTitle}
        </a>
    {/checkpermissionblock}

    {assign var='all' value=0}
    {if isset($showAllEntries) && $showAllEntries eq 1}
        {gt text='Back to paginated view' assign='linkTitle'}
        <a href="{modurl modname='Eternizer' type='admin' func='view' ot='entry'}" title="{$linkTitle}" class="z-icon-es-view">
            {$linkTitle}
        </a>
        {assign var='all' value=1}
    {else}
        {gt text='Show all entries' assign='linkTitle'}
        <a href="{modurl modname='Eternizer' type='admin' func='view' ot='entry' all=1}" title="{$linkTitle}" class="z-icon-es-view">
            {$linkTitle}
        </a>
    {/if}

<table class="z-datatable">
    <colgroup>
        <col id="cip" />
        <col id="ctext" />
        <col id="cnotes" />
        <col id="cobj_status" />
        <col id="citemactions" />
    </colgroup>
    <thead>
    <tr>
        <th id="hip" scope="col" class="z-left">
            {sortlink __linktext='Ip' sort='ip' currentsort=$sort sortdir=$sdir all=$all modname='Eternizer' type='admin' func='view' ot='entry'}
        </th>
        <th id="htext" scope="col" class="z-left">
            {sortlink __linktext='Text' sort='text' currentsort=$sort sortdir=$sdir all=$all modname='Eternizer' type='admin' func='view' ot='entry'}
        </th>
        <th id="hnotes" scope="col" class="z-left">
            {sortlink __linktext='Notes' sort='notes' currentsort=$sort sortdir=$sdir all=$all modname='Eternizer' type='admin' func='view' ot='entry'}
        </th>
        <th id="hobj_status" scope="col" class="z-left">
            {sortlink __linktext='Obj_status' sort='obj_status' currentsort=$sort sortdir=$sdir all=$all modname='Eternizer' type='admin' func='view' ot='entry'}
        </th>
        <th id="hitemactions" scope="col" class="z-right z-order-unsorted">{gt text='Actions'}</th>
    </tr>
    </thead>
    <tbody>

    {foreach item='entry' from=$items}
    <tr class="{cycle values='z-odd, z-even'}">
        <td headers="hip" class="z-left">
            {$entry.ip|notifyfilters:'eternizer.filterhook.entries'}
        </td>
        <td headers="htext" class="z-left">
            {$entry.text}
        </td>
        <td headers="hnotes" class="z-left">
            {$entry.notes}
        </td>
        <td headers="hobj_status" class="z-left">
            {$entry.obj_status}
        </td>
        <td headers="hitemactions" class="z-right z-nowrap z-w02">
            {if count($entry._actions) gt 0}
            {strip}
                {foreach item='option' from=$entry._actions}
                    <a href="{$option.url.type|eternizerActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}"{if $option.icon eq 'preview'} target="_blank"{/if}>
                        {icon type=$option.icon size='extrasmall' alt=$option.linkText|safetext}
                    </a>
                {/foreach}
            {/strip}
            {/if}
        </td>
    </tr>
    {foreachelse}
        <tr class="z-admintableempty">
          <td class="z-left" colspan="5">
            {gt text='No entries found.'}
          </td>
        </tr>
    {/foreach}

    </tbody>
</table>

    {if !isset($showAllEntries) || $showAllEntries ne 1}
        {pager rowcount=$pager.numitems limit=$pager.itemsperpage display='page'}
    {/if}
</div>
{include file='admin/footer.tpl'}

