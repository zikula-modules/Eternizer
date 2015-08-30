{* purpose of this template: entries xml inclusion template *}
<entry id="{$item.id}" createdon="{$item.createdDate|dateformat}" updatedon="{$item.updatedDate|dateformat}">
    <id>{$item.id}</id>
    <ip><![CDATA[{$item.ip}]]></ip>
    <name><![CDATA[{$item.name}]]></name>
    <email>{$item.email}</email>
    <homepage>{$item.homepage}</homepage>
    <location><![CDATA[{$item.location}]]></location>
    <text><![CDATA[{$item.text}]]></text>
    <notes><![CDATA[{$item.notes}]]></notes>
    <obj_status><![CDATA[{$item.obj_status}]]></obj_status>
    <workflowState>{$item.workflowState|mueternizermoduleObjectState:false|lower}</workflowState>
</entry>
