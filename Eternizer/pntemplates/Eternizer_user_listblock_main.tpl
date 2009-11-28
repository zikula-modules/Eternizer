<!--[ $Id$ ]-->
<dl id="bookentries">
    <!--[foreach from=$entryarray item=entry]-->
    <dt><!--[$entry.cr_date|date_format:"%x %X"]-->: <!--[$entry.profile.1|pnvarprepfordisplay]--></dt>
    <dd><!--[$entry.text|pnvarprepfordisplay]--></dd>
    <!--[/foreach]-->
</dl>