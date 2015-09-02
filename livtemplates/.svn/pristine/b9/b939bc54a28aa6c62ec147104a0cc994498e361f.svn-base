 <script>
function  content_detail(id,a)
{
	window.location = "./run.php?mid={$_INPUT['mid']}&a="+a+"&infrm=1&id="+id ;
}
</script>
<!-- 
                 <li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
					    
					    <span class="left">
					    <a class="lb" onclick="hg_row_interactive('#r_{$v['id']}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title=""  /></a>
						    <a class="typeslt">&nbsp布局</a>
						    <a class="typeslt">&nbsp{$v['name']}</a>
						    <a class="typeslt">&nbsp{$_configs['update_type'][$v['update_type']]}</a>
                    		<a class="typeslt">{if $v['update_type']==1}-{else}{$v['update_time']}{/if}</a>
                    		<a class="typeslt">{if $v['update_type']==1}-{else}栏目{/if}</a>
                    		<a class="typeslt">{if $v['update_type']==1}-{else}应用{/if}</a>
                    		<a class="typeslt">{if $v['update_type']==1}-{else}{$v['weight']}{/if}</a>
                    		<a class="typeslt">{if $v['update_type']==1}-{else}{$v['line_num']}{/if}</a>
                    		<a class="typeslt">&nbsp引用页</a>
                    		 <a class="typeslt">{code}echo date('Y-m-d H-:i:s',$v['last_update_time']);{/code}</a>
                    		<a class="typeslt" onclick="content_detail({$v['id']},'block_form');">详细</a>
                    		<a class="typeslt" onclick="content_detail({$v['id']},'block_set');">设置</a>
                    		<a class="typeslt" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}" >删除</a>
                    		 </span>
                    		 <span class="right" onclick="hg_row_interactive('#r_{$v['id']}', 'click', 'cur');">
                </li> 
-->  
<li class="common-list-data clear"  id="r_{$v['rid']}" _id="{$v['id']}" name="{$v['rid']}"   orderid="{$v['order_id']}">
	<div class="common-list-left">
		<div class="common-list-item paixu">
			<div class="common-list-cell">
				 <a name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}" /></a>
			</div>
		</div>
		<div class="common-list-item block">
			<div class="common-list-cell">
			</div>
		</div>
	</div>
	<div class="common-list-right ">
	<div class="common-list-item opration">
			<div class="common-list-cell">
				<a class="typeslt" onclick="content_detail({$v['id']},'block_form');">设置</a>
			</div>
		</div>
		<div class="common-list-item update-type">
			<div class="common-list-cell">
				{$_configs['update_type'][$v['update_type']]}
			</div>
		</div>
		<div class="common-list-item pinlu">
			<div class="common-list-cell">
			{if $v['update_type']==1}-{else}{$v['update_time']}{/if}
			</div>
		</div>
		<div class="common-list-item lanmu">
			<div class="common-list-cell">
			{if $list['block']['block_record'][$v['id']]}
			{foreach $list['block']['block_record'][$v['id']] as $kk=>$vv}
			{if $list['column'][$vv]}
			{$list['column'][$vv]}
			{else}
			不限
			{/if}
			{/foreach}
			{else}
			不限
			{/if}
			
			</div>
		</div>
		<div class="common-list-item app">
			<div class="common-list-cell">
			{code}
			if($v['app'])
			{
				foreach(explode(',',$v['app']) as $va)
				{
					echo $list['app'][$va].' ';
				}
			}
			else
			{
				echo "不限";
			}
			{/code}
			</div>
		</div>
		<div class="common-list-item quanzhong">
			<div class="common-list-cell">
				<div class="common-quanzhong-box">
					<div class="common-quanzhong-box{$v['weight']}" _level="{$v['weight']}">
						<div class="common-quanzhong">{$_configs['levelLabel'][$v['weight']]}</div>
						<div class="common-quanzhong-option">
						    <div class="common-quanzhong-down"></div>
							<div class="common-quanzhong-up"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="common-list-item tiaoshu">
			<div class="common-list-cell">
			{$v['line_num']}
			</div>
		</div>
		<div class="common-list-item yinyong">
			<div class="common-list-cell">
			</div>
		</div>
		<div class="common-list-item last-update">
			<div class="common-list-cell">
				{code}echo date('Y-m-d H:i:s',$v['update_time']);{/code}
			</div>
		</div>
	</div>
	<div class="common-list-biaoti">
		<div class="common-list-item biaoti-transition">
			<div class="common-list-cell">
				<a class="shareslt" onclick="content_detail({$v['id']},'block_set');">{$v['name']}</a>
			</div>
		</div>
	</div>
</li>