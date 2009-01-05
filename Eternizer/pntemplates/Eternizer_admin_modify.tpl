<!--[* $Id$ *]-->
<!--[*  ---------------------------------------------------------------------------- *]-->
<!--[*  Author: $Author: philipp $                                        *]-->
<!--[*  Link: http://www.guite.de	                                                 *]-->
<!--[*  Copyright: Copyright (C) 2005 by Guite	                                     *]-->
<!--[*  License: http://www.gnu.org/copyleft/gpl.html GNU General Public License	 *]-->
<!--[*  ---------------------------------------------------------------------------- *]-->
<!--[*  Purpose of this template:	                                                 *]-->
<!--[*  Modify an entry	                                                             *]-->
<!--[*  ---------------------------------------------------------------------------- *]-->

<!--[include file="Eternizer_admin_menu.tpl"]-->

<!--[pnformvalidationsummary]-->

<!--[pnform]-->
<!--[foreach from=$profileConfig key=id item=item]-->
<div class="row">
  <!--[pnformlabel for="profile_$id" text=$item.title]-->
  <!--[if $item.type eq 'mail']-->
  <!--[pnformemailinput id="profile_$id" group="profile" text=$item.value maxLength=255 mandatory=$item.mandatory readOnly=$profileReadonly]-->
  <!--[elseif $item.type eq 'url']-->
  <!--[pnformurlinput id="profile_$id" group="profile" text=$item.value maxLength=255 mandatory=$item.mandatory readOnly=$profileReadonly]-->
  <!--[else]-->
  <!--[pnformtextinput id="profile_$id" group="profile" text=$item.value maxLength=255 mandatory=$item.mandatory readOnly=$profileReadonly]-->
  <!--[/if]-->
</div>
<!--[/foreach]-->

<div class="row">
  <!--[pnformlabel for=text text=_ETERNIZER_TEXT]-->
  <!--[pnformtextinput id=text textMode="multiline" mandatory=1 rows="5" cols="40" readOnly=$modonly]-->
  <!--[if !$modonly]-->
  <!--[if $bbcode]-->
  <!--[pnmodfunc modname=bbcode type=user func=bbcodes textfieldid=text images=0]--><br />
  <!--[/if]-->
  <!--[if $bbsmile]-->
  <!--[pnmodfunc modname=bbsmile type=user func=bbsmiles textfieldid=text]-->
  <!--[/if]-->
  <!--[/if]-->
</div>

<div class="row">
  <!--[pnformlabel for=comment text=_ETERNIZER_COMMENT]-->
  <!--[pnformtextinput id=comment textMode="multiline" mandatory=0 rows=5 cols=40]-->

  <!--[if $bbcode]-->
  <!--[pnmodfunc modname=bbcode type=user func=bbcodes textfieldid=comment images=0]--><br />
  <!--[/if]-->
  <!--[if $bbsmile]-->
  <!--[pnmodfunc modname=bbsmile type=user func=bbsmiles textfieldid=comment]-->
  <!--[/if]-->
</div>

<div class="buttonrow">
  <!--[pnformbutton commandName="update" text="_UPDATE"]-->
  <!--[pnformbutton commandName="cancel" text="_CANCEL"]-->
</div>

<!--[/pnform]-->

<!--[include file="Eternizer_admin_footer.tpl"]-->