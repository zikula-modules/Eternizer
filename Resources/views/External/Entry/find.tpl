{* Purpose of this template: Display a popup selector of entries for scribite integration *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}" lang="{lang}">
<head>
    <title>{gt text='Search and select entry'}</title>
    <link type="text/css" rel="stylesheet" href="{$baseurl}style/core.css" />
    <link type="text/css" rel="stylesheet" href="{$baseurl}modules/Resources/public/css/style.css" />
    <link type="text/css" rel="stylesheet" href="{$baseurl}modules/Resources/public/css/finder.css" />
    {assign var='ourEntry' value=$modvars.ZConfig.entrypoint}
    <script type="text/javascript">/* <![CDATA[ */
        if (typeof(Zikula) == 'undefined') {var Zikula = {};}
        Zikula.Config = {'entrypoint': '{{$ourEntry|default:'index.php'}}', 'baseURL': '{{$baseurl}}'}; /* ]]> */</script>
        <link rel="stylesheet" href="web/bootstrap/css/bootstrap.min.css" type="text/css" />
        <link rel="stylesheet" href="web/bootstrap/css/bootstrap-theme.css" type="text/css" />
        <script type="text/javascript" src="web/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="web/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{$baseurl}modules/Resources/public/js/MUEternizerModule.Finder.js"></script>
</head>
<body>
    <form action="{$ourEntry|default:'index.php'}" id="mUEternizerModuleSelectorForm" method="get" class="form-horizontal" role="form">
    <div>
        <input type="hidden" name="module" value="MUEternizerModule" />
        <input type="hidden" name="type" value="external" />
        <input type="hidden" name="func" value="finder" />
        <input type="hidden" name="objectType" value="{$objectType}" />
        <input type="hidden" name="editor" id="editorName" value="{$editorName}" />

        <fieldset>
            <legend>{gt text='Search and select entry'}</legend>

            <div class="form-group">
                <label for="mUEternizerModulePasteAs" class="col-sm-3 control-label">{gt text='Paste as'}:</label>
                <div class="col-sm-9">
                    <select id="mUEternizerModulePasteAs" name="pasteas" class="form-control">
                        <option value="1">{gt text='Link to the entry'}</option>
                        <option value="2">{gt text='ID of entry'}</option>
                    </select>
                </div>
            </div>
            <br />

            <div class="form-group">
                <label for="mUEternizerModuleObjectId" class="col-sm-3 control-label">{gt text='Entry'}:</label>
                <div class="col-sm-9">
                    <div id="mueternizermoduleItemContainer">
                        <ul>
                        {foreach item='entry' from=$items}
                            <li>
                                <a href="#" onclick="mUEternizerModule.finder.selectItem({$entry.id})" onkeypress="mUEternizerModule.finder.selectItem({$entry.id})">{$entry->getTitleFromDisplayPattern()}</a>
                                <input type="hidden" id="url{$entry.id}" value="{route name='mueternizermodule_entry_display'  id=$entry.id absolute=true}" />
                                <input type="hidden" id="title{$entry.id}" value="{$entry->getTitleFromDisplayPattern()|replace:"\"":""}" />
                                <input type="hidden" id="desc{$entry.id}" value="{capture assign='description'}{if $entry.text ne ''}{$entry.text}{/if}
                                {/capture}{$description|strip_tags|replace:"\"":""}" />
                            </li>
                        {foreachelse}
                            <li>{gt text='No entries found.'}</li>
                        {/foreach}
                        </ul>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="mUEternizerModuleSort" class="col-sm-3 control-label">{gt text='Sort by'}:</label>
                <div class="col-sm-9">
                    <select id="mUEternizerModuleSort" name="sort" style="width: 150px" class="pull-left" style="margin-right: 10px">
                    <option value="id"{if $sort eq 'id'} selected="selected"{/if}>{gt text='Id'}</option>
                    <option value="workflowState"{if $sort eq 'workflowState'} selected="selected"{/if}>{gt text='Workflow state'}</option>
                    <option value="ip"{if $sort eq 'ip'} selected="selected"{/if}>{gt text='Ip'}</option>
                    <option value="name"{if $sort eq 'name'} selected="selected"{/if}>{gt text='Name'}</option>
                    <option value="email"{if $sort eq 'email'} selected="selected"{/if}>{gt text='Email'}</option>
                    <option value="homepage"{if $sort eq 'homepage'} selected="selected"{/if}>{gt text='Homepage'}</option>
                    <option value="location"{if $sort eq 'location'} selected="selected"{/if}>{gt text='Location'}</option>
                    <option value="text"{if $sort eq 'text'} selected="selected"{/if}>{gt text='Text'}</option>
                    <option value="notes"{if $sort eq 'notes'} selected="selected"{/if}>{gt text='Notes'}</option>
                    <option value="obj_status"{if $sort eq 'obj_status'} selected="selected"{/if}>{gt text='Obj_status'}</option>
                    <option value="createdDate"{if $sort eq 'createdDate'} selected="selected"{/if}>{gt text='Creation date'}</option>
                    <option value="createdUserId"{if $sort eq 'createdUserId'} selected="selected"{/if}>{gt text='Creator'}</option>
                    <option value="updatedDate"{if $sort eq 'updatedDate'} selected="selected"{/if}>{gt text='Update date'}</option>
                    </select>
                    <select id="mUEternizerModuleSortDir" name="sortdir" style="width: 100px" class="form-control">
                        <option value="asc"{if $sortdir eq 'asc'} selected="selected"{/if}>{gt text='ascending'}</option>
                        <option value="desc"{if $sortdir eq 'desc'} selected="selected"{/if}>{gt text='descending'}</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="mUEternizerModulePageSize" class="col-sm-3 control-label">{gt text='Page size'}:</label>
                <div class="col-sm-9">
                    <select id="mUEternizerModulePageSize" name="num" style="width: 50px; text-align: right" class="form-control">
                        <option value="5"{if $pager.itemsperpage eq 5} selected="selected"{/if}>5</option>
                        <option value="10"{if $pager.itemsperpage eq 10} selected="selected"{/if}>10</option>
                        <option value="15"{if $pager.itemsperpage eq 15} selected="selected"{/if}>15</option>
                        <option value="20"{if $pager.itemsperpage eq 20} selected="selected"{/if}>20</option>
                        <option value="30"{if $pager.itemsperpage eq 30} selected="selected"{/if}>30</option>
                        <option value="50"{if $pager.itemsperpage eq 50} selected="selected"{/if}>50</option>
                        <option value="100"{if $pager.itemsperpage eq 100} selected="selected"{/if}>100</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="mUEternizerModuleSearchTerm" class="col-sm-3 control-label">{gt text='Search for'}:</label>
            <div class="col-sm-9">
                    <input type="text" id="mUEternizerModuleSearchTerm" name="q" style="width: 150px" class="form-control pull-left" style="margin-right: 10px" />
                    <input type="button" id="mUEternizerModuleSearchGo" name="gosearch" value="{gt text='Filter'}" style="width: 80px" class="btn btn-default" />
            </div>
            </div>
            
            <div style="margin-left: 6em">
                {pager display='page' rowcount=$pager.numitems limit=$pager.itemsperpage posvar='pos' template='pagercss.tpl' maxpages='10' route='mueternizermodule_external_finder'}
            </div>
            <input type="submit" id="mUEternizerModuleSubmit" name="submitButton" value="{gt text='Change selection'}" class="btn btn-success" />
            <input type="button" id="mUEternizerModuleCancel" name="cancelButton" value="{gt text='Cancel'}" class="btn btn-default" />
            <br />
        </fieldset>
    </div>
    </form>

    <script type="text/javascript">
    /* <![CDATA[ */
        ( function($) {
            $(document).ready(function() {
                mUEternizerModule.finder.onLoad();
            });
        })(jQuery);
    /* ]]> */
    </script>

    {*
    <div class="mueternizermodule-finderform">
        <fieldset>
            {modfunc modname='MUEternizerModule' type='admin' func='edit'}
        </fieldset>
    </div>
    *}
</body>
</html>
