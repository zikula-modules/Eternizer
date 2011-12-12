{* $Id$ *}
{formvalidationsummary}
{form cssClass="z-form"}
<div>
    <fieldset>
        <legend>{gt text="Add a new entry"}</legend>
        {foreach from=$profile key=id item=item}
        <div class="z-formrow">
            {formlabel for="profile_$id" text=$item.title}
            {if $item.type eq 'mail'}
            {formemailinput id="profile_$id" group="profile" text=$item.value maxLength='255' mandatory=$item.mandatory readOnly=$item.readonly}
            {elseif $item.type eq 'url'}
            {formurlinput id="profile_$id" group="profile" text=$item.value maxLength='255' mandatory=$item.mandatory readOnly=$item.readonly}
            {else}
            {formtextinput id="profile_$id" group="profile" text=$item.value maxLength='255' mandatory=$item.mandatory readOnly=$item.readonly}
            {/if}
        </div>
        {/foreach}

        <div class="z-formrow">
            {formlabel for='text' __text='Text'}
            {formtextinput id='text' textMode="multiline" mandatory='1' rows="5" cols="40"}

            {if $bbcode}
            <div class="z-formnote">
                {modfunc modname=bbcode type=user func=bbcodes textfieldid=text}
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
        {formbutton commandName="create" __text="Create"}
        {formbutton commandName="cancel" __text="Cancel"}
    </div>
</div>
{/form}
