{css:common/common_publish}
{js:common/common_publish}
{code}
if(!class_exists('column'))
{
    include_once(ROOT_DIR . 'lib/class/column.class.php');
    $publish = new column();
}

$hg_sites = $publish->getallsites();
$hg_column = $publish->getdefaultcol();
$hg_selected = $hg_value ? $publish->get_selected_column_path($hg_value) : array();
{/code}
<div class="publish-box" id="publish-{$hg_name}" style="display:none;">
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
                }
            }
            {/code}
            <li _id="{$id}" _name="{$name}"><input type="checkbox" checked="checked" class="publish-checkbox"/>
            {$html}
            </li>
            {/foreach}
            {/if}
        </ul>
        <div class="publish-result-tip" style="{if $hg_selected}display:none;{/if}">没有选择！</div>
    </div>
    <div class="publish-site">
        {if $hg_sites}
        {foreach $hg_sites as $kk => $vv}
        <div class="publish-site-current" _siteid="{$kk}">{$vv}</div>
        {code}
        break;
        {/code}
        {/foreach}
        <span class="publish-site-qiehuan">切换</span>
        <ul>
        {code}
        $_default = false;
        {/code}
        {foreach $hg_sites as $kk => $vv}
        <li class="publish-site-item {if !$_default}publish-site-select{/if}" _siteid="{$kk}" _name="{$vv}"><input type="radio" name="publish-sites-{$hg_name}" {if !$_default}checked="checked"{/if} style="vertical-align:middle;margin-right:5px;"/>{$vv}</li>
        {code}
        $_default = true;
        {/code}
        {/foreach}
        {/if}
        </ul>
    </div>
    <div class="publish-list">
        <div class="publish-inner-list">
            <div class="publish-each">
                <ul>
                    {if $hg_column}
                    {foreach $hg_column as $kk => $vv}
                    <li _id="{$vv['id']}" _name="{$vv['name']}">
                        <input type="checkbox" class="publish-checkbox"/>{$vv['name']}
                        {if $vv['is_last']}
                        <span class="publish-child">&gt;</span>
                        {/if}
                    </li>
                    {/foreach}
                    {/if}
                </ul>
            </div>
        </div>
    </div>
    
    <input type="hidden" class="publish-hidden" name="column_id" value="{$hg_value}"/>

    {code}
    $names = array();
    if($hg_selected){
    foreach($hg_selected as $kk => $vv){
        $count = count($vv) - 1;
        $index = 0;
        foreach($vv as $vvv){
            if($count == $index){
                $names[] = $vvv['name'];
            }
            $index++;
        }
    }
    }
    $names = implode(',&nbsp;', $names);
    {/code}
    <input type="hidden" class="column-name" value="{$names}"/>
    <input type="hidden" class="publish-name-hidden" name="column_name" value="{$names}"/>

    <textarea class="publish-tpl" style="display:none;">
    <li _id="{{id}}" _name="{{nameother}}"><input type="checkbox" class="publish-checkbox"/>{{name}}<span class="{{haschild}}">&gt;</span></li>
    </textarea>

    <textarea class="publish-tpl-result" style="display:none;">
    <span class="publish-result-item">{{name}}</span>
    </textarea>
    
    {code}
    
    	//$hg_page_block = $publish->get_page_block();
    
    {/code}
    <!--
    <div style="display:none;margin-top:269px;weight:300px;height:200px;background-color:#F2F2F2;">
    	
    	<span class="publish-title" style="margin-top:269px;">推送至：</span>
    	<div style="margin-top:269px;margin-left:227px;">
    		{if $hg_page_block['block']}
    			{foreach $hg_page_block['block'] as $k=>$v}
    				<li><input type="checkbox" value="{$v['block_id']}" class="publish-checkbox"/>{$v['block_name']}</li>
    			{/foreach}
    		{/if}
    		{if $hg_page_block['page']}
    			{foreach $hg_page_block['page'] as $k=>$v}
    				<li _id="{{id}}" _name="{{nameother}}">{$v['name']} <span class="publish-child">&gt;</span></li>
    			{/foreach}
    		{/if}
    		
    	</div style="margin-left:50px">
    </div>
    -->
   
      
    
 
    
</div>

