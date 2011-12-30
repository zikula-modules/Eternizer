{* purpose of this template: inclusion template for display of related Entries in user area *}

<h4>
    <a href="{modurl modname='Eternizer' type='user' func='display' ot='entry' id=$item.id}">
        {$item.ip}
    </a>
    <a id="entryItem{$item.id}Display" href="{modurl modname='Eternizer' type='user' func='display' ot='entry' id=$item.id theme='Printer' forcelongurl=true}" title="{gt text='Open quick view window'}" style="display: none">
        {icon type='view' size='extrasmall' __alt='Quick view'}
    </a>
</h4>
    <script type="text/javascript" charset="utf-8">
    /* <![CDATA[ */
        document.observe('dom:loaded', function() {
            eternizerInitInlineWindow($('entryItem{{$item.id}}Display'), '{{$item.ip|replace:"'":""}}');
        });
    /* ]]> */
    </script>

