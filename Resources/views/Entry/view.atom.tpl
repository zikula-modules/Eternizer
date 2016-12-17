{* purpose of this template: entries atom feed *}
<?xml version="1.0" encoding="{charset assign='charset'}{if $charset eq 'ISO-8859-15'}ISO-8859-1{else}{$charset}{/if}" ?>
<feed xmlns="http://www.w3.org/2005/Atom">
{gt text='Latest entries' assign='channelTitle'}
{gt text='A direct feed showing the list of entries' assign='channelDesc'}
    <title type="text">{$channelTitle}</title>
    <subtitle type="text">{$channelDesc} - {$modvars.ZConfig.slogan}</subtitle>
    <author>
        <name>{$modvars.ZConfig.sitename}</name>
    </author>
{assign var='numItems' value=$items|@count}
{if $numItems}
{capture assign='uniqueID'}tag:{$baseurl|replace:'http://':''|replace:'/':''},{$items[0].createdDate|dateformat|default:$smarty.now|dateformat:'%Y-%m-%d'}:{route name="mueternizermodule_entry_`$routeArea`display"  id=$items[0].id}{/capture}
    <id>{$uniqueID}</id>
    <updated>{$items[0].updatedDate|default:$smarty.now|dateformat:'%Y-%m-%dT%H:%M:%SZ'}</updated>
{/if}
<link rel="alternate" type="text/html" hreflang="{lang}" href="{route name="mueternizermodule_entry_`$routeArea`index" absolute=true}" />
<link rel="self" type="application/atom+xml" href="{php}echo substr(\System::getBaseUrl(), 0, strlen(\System::getBaseUrl())-1);{/php}{getcurrenturi}" />
<rights>Copyright (c) {php}echo date('Y');{/php}, {$baseurl}</rights>

{foreach item='entry' from=$items}
    <entry>
        <title type="html">{$entry->getTitleFromDisplayPattern()|notifyfilters:'mueternizermodule.filterhook.entries'}</title>
        <link rel="alternate" type="text/html" href="{route name="mueternizermodule_entry_`$routeArea`display"  id=$entry.id absolute=true}" />
        {capture assign='uniqueID'}tag:{$baseurl|replace:'http://':''|replace:'/':''},{$entry.createdDate|dateformat|default:$smarty.now|dateformat:'%Y-%m-%d'}:{route name="mueternizermodule_entry_`$routeArea`display"  id=$entry.id}{/capture}
        <id>{$uniqueID}</id>
        {if isset($entry.updatedDate) && $entry.updatedDate ne null}
            <updated>{$entry.updatedDate|dateformat:'%Y-%m-%dT%H:%M:%SZ'}</updated>
        {/if}
        {if isset($entry.createdDate) && $entry.createdDate ne null}
            <published>{$entry.createdDate|dateformat:'%Y-%m-%dT%H:%M:%SZ'}</published>
        {/if}
        {if isset($entry.createdUserId)}
            {usergetvar name='uname' uid=$entry.createdUserId assign='cr_uname'}
            {usergetvar name='name' uid=$entry.createdUserId assign='cr_name'}
            <author>
               <name>{$cr_name|default:$cr_uname}</name>
               <uri>{usergetvar name='_UYOURHOMEPAGE' uid=$entry.createdUserId assign='homepage'}{$homepage|default:'-'}</uri>
               <email>{usergetvar name='email' uid=$entry.createdUserId}</email>
            </author>
        {/if}

        <summary type="html">
            <![CDATA[
            {$entry.text|truncate:150:"&hellip;"|default:'-'}
            ]]>
        </summary>
        <content type="html">
            <![CDATA[
            {$entry.notes|replace:'<br>':'<br />'}
            ]]>
        </content>
    </entry>
{/foreach}
</feed>
