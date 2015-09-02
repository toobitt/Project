                 <li class="clear" id="r_{$v['id']}" name="{$v['id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');" >
                    	<span class="left">
							<a class="lb" name="alist[]" ><input id="primary_key_{$v['cateid']}" type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}" /></a>
						</span>
						<a title="{$v['title']}" style="width:150px;margin-top:8px;float:left;">
						<span id="title_{$v['id']}" class="m2o-common-title">{$v['received_phone']}</span>
						</a>
						<a style="width:200px;margin-top:8px;float:left;">{code}{echo substr($v['content'],0,20);}{/code}</a>
						<a style="width:150px;margin-top:8px;float:left;">{code}{echo date("Y-m-d H:i:s",$v['create_time']);}{/code}</a>
						<a style="width:150px;margin-top:8px;float:left;">{code}{echo date("Y-m-d H:i:s",$v['update_time']);}{/code}</a>
						<a style="width:100px;margin-top:8px;float:left;">{if($v['backstatus']==1)}<span class="gery">{$v['back_status'][1]}</span>{elseif($v['backstatus']==2)}<span class="green">{$v['back_status'][2]}</span>{elseif($v['backstatus']==3)}<span class="red">{$v['back_status'][3]}</span>{/if}</a>
	                    <span class="right" style="width:200px;">
							<a class="f1" style="width:70px;" id="status_{$v['id']}">{if($v['status']==0)}<span class="gery">{$v['audio_status'][0]}</span>{elseif($v['status']==1)}<span class="green">{$v['audio_status'][1]}</span>{elseif($v['status']==2)}<span class="red">{$v['audio_status'][2]}</span>{/if}</a>
                    		<a class="fl" style="width:35px;" href="./run.php?mid={$_INPUT['mid']}&a=form{$_ext_link}&id={$v['id']}">编辑</a>
							<a class="fl" style="width:35px;" href="./run.php?mid={$_INPUT['mid']}&a=delete{$_ext_link}&id={$v['id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
					   </span>					   
                 </li>