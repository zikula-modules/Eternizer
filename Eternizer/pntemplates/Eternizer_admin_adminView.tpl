<!--[include file="Eternizer_admin_menu.tpl"]-->

<!--[eternizerundo assign="undo"]-->
<!--[if $undo ne '']-->
<a href="<!--[$undo]-->"><!--[pnml name=_UNDO]--></a>
<!--[/if]-->

<form action="<!--[pnmodurl modname=Eternizer type=admin func=adminViewRedirect]-->" method="post">
  <dl>
    <!--[foreach from=$list item=item]-->
    <dt>
      <input type="checkbox" name="selected[<!--[$item.id]-->]" value="1" />
      [<!--[if $item.obj_status eq 'M']-->
      <a class="activate" href="<!--[pnmodurl modname=Eternizer type=admin func='changeStatus' status="A" id=$item.id goback=$goback]-->">A</a>
      <!--[else]-->
      <a class="moderate" href="<!--[pnmodurl modname=Eternizer type=admin func='changeStatus' status="M" id=$item.id goback=$goback]-->">M</a>
      <!--[/if]-->
      <!--[if $item.rights.modify]-->
      <a class="edit" href="<!--[pnmodurl modname=Eternizer type=admin func='modify' id=$item.id goback=$goback]-->">E</a>
      <!--[/if]-->
      <!--[if $item.rights.delete]-->
      <a class="delete" href="<!--[pnmodurl modname=Eternizer type=admin func='suppress' id=$item.id goback=$goback]-->">D</a>
      <!--[/if]-->]
      (<!--[$item.cr_date|pndate_format]-->) <!--[$item.profile[$config.titlefield]]-->
    </dt>
    <dd><!--[$item.text|truncate:200]--></dd>
    <!--[/foreach]-->
  </dl>
  <p><!--[pnml name=_ETERNIZER_SELECTED]-->:
    <input type="submit" name="action[activate]" value="<!--[pnml name="_ETERNIZER_ACTIVATE"]-->" />
    <input type="submit" name="action[moderate]" value="<!--[pnml name="_ETERNIZER_MODERATE"]-->" />
    <!--[if $rights.delete]-->
    <input type="submit" name="action[delete]" value="<!--[pnml name="_DELETE"]-->" />
    <!--[/if]-->
  </p>
</form>

<!--[include file="Eternizer_admin_footer.tpl"]-->