<script>
function  special_sortedit(id)
{
	window.location = "./run.php?mid={$_INPUT['mid']}&a=form&infrm=1&id="+id ;
}
</script>
                 <li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
                    	<span class="left">
							<a class="lb" onclick="hg_row_interactive('#r_{$v['id']}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title=""  /></a>
							</a>
							<a  class="tjr" {if $v['is_last']==0}href="./run.php?mid={$_INPUT['mid']}&a=show&column_id={$v['id']}"{/if}>{$v['name']}{if $v['is_last']==0} >>{/if}</a>
							<a class="column_slt" style="text-align:center;">{$v['linkurl']}</a>
						</span>
	                        <span class="right" onclick="hg_row_interactive('#r_{$v['id']}', 'click', 'cur');">
								<a class="fb"><em class="b2" onclick="special_sortedit({$v['id']});"></em></a>
								<a class="fb" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3" ></em></a>
								<a class="zt" > <em><span class="zt_a" id="share_status_{$v['id']}">{if $v['status']==2}未启用{else}启用{/if}</span></em></a>
								<a class="tjr"><em>{code}echo date('H:i:s',$v['create_time']){/code}</em><span>{code}echo date('Y-m-d',$v['create_time']){/code}</span></a>
						   </span>
                </li>   