<!--[ $Id$ ]-->
<h2><!--[gt text="Installation of Eternizer" domain="module_eternizer"]--></h2>

<!--[insert name="getstatusmsg"]-->

<p class="z-informationmsg"><!--[gt text="All configuration is done. Thanks for using Eternizer." domain="module_eternizer"]--></p>
<form class="z-form" action="<!--[pnmodurl modname=Modules type=admin func=initialise]-->" method="post">
    <div>
        <input type="hidden" name="authid" value="<!--[$authid]-->" />

        <fieldset>
            <legend><!--[gt text="Last step" domain="module_eternizer"]--></legend>
            <div class="z-formrow">
                <label for="activate"><!--[gt text="Activate Eternizer after installation" domain="module_eternizer"]--></label>
                <input type="checkbox" id="activate" name="activate" />
            </div>
            <div class="z-formbuttons">
                <!--[pnbutton src='button_ok.gif' set='icons/small' __alt='Continue' __title='Continue']-->
            </div>
        </fieldset>
    </div>
</form>