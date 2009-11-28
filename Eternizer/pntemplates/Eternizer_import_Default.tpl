<!--[insert name="getstatusmsg"]-->

<!--[pnform cssClass="pn-adminform"]-->

  <!--[pnformvalidationsummary]-->
  
  <input type="hidden" name="plugin" value="<!--[$plugin]-->" />

  <div class="pn-adminformrow">
    <!--[pnformlabel for='config' __text='Import configuration']-->
    <!--[pnformcheckbox id='config']-->
  </div>

  <div class="pn-adminformrow">
    <!--[pnformlabel for='data' __text='Import data']-->
    <!--[pnformcheckbox id='data']-->
  </div>

  <div class="pn-adminformrow">
    <!--[pnformlabel for='deactivate' __text='Deactivate module']-->
    <!--[pnformcheckbox id='deactivate']-->
  </div>

  <div class="pn-adminformrow">
    <!--[pnformlabel for='delete' __text='Delete module']-->
    <!--[pnformcheckbox id='delete']-->
  </div>

  <div class="pn-adminformbuttons">
    <!--[pnformbutton commandName="import" __text="Import"]-->
    <!--[pnformbutton commandName="cancel" __text="Cancel"]-->
  </div>

<!--[/pnform]-->