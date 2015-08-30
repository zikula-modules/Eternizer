{* Purpose of this template: Display search options *}
<input type="hidden" id="mUEternizerModuleActive" name="active[MUEternizerModule]" value="1" />
<div>
    <input type="checkbox" id="active_mUEternizerModuleEntries" name="mUEternizerModuleSearchTypes[]" value="entry"{if $active_entry} checked="checked"{/if} />
    <label for="active_mUEternizerModuleEntries">{gt text='Entries' domain='mueternizermodule'}</label>
</div>
