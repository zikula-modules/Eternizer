<h2><!--[gt text="Installation of Eternizer"]--></h2>

<!--[insert name="getstatusmsg"]-->

<p style="text-align: center">
    <!--[gt text="Last step"]--><br /><br />
    <!--[gt text="All configuration is done. Thanks for using Eternizer"]--><br /><br />
    <form action="<!--[pnmodurl modname=Modules type=admin func=initialise]-->" method="post">
      <input type="hidden" name="authid" value="<!--[$authid]-->" />
      <input type="checkbox" id="activate" name="activate" />
      <label for="activate"><!--[gt text="Activate Eternizer after installation"]--></label><br />
      <input type="submit" value="<!--[gt text="Continue"]-->" />
    </form>
</p>