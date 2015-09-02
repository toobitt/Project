<li class="common-list-data clear"  id="r_{$v['id']}" name="{$v['id']}" orderid="{$v['order_id']}" cname="{$v['cid']}" corderid="{$v['order_id']}">
	   <div class="common-list-left">
                 <div class="common-list-item group-paixu">
                      <div class="common-list-cell">
                           <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"/></a>    
                       </div>  
                 </div>                       
       </div>
        <div class="common-list-right">
       			<div class="group-cz2 common-list-item open-close">
                       <div class="common-list-cell">
                       {if $v['send_to_id']}已发送{else}<a href="./run.php?mid={$_INPUT['mid']}&a=send&id={$v['id']}" style="text-decoration: underline;" onclick="return hg_ajax_post(this, '发送', 1);">未发送</a>{/if}
                       </div>
                </div>
                <div class="group-cz2 common-list-item open-close">
                       <div class="common-list-cell">
                       {if $v['user_id']}已使用{else}未使用{/if}
                       </div>
                </div>
                <div class="group-cz2 common-list-item open-close">
                       <div class="common-list-cell">
                       {if $v['user_id']}{$v['user_name']}{else}暂无{/if}
                       </div>
                </div>
        		<div class="group-tz common-list-item open-close">
                        <div class="common-list-cell">
						      <div title="操作" class="btn-box-cz">
		                         <div class="btn-box-cz-menu" id="rr_2_{$v['id']}">
									<a class="button_4" style="margin-right:4px;" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
					             </div>
			                 </div>
                        </div> 
                </div>
                <div class="group-tjr common-list-item open-close">
                       <div class="common-list-cell">
                               <span>{code}echo date('Y-m-d H:i:s', $v['create_time']);{/code}<br />{$v['add_user_name']}</span>   
                       </div>
                </div>
         </div>
		 <div class="common-list-biaoti">
	    <div class="common-list-item group-title biaoti-transition">
			<div class="common-list-cell">
                <span id="title_{$v['id']}" class="m2o-common-title">{$v['code']}</span>
            </div>  
	    </div>
   </div>
</li>