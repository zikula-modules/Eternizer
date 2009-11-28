<!--[ $Id$ ]-->
<!--[insert name="getstatusmsg"]-->

<!--[pnform cssClass="z-form"]-->
<div>
    <!--[pnformvalidationsummary]-->
    <input type="hidden" name="plugin" value="<!--[$plugin]-->" />
    <fieldset>
        <div class="z-formrow">
            <!--[pnformlabel for='config' __text='Import configuration']-->
            <!--[pnformcheckbox id='config']-->
        </div>

        <div class="z-formrow">
            <!--[pnformlabel for='data' __text='Import data']-->
            <!--[pnformcheckbox id='data']-->
        </div>

        <div class="z-formrow">
            <!--[pnformlabel for='deactivate' __text='Deactivate module']-->
            <!--[pnformcheckbox id='deactivate']-->
        </div>

        <div class="z-formrow">
            <!--[pnformlabel for='delete' __text='Delete module']-->
            <!--[pnformcheckbox id='delete']-->
        </div>
    </fieldset>

    <div class="z-formbuttons">
        <!--[pnformbutton commandName="import" __text="Import"]-->
        <!--[pnformbutton commandName="cancel" __text="Cancel"]-->
    </div>
</div>
<!--[/pnform]-->