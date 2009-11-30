<!--[* $Id$ *]-->
<!--[gt text="Guestbook" domain="module_eternizer" assign=title]-->
<!--[pnpagesetvar name=title value=$title]-->
<!--[insert name="getstatusmsg"]-->

<div id="eternizer">
    <h2><!--[gt text="Guestbook entries" domain="module_eternizer"]--> (<em class="z-sub"><a href="#pnFormForm"><!--[gt text="Add a new entry" domain="module_eternizer"]--></a></em>)</h2>

    <div id="eternizer-entries">
        <!--[foreach from=$entries item=entry]-->
        <!--[$entry]-->
        <!--[/foreach]-->
    </div>
    <!--[pager posvar="startnum" rowcount=$count limit=$config.perpage]-->

    <!--[$form]-->
</div>
