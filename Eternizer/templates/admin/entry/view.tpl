{* purpose of this template: entries view view in admin area *}
<div class="eternizer-entry eternizer-view">
    {include file='admin/header.tpl'}
    {gt text='Entry list' assign='templateTitle'}
    {pagesetvar name='title' value=$templateTitle}
    <div class="z-admin-content-pagetitle">
        {icon type='view' size='small' alt=$templateTitle}
        <h3>{$templateTitle}</h3>
    </div>

    {assign var='all' value=0}
    {if isset($showAllEntries) && $showAllEntries eq 1}
    {gt text='Back to paginated view' assign='linkTitle'}
    <p>
        <a href="{modurl modname='Eternizer' type='admin' func='view' ot='entry'}" title="{$linkTitle}" class="z-icon-es-view">{$linkTitle}</a>
    </p>
    {assign var='all' value=1}
    {else}
    {gt text='Show all entries' assign='linkTitle'}
    <p>
        <a href="{modurl modname='Eternizer' type='admin' func='view' ot='entry' all=1}" title="{$linkTitle}" class="z-icon-es-view">{$linkTitle}</a>
    </p>
    {/if}

    <table class="z-datatable">
        <colgroup>
            <col id="cip"/>
            <col id="cname"/>
            <col id="cemail"/>
            <col id="chomepage"/>
            <col id="clocation"/>
            <col id="ctext"/>
            <col id="cnotes"/>
            <col id="cobj_status"/>
            <col id="citemactions"/>
        </colgroup>
        <thead>
            <tr>
                <th id="hip" scope="col" class="z-left">
                    {sortlink __linktext='Ip' sort='ip' currentsort=$sort sortdir=$sdir all=$all modname='Eternizer' type='admin' func='view' ot='entry'}
                </th>
                <th id="hname" scope="col" class="z-left">
                    {sortlink __linktext='Name' sort='name' currentsort=$sort sortdir=$sdir all=$all modname='Eternizer' type='admin' func='view' ot='entry'}
                </th>
                <th id="hemail" scope="col" class="z-left">
                    {sortlink __linktext='Email' sort='email' currentsort=$sort sortdir=$sdir all=$all modname='Eternizer' type='admin' func='view' ot='entry'}
                </th>
                <th id="hhomepage" scope="col" class="z-left">
                    {sortlink __linktext='Homepage' sort='homepage' currentsort=$sort sortdir=$sdir all=$all modname='Eternizer' type='admin' func='view' ot='entry'}
                </th>
                <th id="hlocation" scope="col" class="z-left">
                    {sortlink __linktext='Location' sort='location' currentsort=$sort sortdir=$sdir all=$all modname='Eternizer' type='admin' func='view' ot='entry'}
                </th>
                <th id="htext" scope="col" class="z-left">
                    {sortlink __linktext='Text' sort='text' currentsort=$sort sortdir=$sdir all=$all modname='Eternizer' type='admin' func='view' ot='entry'}
                </th>
                <th id="hnotes" scope="col" class="z-left">
                    {sortlink __linktext='Notes' sort='notes' currentsort=$sort sortdir=$sdir all=$all modname='Eternizer' type='admin' func='view' ot='entry'}
                </th>
                <th id="hobj_status" scope="col" class="z-left">
                    {sortlink __linktext='Status' sort='obj_status' currentsort=$sort sortdir=$sdir all=$all modname='Eternizer' type='admin' func='view' ot='entry'}
                </th>
                <th id="hitemactions" scope="col" class="z-right z-order-unsorted">{gt text='Actions'}</th>
            </tr>
        </thead>

        <tbody>
            {foreach item='entry' from=$items}
            <tr class="{cycle values='z-odd, z-even'}">
                <td headers="hip">
                    {$entry.ip|notifyfilters:'eternizer.filterhook.entries'}
                </td>
                <td headers="hname">
                    {$entry.name|safetext}
                </td>
                <td headers="hemail">
                    {if $entry.email ne ''}
                    <a href="mailto:{$entry.email|safehtml}" title="{gt text='Send an email'}">{icon type='mail' size='extrasmall' __alt='Email'}</a>
                    {else}
                    &nbsp;
                    {/if}
                </td>
                <td headers="hhomepage">
                    {if $entry.homepage ne ''}
                    <a href="{$entry.homepage|safehtml}" title="{gt text='Visit this page'}">{icon type='url' size='extrasmall' __alt='Homepage'}</a>
                    {else}
                    &nbsp;
                    {/if}
                </td>
                <td headers="hlocation">
                    {$entry.location|safetext}
                </td>
                <td headers="htext">
                    {$entry.text|truncate:100|safehtml}
                </td>
                <td headers="hnotes">
                    {$entry.notes|safetext}
                </td>
                <td headers="hobj_status" class="z-left">
                    {if $entry.obj_status == 'A'}
                    {img src='extrasmall/greenled.png' modname='core' set='icons' __alt='confirmed' __title='confirmed'}
                    {elseif $entry.obj_status == 'M'}
                    {img src='extrasmall/yellowled.png' modname='core' set='icons' __alt='to moderate' __title='to moderate'}
                    {elseif $entry.obj_status == 'D'}
                    {img src='extrasmall/redled.png' modname='core' set='icons' __alt='denied' __title='denied'}
                    {/if}
                </td>
                <td headers="hitemactions" class="z-right z-nowrap">
                    {if count($entry._actions) gt 0}
                    {foreach item='option' from=$entry._actions}
                    <a href="{$option.url.type|eternizerActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}"{if $option.icon eq 'preview'} target="_blank"{/if}>{icon type=$option.icon size='extrasmall' alt=$option.linkText|safetext}</a>
                    {/foreach}
                    {/if}
                </td>
            </tr>
            {foreachelse}
            <tr class="z-datatableempty"><td colspan="9">{gt text='No entries found'}</td></tr>
            {/foreach}
        </tbody>
    </table>
    {if !isset($showAllEntries) || $showAllEntries ne 1}
    {pager rowcount=$pager.numitems limit=$pager.itemsperpage display='page'}
    {/if}
</div>
{include file='admin/footer.tpl'}