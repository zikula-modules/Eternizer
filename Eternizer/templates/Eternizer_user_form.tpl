{* $Id$ *}
{pnformvalidationsummary}
{pnform cssClass="z-form"}
<div>
    <fieldset>
        <legend>{gt text="Add a new entry"}</legend>
        {foreach from=$profile key=id item=item}
        <div class="z-formrow">
            {pnformlabel for="profile_$id" text=$item.title}
            {if $item.type eq 'mail'}
            {pnformemailinput id="profile_$id" group="profile" text=$item.value maxLength='255' mandatory=$item.mandatory readOnly=$item.readonly}
            {elseif $item.type eq 'url'}
            {pnformurlinput id="profile_$id" group="profile" text=$item.value maxLength='255' mandatory=$item.mandatory readOnly=$item.readonly}
            {else}
            {pnformtextinput id="profile_$id" group="profile" text=$item.value maxLength='255' mandatory=$item.mandatory readOnly=$item.readonly}
            {/if}
        </div>
        {/foreach}

        <div class="z-formrow">
            {pnformlabel for='text' __text='Text'}
            {pnformtextinput id='text' textMode="multiline" mandatory='1' rows="5" cols="40"}

            {if $bbcode}
            <div class="z-formnote">
                {pnmodfunc modname=bbcode type=user func=bbcodes textfieldid=text}
            </div>
            {/if}
            {if $bbsmile}
            <div class="z-formnote">
                {modfunc modname=bbsmile type=user func=bbsmiles textfieldid=text}
            </div>
            {/if}
        </div>

        {eternizerantispam group=extra}
    </fieldset>

    <div class="z-formbuttons">
        {pnformbutton commandName="create" __text="Create"}
        {pnformbutton commandName="cancel" __text="Cancel"}
    </div>
</div>
{/pnform}
