<!--[ $Id$ ]-->

<!--[pnformvalidationsummary]-->

<!--[pnform cssClass="z-form"]-->
<div>
    <fieldset>
        <legend><!--[gt text="Add a new entry"]--></legend>
        <!--[foreach from=$profile key=id item=item]-->
        <div class="z-formrow">
            <!--[pnformlabel for="profile_$id" text=$item.title]-->
            <!--[if $item.type eq 'mail']-->
            <!--[pnformemailinput id="profile_$id" group="profile" text=$item.value maxLength='255' mandatory=$item.mandatory readOnly=$item.readonly]-->
            <!--[elseif $item.type eq 'url']-->
            <!--[pnformurlinput id="profile_$id" group="profile" text=$item.value maxLength='255' mandatory=$item.mandatory readOnly=$item.readonly]-->
            <!--[else]-->
            <!--[pnformtextinput id="profile_$id" group="profile" text=$item.value maxLength='255' mandatory=$item.mandatory readOnly=$item.readonly]-->
            <!--[/if]-->
        </div>
        <!--[/foreach]-->

        <div class="z-formrow">
            <!--[pnformlabel for='text' __text='Text']-->
            <!--[pnformtextinput id='text' textMode="multiline" mandatory='1' rows="5" cols="40"]-->

            <!--[if $bbcode]-->
            <!--[pnmodfunc modname=bbcode type=user func=bbcodes textfieldid=text]--><br />
            <!--[/if]-->
            <!--[if $bbsmile]-->
            <!--[pnmodfunc modname=bbsmile type=user func=bbsmiles textfieldid=text]-->
            <!--[/if]-->
        </div>

        <!--[eternizerantispam group=extra]-->
    </fieldset>

    <div class="z-formbuttons">
        <!--[pnformbutton commandName="create" __text="Create"]-->
        <!--[pnformbutton commandName="cancel" __text="Cancel"]-->
    </div>
</div>
<!--[/pnform]-->
