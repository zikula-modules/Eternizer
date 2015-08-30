{* purpose of this template: entries view csv view *}
{strip}"{gt text='Ip'}";"{gt text='Name'}";"{gt text='Email'}";"{gt text='Homepage'}";"{gt text='Location'}";"{gt text='Text'}";"{gt text='Notes'}";"{gt text='Obj_status'}";"{gt text='Workflow state'}"
{/strip}
{foreach item='entry' from=$items}
{strip}
    "{$entry.ip}";"{$entry.name}";"{$entry.email}";"{$entry.homepage}";"{$entry.location}";"{$entry.text}";"{$entry.notes}";"{$entry.obj_status}";"{$item.workflowState|mueternizermoduleObjectState:false|lower}"
{/strip}
{/foreach}
