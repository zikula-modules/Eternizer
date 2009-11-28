<!--[ $Id$ ]-->

<!--[include file="Eternizer_admin_menu.tpl"]-->
<h2><!--[gt text="Edit entry"]--></h2>
<!--[pnformvalidationsummary]-->

<!--[pnform cssClass="z-form"]-->
<div>
    <fieldset>
        <legend><!--[gt text="Edit entry"]--></legend>
        <!--[foreach from=$profileConfig key=id item=item]-->
        <div class="z-formrow">
            <!--[pnformlabel for="profile_$id" text=$item.title]-->
            <!--[if $item.type eq 'mail']-->
            <!--[pnformemailinput id="profile_$id" group="profile" text=$item.value maxLength='255' mandatory=$item.mandatory readOnly=$profileReadonly]-->
            <!--[elseif $item.type eq 'url']-->
            <!--[pnformurlinput id="profile_$id" group="profile" text=$item.value maxLength='255' mandatory=$item.mandatory readOnly=$profileReadonly]-->
            <!--[else]-->
            <!--[pnformtextinput id="profile_$id" group="profile" text=$item.value maxLength='255' mandatory=$item.mandatory readOnly=$profileReadonly]-->
            <!--[/if]-->
        </div>
        <!--[/foreach]-->

        <div class="z-formrow">
            <!--[pnformlabel for='text' __text='Text']-->
            <!--[pnformtextinput id='text' textMode="multiline" mandatory='1' rows="5" cols="40" readOnly=$modonly]-->
        </div>

        <div class="z-formrow">
            <!--[pnformlabel for='comment' __text='Comment']-->
            <!--[pnformtextinput id='comment' textMode="multiline" mandatory='0' rows='5' cols='40']-->
        </div>

        <!--[if $bbcode]-->
        <div class="z-formnote">
            <!--[pnmodfunc modname=bbcode type=user func=bbcodes textfieldid=comment images=0]-->
        </div>
        <!--[/if]-->
        <!--[if $bbsmile]-->
        <div class="z-formnote">
            <!--[pnmodfunc modname=bbsmile type=user func=bbsmiles textfieldid=comment]-->
        </div>
        <!--[/if]-->

    </fieldset>

    <div class="z-formbuttons">
        <!--[pnformbutton commandName="update" __text="Update"]-->
        <!--[pnformbutton commandName="cancel" __text="Cancel"]-->
    </div>

</div>
<!--[/pnform]-->

<!--[include file="Eternizer_admin_footer.tpl"]-->