{* purpose of this template: entries delete confirmation view *}
{assign var='area' value='User'}
{if $routeArea eq 'admin'}
    {assign var='area' value='Admin'}
{/if}
{include file="`$area`/header.tpl"}
<div class="mueternizermodule-entry mueternizermodule-delete">
    {gt text='Delete entry' assign='templateTitle'}
    {pagesetvar name='title' value=$templateTitle}
    {if $routeArea eq 'admin'}
        <h3>
            <span class="fa fa-trash-o"></span>
            {$templateTitle}
        </h3>
    {else}
        <h2>{$templateTitle}</h2>
    {/if}

    <p class="alert alert-warningmsg">{gt text='Do you really want to delete this entry ?'}</p>

    <form class="form-horizontal" action="{route name="mueternizermodule_entry_`$routeArea`delete"  id=$entry.id}" method="post" role="form">
        <div>
            <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
            <input type="hidden" id="confirmation" name="confirmation" value="1" />
            <fieldset>
                <legend>{gt text='Confirmation prompt'}</legend>
                <div class="form-group form-buttons">
                <div class="col-sm-offset-3 col-sm-9">
                    {gt text='Delete' assign='deleteTitle'}
                    {button src='14_layer_deletelayer.png' set='icons/small' text=$deleteTitle title=$deleteTitle class='btn btn-danger'}
                    <a href="{route name="mueternizermodule_entry_`$routeArea`view"}" class="btn btn-default" role="button"><span class="fa fa-times"></span> {gt text='Cancel'}</a>
                </div>
                </div>
            </fieldset>

            {notifydisplayhooks eventname='mueternizermodule.ui_hooks.entries.form_delete' id="`$entry.id`" assign='hooks'}
            {foreach key='providerArea' item='hook' from=$hooks}
            <fieldset>
                <legend>{$hookName}</legend>
                {$hook}
            </fieldset>
            {/foreach}
        </div>
    </form>
</div>
{include file="`$area`/footer.tpl"}
