<div id="bookentries">
	<!--[foreach from=$entryarray item=entry]-->
		<h5><!--[$entry.cr_date|date_format:"%x %X"]-->: <!--[$entry.profile.1]--></h5>
		<div><!--[$entry.text]--></div>
	<!--[/foreach]-->
</div>