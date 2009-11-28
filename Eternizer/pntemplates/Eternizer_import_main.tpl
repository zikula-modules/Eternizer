<!--[ $Id$ ]-->

<h2><!--[gt text="Eternizer Import"]--></h2>
<!--[if $books]-->
<form class="z-form" action="<!--[pnmodurl modname="Eternizer" type="import" func="display"]-->" method="post">
    <fieldset>
        <legend><!--[gt text="Import from" domain="module_eternizer"]--></legend>

        <div class="z-formrow">
            <label><!--[gt text="Choose module" domain="module_eternizer"]--></label>
            <!--[foreach from=$books key="name" item="name"]-->
            <div><input type="radio" name="plugin" value="<!--[$name]-->" /> <!--[$name]--> </div>
            <!--[/foreach]-->
        </div>
    </fieldset>

    <div class="z-formbuttons">
        <input type="submit" value="<!--[gt text='Continue' domain="module_eternizer"]-->" />
    </div>
</form>
<!--[else]-->
<p class="z-warningmsg"><!--[gt text="No guestbook module found" domain="module_eternizer"]--></p>
<!--[/if]-->