
<script>
(function(){
    var configBaseUrl = './run.php?mid='+ gMid +'&a=';
    window['configUrl'] = {
        ajax : 'route2node.php?nodevar={$hg_name}&fid={{fid}}&mid=' + gMid,
        create : configBaseUrl + 'create',
        update : configBaseUrl + 'update',
        sort : configBaseUrl + 'sort',
        'delete' : configBaseUrl + 'delete&ajax=1'
    };
})();
</script>
{css:column_form}
{js:column/sort}

<div class="column-outer-box">
    <span class="column-left-button"><</span>
    <span class="column-right-button">></span>
    <div class="column-bg"></div>
    <div class="column-box">
        <div class="column-inner-box">
            <div class="column-each">
                <div class="column-add"><a class="column-sort-button" href="javascript:;"></a><span class="column-add-box"><a class="column-add-button" href="javascript:;"></a>添加分类</span></div>
                <ul class="column-ul">
                    {foreach $hg_value as $kk => $vv}
                    <li _id="{$vv['id']}" _fid="{$vv['fid']}" id="sort-{$vv['id']}">
                        <a class="column-edit-button" href="#{$vv['id']}" onclick="return false;" title="{$vv['id']}"></a>
                        {if !$vv['is_last']}
                        <a class="column-next-button" href="javascript:;">&gt;</a>
                        {else}
                        <a class="column-delete-button" href="javascript:;">—</a>
                        {/if}
                        <span class="column-name">{$vv['name']}</span>
                    </li>
                    {/foreach}
                </ul>
            </div>
            <div class="column-each">
                <div class="column-add"><a class="column-sort-button" href="javascript:;"></a><span class="column-add-box"><a class="column-add-button" href="javascript:;"></a>添加分类</span></div>
                <ul class="column-ul">
                </ul>
            </div>
        </div>
    </div>
</div>

<textarea id="column-tpl-add" style="display:none;">
    <div class="column-input">
        <div class="column-input-text"><span class="column-input-add"></span><input type="text"/><span class="column-submit">OK</span><button class="column-cancel">NO</button></div>
        <div class="column-input-tip">提交中...</div>
    </div>
</textarea>
<textarea id="column-tpl-edit" style="display:none;">
    <div class="column-edit">
        <div class="column-edit-inner">
            <div class="column-edit-text"><a class="column-edit-button" href="javascript:;"></a><input type="text"/><span class="column-edit-submit">OK</span></div>
            <div class="column-edit-tip">提交中...</div>
        </div>

        <div class="column-select-box">
            <div class="column-select-parent">
                <span class="column-select-button">切换父分类</span>
                <span class="column-select-cancel">取消</span>
                <div class="column-parents">
                    <div>原来的分类：<span class="column-old-parents"></span></div>
                    <div>现在的分类：<span class="column-now-parents"></span></div>
                </div>
            </div>
        </div>
    </div>
</textarea>
<textarea id="column-tpl-li" style="display:none;">
    <li _id="{{id}}" _fid="{{fid}}" id="sort-{{id}}">
        <a class="column-edit-button" href="#{{id}}" onclick="return false;" title="{{id}}"></a>
        {{next}}
        {{del}}
        <span class="column-name">{{name}}</span>
    </li>
</textarea>
<textarea id="column-tpl-next" style="display:none;">
    <a class="column-next-button" href="javascript:;">&gt;</a>
</textarea>
<textarea id="column-tpl-delete" style="display:none;">
    <a class="column-delete-button" href="javascript:;">—</a>
</textarea>
<textarea id="column-tpl-child" style="display:none;">
    <div class="column-each">
        <div class="column-add"><a class="column-sort-button" href="javascript:;"></a><span class="column-add-box"><a class="column-add-button" href="javascript:;"></a>添加分类</span></div>
        <ul class="column-ul">
            <li><img src="{$RESOURCE_URL}loading2.gif" style="width:20px;"/></li>
        </ul>
    </div>
</textarea>