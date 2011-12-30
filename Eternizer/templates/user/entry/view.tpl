{* purpose of this template: entries view view in user area *}
<div class="eternizer-entry eternizer-view">
{include file='user/header.tpl'}
{gt text='Entry list' assign='templateTitle'}
{pagesetvar name='title' value=$templateTitle}
<div class="z-frontendcontainer">
    <h2>{$templateTitle}</h2>


    {checkpermissionblock component='Eternizer::' instance='.*' level="ACCESS_ADD"}
        {gt text='Create entry' assign='createTitle'}
        <a href="{modurl modname='Eternizer' type='user' func='edit' ot='entry'}" title="{$createTitle}" class="z-icon-es-add">
            {$createTitle}
        </a>
    {/checkpermissionblock}

    {if isset($showAllEntries) && $showAllEntries eq 1}
        {gt text='Back to paginated view' assign='linkTitle'}
        <a href="{modurl modname='Eternizer' type='user' func='view' ot='entry'}" title="{$linkTitle}" class="z-icon-es-view">
            {$linkTitle}
        </a>
    {else}
        {gt text='Show all entries' assign='linkTitle'}
        <a href="{modurl modname='Eternizer' type='user' func='view' ot='entry' all=1}" title="{$linkTitle}" class="z-icon-es-view">
            {$linkTitle}
        </a>
    {/if}

<table class="z-datatable">
    <colgroup>
        <col id="cip" />
        <col id="ctext" />
        <col id="cnotes" />
        <col id="cintactions" />
    </colgroup>
    <thead>
    <tr>
        <th id="hip" scope="col" align="left" valign="middle">
            {sortlink __linktext='Ip' sort='ip' currentsort=$sort sortdir=$sdir modname='Eternizer' type='user' func='view' ot='entry'}
        </th>
        <th id="htext" scope="col" align="left" valign="middle">
            {sortlink __linktext='Text' sort='text' currentsort=$sort sortdir=$sdir modname='Eternizer' type='user' func='view' ot='entry'}
        </th>
        <th id="hnotes" scope="col" align="left" valign="middle">
            {sortlink __linktext='Notes' sort='notes' currentsort=$sort sortdir=$sdir modname='Eternizer' type='user' func='view' ot='entry'}
        </th>
        <th id="hintactions" scope="col" align="left" valign="middle" class="z-wrap z-order-unsorted">{gt text='Actions'}</th>
    </tr>
    </thead>
    <tbody>

    {foreach item='entry' from=$items}
    <tr class="{cycle values='z-odd, z-even'}">
        <td headers="hip" align="left" valign="top">
            {$entry.ip|notifyfilters:'eternizer.filterhook.entries'}
        </td>
        <td headers="htext" align="left" valign="top">
            {$entry.text}
        </td>
        <td headers="hnotes" align="left" valign="top">
            {$entry.notes}
        </td>
        <td headers="hintactions" align="left" valign="top" style="white-space: nowrap">
            <a href="{modurl modname='Eternizer' type='user' func='display' ot='entry' id=$entry.id}" title="{$entry.ip|replace:"\"":""}">
                {icon type='display' size='extrasmall' __alt='Details'}
            </a>
    {checkpermissionblock component='Eternizer::' instance='.*' level='ACCESS_EDIT'}
            <a href="{modurl modname='Eternizer' type='user' func='edit' ot='entry' id=$entry.id}" title="{gt text='Edit'}">
                {icon type='edit' size='extrasmall' __alt='Edit'}
            </a>
            <a href="{modurl modname='Eternizer' type='user' func='edit' ot='entry' astemplate=$entry.id}" title="{gt text='Reuse for new item'}">
                {icon type='saveas' size='extrasmall' __alt='Reuse'}
            </a>
    {/checkpermissionblock}
        </td>
    </tr>
    {foreachelse}
        <tr class="z-datatableempty">
          <td align="left" valign="top" colspan="4">
            {gt text='No entries found.'}
          </td>
        </tr>
    {/foreach}

    </tbody>
</table>

    {if !isset($showAllEntries) || $showAllEntries ne 1}
        {pager rowcount=$pager.numitems limit=$pager.itemsperpage display='page'}
    {/if}

    {notifydisplayhooks eventname='eternizer.ui_hooks.entries.display_view' urlobject=$currentUrlObject assign='hooks'}
    {foreach key='hookname' item='hook' from=$hooks}
        {$hook}
    {/foreach}
</div>
</div>
{include file='user/footer.tpl'}

