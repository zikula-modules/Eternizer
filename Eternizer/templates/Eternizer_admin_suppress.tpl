{* $Id$ *}
{include file="Eternizer_admin_menu.tpl"}
<h2>{gt text="Delete entry"}</h2>
<p class="z-warningmsg">{gt text="Do you really want to delete this guestbook entry?"}</p>
<form class="z-form" action="{modurl modname="Eternizer" type="admin" func="delete"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
        <input type="hidden" name="id" value="{$id|safetext}" />
        <input type="hidden" name="goback" value="{$goback|safetext}" />
        <fieldset>
            <legend>{gt text="Confirmation prompt"}</legend>
            <div class="z-formbuttons">
                {button src='button_ok.png' set='icons/small' __alt='Delete' __title='Delete'}
                <a href="{$cancelurl}">{img modname='core' src='button_cancel.png' set='icons/small' __alt='Cancel' __title='Cancel'}</a>
            </div>
        </fieldset>
    </div>
</form>
{include file="Eternizer_admin_footer.tpl"}