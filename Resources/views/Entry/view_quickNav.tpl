{* purpose of this template: entries view filter form *}
{checkpermissionblock component='MUEternizerModule:Entry:' instance='::' level='ACCESS_EDIT'}
{assign var='objectType' value='entry'}
<form action="{route name="mueternizermodule_entry_`$routeArea`view"}" method="get" id="mUEternizerModuleEntryQuickNavForm" class="mueternizermodule-quicknav {*form-inline*}navbar-form" role="navigation">
    <fieldset>
        <h3>{gt text='Quick navigation'}</h3>
        <input type="hidden" name="all" value="{$all|default:0}" />
        <input type="hidden" name="own" value="{$own|default:0}" />
        {gt text='All' assign='lblDefault'}
        {if !isset($workflowStateFilter) || $workflowStateFilter eq true}
            <div class="form-group">
                <label for="workflowState">{gt text='Workflow state'}</label>
                <select id="workflowState" name="workflowState" class="form-control input-sm">
                    <option value="">{$lblDefault}</option>
                {foreach item='option' from=$workflowStateItems}
                <option value="{$option.value}"{if $option.title ne ''} title="{$option.title|safetext}"{/if}{if $option.value eq $workflowState} selected="selected"{/if}>{$option.text|safetext}</option>
                {/foreach}
                </select>
            </div>
        {/if}
        {if !isset($searchFilter) || $searchFilter eq true}
            <div class="form-group">
                <label for="searchTerm">{gt text='Search'}</label>
                <input type="text" id="searchTerm" name="q" value="{$q}" class="form-control input-sm" />
            </div>
        {/if}
        {if !isset($sorting) || $sorting eq true}
            <div class="form-group">
                <label for="sortBy">{gt text='Sort by'}</label>
                &nbsp;
                <select id="sortBy" name="sort" class="form-control input-sm">
                    <option value="id"{if $sort eq 'id'} selected="selected"{/if}>{gt text='Id'}</option>
                    <option value="ip"{if $sort eq 'ip'} selected="selected"{/if}>{gt text='Ip'}</option>
                    <option value="name"{if $sort eq 'name'} selected="selected"{/if}>{gt text='Name'}</option>
                    <option value="email"{if $sort eq 'email'} selected="selected"{/if}>{gt text='Email'}</option>
                    <option value="homepage"{if $sort eq 'homepage'} selected="selected"{/if}>{gt text='Homepage'}</option>
                    <option value="location"{if $sort eq 'location'} selected="selected"{/if}>{gt text='Location'}</option>
                    <option value="text"{if $sort eq 'text'} selected="selected"{/if}>{gt text='Text'}</option>
                    <option value="notes"{if $sort eq 'notes'} selected="selected"{/if}>{gt text='Notes'}</option>
                    <option value="obj_status"{if $sort eq 'obj_status'} selected="selected"{/if}>{gt text='Obj_status'}</option>
                    <option value="createdDate"{if $sort eq 'createdDate'} selected="selected"{/if}>{gt text='Creation date'}</option>
                    <option value="createdUserId"{if $sort eq 'createdUserId'} selected="selected"{/if}>{gt text='Creator'}</option>
                    <option value="updatedDate"{if $sort eq 'updatedDate'} selected="selected"{/if}>{gt text='Update date'}</option>
                </select>
            </div>
            <div class="form-group">
                <select id="sortDir" name="sortdir" class="form-control input-sm">
                    <option value="asc"{if $sdir eq 'asc'} selected="selected"{/if}>{gt text='ascending'}</option>
                    <option value="desc"{if $sdir eq 'desc'} selected="selected"{/if}>{gt text='descending'}</option>
                </select>
            </div>
        {else}
            <input type="hidden" name="sort" value="{$sort}" />
            <input type="hidden" name="sdir" value="{if $sdir eq 'desc'}asc{else}desc{/if}" />
        {/if}
        {if !isset($pageSizeSelector) || $pageSizeSelector eq true}
            <div class="form-group">
                <label for="num">{gt text='Page size'}</label>
                <select id="num" name="num" class="form-control input-sm" style="min-width: 70px">
                    <option value="5"{if $pageSize eq 5} selected="selected"{/if}>5</option>
                    <option value="10"{if $pageSize eq 10} selected="selected"{/if}>10</option>
                    <option value="15"{if $pageSize eq 15} selected="selected"{/if}>15</option>
                    <option value="20"{if $pageSize eq 20} selected="selected"{/if}>20</option>
                    <option value="30"{if $pageSize eq 30} selected="selected"{/if}>30</option>
                    <option value="50"{if $pageSize eq 50} selected="selected"{/if}>50</option>
                    <option value="100"{if $pageSize eq 100} selected="selected"{/if}>100</option>
                </select>
            </div>
        {/if}
        <input type="submit" name="updateview" id="quicknavSubmit" value="{gt text='OK'}" class="btn btn-default btn-sm" />
    </fieldset>
</form>

<script type="text/javascript">
/* <![CDATA[ */
    ( function($) {
        $(document).ready(function() {
            mUEternizerInitQuickNavigation('entry');
            {{if isset($searchFilter) && $searchFilter eq false}}
                {{* we can hide the submit button if we have no quick search field *}}
                $('#quicknavSubmit').addClass('hidden');
            {{/if}}
        });
    })(jQuery);
/* ]]> */
</script>
{/checkpermissionblock}
