 <script>
function  content_detail(id)
{
	window.location = "./run.php?mid=185&infrm=1&cid="+id ;
}
</script>
<!--window.location = "./run.php?mid={$_INPUT['mid']}&a=getdetail&infrm=1&cid="+id ;
-->
                 <li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
                    	<!--
                    	<span class="left">
							<a class="lb" onclick="hg_row_interactive('#r_{$v['id']}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title=""  /></a>
							</a>
							<a  class="tjr" >{$v['title']}</a>
							<a class="tjr" style="text-align:center;">{$v['brief']}</a>
							<a  class="fb" >&nbsp;{code}if(!empty($_configs['client'][$v['client_type']])) echo $_configs['client'][$v['client_type']];{/code}</a>
							
						</span>
	                        <span class="right" onclick="hg_row_interactive('#r_{$v['id']}', 'click', 'cur');">
								<a class="fb"><em class="b2" onclick="content_detail({$v['id']});"></em></a>
								<a class="fb" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3" ></em></a>
								<a class="tjr"><em>{code}echo date('H:i:s',$v['create_time']){/code}</em><span>{code}echo date('Y-m-d',$v['create_time']){/code}</span></a>
						   </span>
						 -->  
						    <span class="left">
						    <a class="lb">&nbsp</a>
						    <a class="shareslt">nbsp{$v['title']}</a>
                    		<a class="shareslt"  style="width:300px;">nbsp{$v['brief']}</a>
                    		<a class="shareslt">&nbsp;{code}if(!empty($_configs['client'][$v['client_type']])) echo $_configs['client'][$v['client_type']];{/code}</a>
                    		 </span>
                    		 <span class="right" onclick="hg_row_interactive('#r_{$v['id']}', 'click', 'cur');">
                    		<a class="fb"><em class="b2" onclick="content_detail({$v['id']});"></em></a>
                    		<a class="fb"  onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3" ></em></a>
                    		<a class="tjr"><em>{code}echo date('H:i:s',$v['create_time']){/code}</em><span>{code}echo date('Y-m-d',$v['create_time']){/code}</span></a></span>
                        <span class="right"></span>
                </li>   