<!--[pnajaxheader filename=profilelist.js dragdrop=1]-->

<script type="text/javascript">
  var eternizer_deleteconfirm = '<!--[pnml name=_CONFIRMDELETE]-->';
</script>

<!--[if $init]-->
<!--[modulestylesheet modname="Admin" stylesheet="admin.css"]-->
<!--[pnpageaddvar name="stylesheet" value="modules/Eternizer/pnstyle/linkicons_small.css"]-->
<!--[pnpageaddvar name="stylesheet" value="modules/Eternizer/pnstyle/style.css"]-->
<div id="eternizer" class="pn-admincontainer">
<h2><!--[pnml name="_ETERNIZER_INIT"]--></h2>
<!--[pnml name="_ETERNIZER_INIT_STEP2"]-->
<!--[else]-->
<!--[include file="Eternizer_admin_menu.tpl"]-->
<!--[/if]-->

<!--[insert name="getstatusmsg"]-->

<!--[pnform cssClass="pn-adminform"]-->

  <!--[pnformvalidationsummary]-->

  <!--[pnformtabbedpanelset]-->

  <!--[pnformtabbedpanel title=_ETERNIZER_CONFIG_DEFAULT_TITLE]-->
  <div class="pn-adminformrow">
    <!--[pnformlabel for=perpage text=_ETERNIZER_PERPAGE]-->
    <!--[pnformintinput id=perpage mandatory=1 minValue=1]-->
  </div>

  <div class="pn-adminformrow">
    <!--[pnformlabel for=order text=_ETERNIZER_ORDER]-->
    <!--[pnformdropdownlist id=order mandatory=1]-->
  </div>

  <div class="pn-adminformrow">
    <!--[pnformlabel for=spammode text=_ETERNIZER_SPAMCHECK]-->
    <!--[pnformdropdownlist id=spammode mandatory=1]-->
  </div>

  <div class="pn-adminformrow">
    <!--[pnformlabel for=moderate text=_ETERNIZER_MODERATE]-->
    <!--[pnformdropdownlist id=moderate]-->
  </div>

  <div class="pn-adminformrow">
    <!--[pnformlabel for=useuserprofile text=_ETERNIZER_USEUSERPROFILE]-->
    <!--[pnformcheckbox id=useuserprofile]-->
  </div>
  <!--[/pnformtabbedpanel]-->

  <!--[pnformtabbedpanel title=_ETERNIZER_CONFIG_WORDWRAP_TITLE]-->
  <div class="pn-adminformrow">
    <!--[pnformlabel for=wwaction text=_ETERNIZER_WWACTION]-->
    <!--[pnformdropdownlist id=wwaction]-->
  </div>

  <div class="pn-adminformrow">
    <!--[pnformlabel for=wwlimit text=_ETERNIZER_WWLIMIT]-->
    <!--[pnformintinput id=wwlimit]-->
  </div>

  <div class="pn-adminformrow">
    <!--[pnformlabel for=wwshortto text=_ETERNIZER_WWSHORTTO]-->
    <!--[pnformintinput id=wwshortto]-->
  </div>
  <!--[/pnformtabbedpanel]-->

  <!--[pnformtabbedpanel title="_ETERNIZER_CONFIG_PROFILE_TITLE"]-->
  <div class="pn-adminformrow">
    <!--[pnformlabel for=titlefield text=_ETERNIZER_PROFILE_TITLEFIELD]-->
    <!--[pnformdropdownlist id=titlefield]-->
  </div>
  <ul id="profiletable">
    <li class="profileheader pn-clearfix">
      <span class="col1">&nbsp;</span>
      <span class="col2"><!--[pnml name=_ETERNIZER_ID]--></span>
      <span class="col3"><!--[pnml name=_TITLE]--></span>
      <span class="col4"><!--[pnml name=_ETERNIZER_TYPE]--></span>
      <span class="col5"><!--[pnml name=_ETERNIZER_MANDATORY]--></span>
      <span class="col6"><!--[pnml name=_ETERNIZER_PROFILELINK]--></span>
      <span class="col7"><!--[pnml name=_ACTION]--></span>
    </li>
    <!--[foreach from=$profile key="id" item="item"]-->
    <li id="profile_<!--[$id]-->" class="<!--[cycle values="pn-odd,pn-even"]--> pn-sortable pn-clearfix moveable">
      <span class="col1 move"><!--[pnml name=_MOVE]--></span>
      <span class="col2"><!--[$id]--></span>
      <!--[pnformtextinput cssClass="pn-hide" id=profile_`$id`_pos group="profile" maxLength=20]-->
      <!--[pnformtextinput cssClass="col3" id=profile_`$id`_title group="profile" text=$item.title maxLength=255]-->
      <!--[pnformdropdownlist cssClass="col4" id=profile_`$id`_type group="profile" selectedValue=$item.type items=$typeItems]-->
      <!--[pnformdropdownlist cssClass="col5" id=profile_`$id`_mandatory group="profile" selectedValue=$item.mandatory items=$mandatoryItems]-->
      <!--[pnformdropdownlist cssClass="col6" id=profile_`$id`_profile group="profile" selectedValue=$item.profile items=$profileItems]-->
      <span class="col7">
        <a href="javascript:eternizer_profile_delete(<!--[$id]-->);" class="deleterow">D</a>
      </span>
    </li>
    <!--[/foreach]-->
    <li id="profile_n1" class="<!--[cycle values="pn-odd,pn-even"]--> pn-sortable pn-clearfix moveable">
      <span class="col1 move"><!--[pnml name=_MOVE]--></span>
      <span class="col2"><!--[pnml name=_NEW]--></span>
      <!--[pnformtextinput cssClass="pn-hide" id=profile_n1_pos group="profile" maxLength=20]-->
      <!--[pnformtextinput cssClass="col3" id=profile_n1_title group="profile" maxLength=255]-->
      <!--[pnformdropdownlist cssClass="col4" id=profile_n1_type group="profile" items=$typeItems]-->
      <!--[pnformdropdownlist cssClass="col5" id=profile_n1_mandatory group="profile" items=$mandatoryItems]-->
      <!--[pnformdropdownlist cssClass="col6" id=profile_n1_profile group="profile" items=$profileItems]-->
      <span class="col7">&nbsp;
      </span>
    </li>
  </ul>
  <!--[/pnformtabbedpanel]-->

  <!--[/pnformtabbedpanelset]-->

  <div class="pn-adminformbuttons">
    <!--[if !$init]-->
    <!--[pnformbutton commandName="update" text="_UPDATE"]-->
    <!--[pnformbutton commandName="cancel" text="_CANCEL"]-->
    <!--[else]-->
    <!--[pnformbutton commandName="update" text="_CONTINUE"]-->
    <!--[/if]-->
  </div>

<!--[/pnform]-->

<!--[include file="Eternizer_admin_footer.tpl" init=$init]-->