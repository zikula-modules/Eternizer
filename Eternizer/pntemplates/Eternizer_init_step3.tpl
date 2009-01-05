<h2><!--[pnml name="_ETERNIZER_INIT"]--></h2>

<!--[insert name="getstatusmsg"]-->

<p style="text-align: center">
    <!--[pnml name="_ETERNIZER_INIT_STEP3" html="1"]--><br /><br />
    <!--[pnml name="_ETERNIZER_INIT_THANKS" html="1"]--><br /><br />
    <form action="<!--[pnmodurl modname=Modules type=admin func=initialise]-->" method="post">
      <input type="hidden" name="authid" value="<!--[$authid]-->" />
      <input type="checkbox" id="activate" name="activate" />
      <label for="activate"><!--[pnml name=_ETERNIZER_INIT_ACTIVATE]--></label><br />
      <input type="submit" value="<!--[pnml name=_CONTINUE]-->" />
    </form>
</p>