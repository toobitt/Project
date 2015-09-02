 <script>
function  content_detail(id,a)
{
	window.location = "./run.php?mid={$_INPUT['mid']}&a="+a+"&infrm=1&id="+id ;
}
</script>
                 <li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
						    
						    <span class="left">
						    <a class="lb" onclick="hg_row_interactive('#r_{$v['id']}', 'click', 'cur');"   name="alist[]" >&nbsp</a>
						   
						    	{if $block_form[0]['content'][$i]}
						    		{foreach $block_form[0]['content'][$i] as $kk=>$vv}
						    		 <a>
						    		 	<a onclick="hg_get_browse('{$block_form[0]['block']['id']}','{$i}','','{$vv['id']}')" >
						    			{$vv['title']}
						    			</a>
						    			<a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete_content&content_id={$vv['id']}">删除</a>&nbsp;
						    		 </a>
						    		{/foreach}
						    	{/if}
						   
                    		 </span>
                    		 <span class="right" onclick="hg_row_interactive('#r_{$v['id']}', 'click', 'cur');">
                    		<!--
                    		<a class="fb"><em class="b2" onclick="content_detail({$v['id']},'block_form');"></em></a>
                    		-->
                    		<a class="fl"  onclick="hg_get_browse('{$block_form[0]['block']['id']}','{$i}','','')" >行内容添加</a>
                    		<a class="fl"  onclick="hg_get_browse('{$block_form[0]['block']['id']}','{$i}','','')" >行设置</a>
                </li>   