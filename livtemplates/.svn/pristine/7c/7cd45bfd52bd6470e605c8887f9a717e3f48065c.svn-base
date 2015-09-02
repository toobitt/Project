{js:common/common_node_prms}
{code}
if(!class_exists('NodePrms'))
{
    include_once(ROOT_DIR . 'lib/class/nodePrms.class.php');
    $node = new NodePrms();
}
$hg_nodes = $formdata['nodes'];

if($hg_nodes)
{
    foreach($hg_nodes as $mid=>$nodevars)
    {
        foreach($nodevars as $node_unqiueid=>$name)
        {
            if(!$nodedata)
            {
               $nodedata = $node->getNodeDataByMidN($mid, $node_unqiueid, True);
            }
            if($nodedata == -1)
            {
                echo "<font color=red>配置错误</font>";
            }
        }
    }
}
$hg_selected = $formdata['node_prms'];
$hg_selected = $node->get_selected_node($hg_selected, true);
{/code}
<div class="publish-box" id="publish-{$hg_name}" style="display:none;">
    <span class="publish-title">选择节点</span>
    <div class="publish-result">
        <ul style="{if !$hg_selected}display:none;{/if}">
            {if $hg_selected}
            {foreach $hg_selected as $kk => $vv}
            {code}
            $id = 0;
            $name = '';
            $html = '';
            $step = '';
            $index = 0;
            $count = count($vv);
            if($count == 1){
                $current = current($vv);
                $id = $current['id'];
                $name = $current['name'];
                $html = $current['name'];
                $siteid = $current['biaoshi'];
            }else{
                $html .= '<span class="publish-result-item">';
                foreach($vv as $vvv){
                    $index++;
                    if($index == $count){
                        $html .= '</span>';
                    }
                    $html .= $step.$vvv['name'];
                    $step = '<span class="publish-step">&gt;</span>';
                    $id = $vvv['id'];
                    $name = $vvv['name'];
                    $siteid = $vvv['biaoshi'];
                }
            }
            {/code}
            <li _id="{$id}" _name="{$name}" _siteid="{$siteid}"><input type="checkbox" checked="checked" class="publish-checkbox"/>
            {$html}
            </li>
            {/foreach}
            {/if}
        </ul>
        <div class="publish-result-tip" style="{if $hg_selected}display:none;{/if}">没有选择！</div>
    </div>
    <div class="publish-site">
        {if $hg_nodes}
            {foreach $hg_nodes as $kk => $vv}
                {foreach $vv as $kkk=>$vvv}
                    <div class="publish-site-current" _siteid="{$kk}" _nodevar="{$kkk}">{$vvv}</div>
                    {code}
                        break;
                    {/code}
                {/foreach}
            {/foreach}
            <span class="publish-site-qiehuan">切换</span>
            <ul>
            {code}
            $_default = false;
            {/code}
            {foreach $hg_nodes as $kk => $vv}
                {foreach $vv as $kkk=>$vvv}
                <li class="publish-site-item {if !$_default}publish-site-select{/if}" _siteid="{$kk}" _nodevar="{$kkk}" _name="{$vvv}"><input type="radio" name="publish-sites-{$hg_name}" {if !$_default}checked="checked"{/if} style="vertical-align:middle;margin-right:5px;"/>{$vvv}</li>
                    {code}
                        $_default = true;
                    {/code}
                {/foreach}
            {/foreach}
            </ul>
        {/if}
    </div>
    <div class="publish-list">
        <div class="publish-inner-list">
            <div class="publish-each">
                <ul>
                    {if $nodedata}
                    {foreach $nodedata as $kk => $vv}
                    <li _id="{$vv['id']}" _name="{$vv['name']}" _siteid="{$vv['biaoshi']}">
                        <input type="checkbox" class="publish-checkbox"/>{$vv['name']}
                        {if !$vv['is_last']}
                        <span class="publish-child">&gt;</span>
                        {/if}
                    </li>
                    {/foreach}
                    <li _id="0" _name="管理无分类" _siteid="{$vv['biaoshi']}">
                        <input type="checkbox" class="publish-checkbox"/>管理无分类
                    </li>
                    {/if}
                </ul>
            </div>
        </div>
    </div>

    <input type="hidden" class="publish-hidden" name="column_id" value="{$hg_value}"/>
    
    <input type="hidden" class="publish-hidden" name="node" value="{$hg_value}"/>

    <textarea class="publish-tpl" style="display:none;">
    <li _id="{{id}}" _name="{{nameother}}" _siteid="{{siteid}}"><input type="checkbox" class="publish-checkbox"/>{{name}}<span class="{{haschild}}">&gt;</span></li>
    </textarea>

    <textarea class="publish-tpl-result" style="display:none;">
    <span class="publish-result-item">{{name}}</span>
    </textarea>
</div>

