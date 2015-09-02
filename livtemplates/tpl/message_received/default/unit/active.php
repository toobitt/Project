           {code}
           if($v['title']==""){
           	$v['title'] = substr($v['content'],0,6);
           }
           {/code}
                 <li class="clear" id="r_{$v['id']}" name="{$v['id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');" >
                    	<span class="left">
							<a class="lb" name="alist[]" ><input id="primary_key_{$v['cateid']}" type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}" /></a>
						</span>
						<a title="{$v['title']}" style="width:100px;margin-top:8px;float:left;">
						<span id="title_{$v['id']}" class="m2o-common-title">{$v['title']}</span>
						</a>
						<a style="width:120px;margin-top:8px;float:left;">{$v['send_phone']}</a>
						<a style="width:170px;margin-top:8px;float:left;">{$v['receive_phone']}</a>
						<a style="width:100px;margin-top:8px;float:left;">{if($v['is_picture']==1)}有{else}无{/if}</a>
						<a style="width:100px;margin-top:8px;float:left;">{if($v['is_video']==1)}有{else}无{/if}</a>
						<a style="width:100px;margin-top:8px;float:left;">{if($v['is_annex']==1)}有{else}无{/if}</a>
	                    <span class="right" style="width:200px;">
								<a class="f1" style="width:70px;" id="status_{$v['id']}">{if($v['status']==0)}<span class="gery">未审核</span>{elseif($v['status']==1)}<span class="green">已审核</span>{elseif($v['status']==2)}<span class="red">已打回</span>{/if}</a>
	                    		<a class="fl" style="width:35px;" href="./run.php?mid={$_INPUT['mid']}&a=form{$_ext_link}&id={$v['id']}">编辑</a>
								<a class="fl" style="width:35px;" href="./run.php?mid={$_INPUT['mid']}&a=delete{$_ext_link}&id={$v['id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
					   </span>					   
                 </li>