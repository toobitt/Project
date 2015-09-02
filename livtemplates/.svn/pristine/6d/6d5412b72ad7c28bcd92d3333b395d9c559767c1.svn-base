<li class="common-list-data clear"  id="r_{$v['id']}" name="{$v['id']}" orderid="{$v['order_id']}" cname="{$v['cid']}" corderid="{$v['order_id']}">
	   <div class="common-list-left">
                 <div class="common-list-item group-paixu">
                      <div class="common-list-cell">
                           <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"/></a>    
                       </div>  
                 </div>                       
       </div>
        <div class="common-list-right">
       			<div class="group-cz common-list-item open-close" style="width:280px;">
                       <div class="common-list-cell">
                              <span>{$v['brief']}</span>
                       </div>
                </div>
                <div class="group-ht common-list-item open-close" style="width:100px;">
                       <div class="common-list-cell">
                              <span>{$_configs['attr_type'][$v['type']]}</span>
                       </div>
                </div>
                <div class="group-cz2 common-list-item open-close">
                       <div class="common-list-cell">
                              <span>
                              {if is_array($v['def_val'])}
							    {foreach $v['def_val'] as $val}
							    {if $val['type'] == 'image'}
							    <p{if $val['default']} style="color:red;"{/if}><img src="{code}echo hg_bulid_img($val['name'], 50, 1);{/code}" /> : {$val['value']}</p>
							    {else}
							    <p{if $val['default']} style="color:red;"{/if} style="margin:5px 0;">{if $val['name']}<span style="vertical-align:middle;">{$val['name']} : </span>{/if}
							    {code}
							    if (strpos($val['value'], '|')) {
								    $arr = explode('|', $val['value']);
								    foreach ($arr as $value)
								    {
								    	$vv = explode(':', $value);
								    	switch ($vv[0]) {
								    		case 'nav' :
								    			$color = '导航色';
								    		break;
								    		case 'thread' :
								    			$color = '线色';
								    		break;
								    		case 'main' :
								    			$color = '主色';
								    		break;
								    		case 'secondary' :
								    			$color = '辅助色';
								    		break;
								    	}
								    	echo '<em title="'.$color.'" style="background: '.$vv[1].'; width:10px; height:30px; vertical-align:middle;"></em>';
								    }
							    } else {
							    	echo $val['value'];
							    }
							    {/code}
							    </p>
							    {/if}
							    {/foreach}
							  {else}
							    {$v['def_val']}
							  {/if}
    						  </span>
                       </div>
                </div>
                <div class="group-ht common-list-item open-close" style="width:80px;">
                       <div class="common-list-cell">
                              <span>{if $v['flag'] == 1}界面{elseif $v['flag'] == 2}模板{/if}</span>
                       </div>
                </div>
        		<div class="group-tjr common-list-item open-close">
                        <div class="common-list-cell">
						      <div title="操作" class="btn-box-cz">
		                         <div class="btn-box-cz-menu" id="rr_2_{$v['id']}">
		                         	<a class="button_4" style="margin-right:4px;" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">编辑</a>
									<a class="button_4" style="margin-right:4px;" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
					             </div>
			                 </div>
                        </div> 
                </div>
         </div>
		 <div class="common-list-biaoti">
	    <div class="common-list-item group-title biaoti-transition">
			<div class="common-list-cell">
				<span>
			   	{if $v['pic']}
				<img style="vertical-align:middle; border-radius:10%;" width="40" height="40" src="{code}echo hg_bulid_img($v['pic'], 40, 40);{/code}" alt="{$v['name']}" />
				{/if}
			    </span>
                <span id="title_{$v['id']}" class="m2o-common-title">{$v['name']}</span>
            </div>  
	    </div>
   </div>
</li>