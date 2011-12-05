{* $Id$ *}

{admincategorymenu xhtml=1}
<div class="z-adminbox">
    <h1>{modgetinfo modname="Eternizer" info="displayname"} v{modgetinfo modname="Eternizer" info="version"}</h1>
    <div class="z-menu">{modulelinks modname="Eternizer"}</div>
</div>
{insert name="getstatusmsg"}
<div id="eternizer" class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='Eternizer' src='admin.gif' alt='' }</div>
