<!--[ $Id$ ]-->
<!--[gt text="Guestbook" assign=title]-->
<!--[pnpagesetvar name=title value=$title]-->
<!--[insert name="getstatusmsg"]-->

<div id="eternizer">
    <h2><!--[gt text="Guestbook entries"]--> (<em class="z-sub"><a href="#pnFormForm"><!--[gt text="Add a new entry"]--></a></em>)</h2>

    <div id="eternizer-entries">
        <!--[foreach from=$entries item=entry]-->
        <!--[$entry]-->
        <!--[/foreach]-->
    </div>
    <!--[pager posvar="startnum" rowcount=$count limit=$config.perpage]-->

    <!--[$form]-->
</div>
