{* purpose of this template: entries view xml view *}
<?xml version="1.0" encoding="{charset}" ?>
<entries>
{foreach item='item' from=$items}
    {include file='entry/include.xml.tpl'}
{foreachelse}
    <noEntry />
{/foreach}
</entries>
