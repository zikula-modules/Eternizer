<!--[pnajaxheader filename=profilelist.js dragdrop=1]-->

<script type="text/javascript">
  var eternizer_deleteconfirm = '<!--[gt text="Confirm deletion?"]-->';
</script>

<!--[if $init]-->
<!--[modulestylesheet modname="Admin" stylesheet="admin.css"]-->
<!--[pnpageaddvar name="stylesheet" value="modules/Eternizer/pnstyle/linkicons_small.css"]-->
<!--[pnpageaddvar name="stylesheet" value="modules/Eternizer/pnstyle/style.css"]-->
<div id="eternizer" class="pn-admincontainer">
<h2><!--[gt text="Installation of Eternizer"]--></h2>
<!--[gt text="Configuration"]-->
<!--[else]-->
<!--[include file="Eternizer_admin_menu.tpl"]-->
<!--[/if]-->

<!--[insert name="getstatusmsg"]-->

<!--[pnform cssClass="pn-adminform"]-->

  <!--[pnformvalidationsummary]-->

  <!--[pnformtabbedpanelset]-->

  <!--[pnformtabbedpanel __title='General']-->
  <div class="pn-adminformrow">
    <!--[pnformlabel for='perpage' __text='Entries per page']-->
    <!--[pnformintinput id='perpage' mandatory='1' minValue='1']-->
  </div>

  <div class="pn-adminformrow">
    <!--[pnformlabel for='order' __text='Order']-->
    <!--[pnformdropdownlist id='order' mandatory='1']-->
  </div>

  <div class="pn-adminformrow">
    <!--[pnformlabel for='spammode' __text='Spam check']-->
    <!--[pnformdropdownlist id='spammode' mandatory='1']-->
  </div>

  <div class="pn-adminformrow">
    <!--[pnformlabel for='moderate' __text='Moderate']-->
    <!--[pnformdropdownlist id='moderate']-->
  </div>

  <div class="pn-adminformrow">
    <!--[pnformlabel for='useuserprofile' __text='Enforce use of profile data']-->
    <!--[pnformcheckbox id='useuserprofile']-->
  </div>
  <!--[/pnformtabbedpanel]-->

  <!--[pnformtabbedpanel __title='Long words processing']-->
  <div class="pn-adminformrow">
    <!--[pnformlabel for='wwaction' __text='Action']-->
    <!--[pnformdropdownlist id='wwaction']-->
  </div>

  <div class="pn-adminformrow">
    <!--[pnformlabel for='wwlimit' __text='Maximal length']-->
    <!--[pnformintinput id='wwlimit']-->
  </div>

  <div class="pn-adminformrow">
    <!--[pnformlabel for='wwshortto' __text='Length after processing']-->
    <!--[pnformintinput id='wwshortto']-->
  </div>
  <!--[/pnformtabbedpanel]-->

  <!--[pnformtabbedpanel __title="Profile"]-->
  <div class="pn-adminformrow">
    <!--[pnformlabel for='titlefield' __text='Title field']-->
    <!--[pnformdropdownlist id='titlefield']-->
  </div>
  <ul id="profiletable">
    <li class="profileheader pn-clearfix">
      <span class="col1">&nbsp;</span>
      <span class="col2"><!--[gt text="ID"]--></span>
      <span class="col3"><!--[gt text="Title"]--></span>
      <span class="col4"><!--[gt text="Type"]--></span>
      <span class="col5"><!--[gt text="Mandatory"]--></span>
      <span class="col6"><!--[gt text="Equivalent in Profile"]--></span>
      <span class="col7"><!--[gt text="Action"]--></span>
    </li>
    <!--[foreach from=$profile key="id" item="item"]-->
    <li id="profile_<!--[$id]-->" class="<!--[cycle values="pn-odd,pn-even"]--> pn-sortable pn-clearfix moveable">
      <span class="col1 move"><!--[gt text="Move"]--></span>
      <span class="col2"><!--[$id]--></span>
      <!--[pnformtextinput cssClass="pn-hide" id='profile_`$id`_pos' group="profile" maxLength='20']-->
      <!--[pnformtextinput cssClass="col3" id='profile_`$id`_title' group="profile" __text=$item.title maxLength='255']-->
      <!--[pnformdropdownlist cssClass="col4" id='profile_`$id`_type' group="profile" selectedValue=$item.type items=$typeItems]-->
      <!--[pnformdropdownlist cssClass="col5" id='profile_`$id`_mandatory' group="profile" selectedValue=$item.mandatory items=$mandatoryItems]-->
      <!--[pnformdropdownlist cssClass="col6" id='profile_`$id`_profile' group="profile" selectedValue=$item.profile items=$profileItems]-->
      <span class="col7">
        <a href="javascript:eternizer_profile_delete(<!--[$id]-->);" class="deleterow">D</a>
      </span>
    </li>
    <!--[/foreach]-->
    <li id="profile_n1" class="<!--[cycle values="pn-odd,pn-even"]--> pn-sortable pn-clearfix moveable">
      <span class="col1 move"><!--[gt text="Move"]--></span>
      <span class="col2"><!--[gt text="New"]--></span>
      <!--[pnformtextinput cssClass="pn-hide" id='profile_n1_pos' group="profile" maxLength='20']-->
      <!--[pnformtextinput cssClass="col3" id='profile_n1_title' group="profile" maxLength='255']-->
      <!--[pnformdropdownlist cssClass="col4" id='profile_n1_type' group="profile" items=$typeItems]-->
      <!--[pnformdropdownlist cssClass="col5" id='profile_n1_mandatory' group="profile" items=$mandatoryItems]-->
      <!--[pnformdropdownlist cssClass="col6" id='profile_n1_profile' group="profile" items=$profileItems]-->
      <span class="col7">&nbsp;
      </span>
    </li>
  </ul>
  <!--[/pnformtabbedpanel]-->

  <!--[/pnformtabbedpanelset]-->

  <div class="pn-adminformbuttons">
    <!--[if !$init]-->
    <!--[pnformbutton commandName="update" __text="Update"]-->
    <!--[pnformbutton commandName="cancel" __text="Cancel"]-->
    <!--[else]-->
    <!--[pnformbutton commandName="update" __text="Continue"]-->
    <!--[/if]-->
  </div>

<!--[/pnform]-->

<!--[include file="Eternizer_admin_footer.tpl" init=$init]-->