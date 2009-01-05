<!--[* $Id$ *]-->
<!--[*  ---------------------------------------------------------------------------- *]-->
<!--[*  Author: $Author: philipp $                                        *]-->
<!--[*  Link: http://www.guite.de	                                                 *]-->
<!--[*  Copyright: Copyright (C) 2005 by Guite	                                     *]-->
<!--[*  License: http://www.gnu.org/copyleft/gpl.html GNU General Public License	 *]-->
<!--[*  ---------------------------------------------------------------------------- *]-->
<!--[*  Purpose of this template:	                                                 *]-->
<!--[*  User formular	                                                             *]-->
<!--[*  ---------------------------------------------------------------------------- *]-->

<!--[pnformvalidationsummary]-->

<!--[pnform cssClass="et_form"]-->
<!--[foreach from=$profile key=id item=item]-->
<div class="row">
  <!--[pnformlabel for="profile_$id" text=$item.title]-->
  <!--[if $item.type eq 'mail']-->
  <!--[pnformemailinput id="profile_$id" group="profile" text=$item.value maxLength=255 mandatory=$item.mandatory readOnly=$item.readonly]-->
  <!--[elseif $item.type eq 'url']-->
  <!--[pnformurlinput id="profile_$id" group="profile" text=$item.value maxLength=255 mandatory=$item.mandatory readOnly=$item.readonly]-->
  <!--[else]-->
  <!--[pnformtextinput id="profile_$id" group="profile" text=$item.value maxLength=255 mandatory=$item.mandatory readOnly=$item.readonly]-->
  <!--[/if]-->
</div>
<!--[/foreach]-->

<div class="row">
  <!--[pnformlabel for=text text=_ETERNIZER_TEXT"]-->
  <!--[pnformtextinput id=text textMode="multiline" mandatory=1 rows="5" cols="40"]-->

  <!--[if $bbcode]-->
  <!--[pnmodfunc modname=bbcode type=user func=bbcodes textfieldid=text]--><br />
  <!--[/if]-->
  <!--[if $bbsmile]-->
  <!--[pnmodfunc modname=bbsmile type=user func=bbsmiles textfieldid=text]-->
  <!--[/if]-->
</div>

<!--[eternizerantispam group=extra]-->

<div class="buttonrow">
  <!--[pnformbutton commandName="create" text="_CREATE"]-->
  <!--[pnformbutton commandName="cancel" text="_CANCEL"]-->
</div>

<!--[/pnform]-->
