<li class="common-list-data clear"  id="r_{$v['group_id']}"    name="{$v['group_id']}"   orderid="{$v['order_id']}"  cname="{$v['cid']}"    corderid="{$v['order_id']}">
	   <div class="common-list-left">
                 <div class="common-list-item group-paixu">
                      <div class="common-list-cell">
                           <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"/></a>    
                       </div>  
                 </div>                       
       </div>
        <div class="common-list-right">
                 <div class="group-cz common-list-item open-close">
                        <div class="common-list-cell">
						      <div title="操作" class="btn-box-cz">
		                         <div class="btn-box-cz-menu" id="rr_2_{$v['group_id']}">
						             <a class="button_4" style="margin-right:4px;" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['group_id']}&infrm=1">编辑</a>
									 <a class="button_4" style="margin-right:4px;" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['group_id']}" onclick="return hg_ajax_post(this, '删除', 1);">删除</a>
									 <a class="button_4" style="margin-right:4px;" href="./run.php?mid={$_INPUT['mid']}&a=audit&id={$v['group_id']}" onclick="return hg_ajax_post(this, '审核', 1);">审核</a>
									 <a class="button_4" style="margin-right:4px;" href="./run.php?mid={$_INPUT['mid']}&a=back&id={$v['group_id']}" onclick="return hg_ajax_post(this, '打回', 1);">打回</a>
									 <a class="button_4" style="margin-right:4px;" href="javascript:void(0);" onclick="hg_statePublish({$v['group_id']});" id="statePublish_{$v['group_id']}" >发布</a>
					             </div>
			                 </div>
                        </div> 
                </div>
                 <div class="group-tz common-list-item open-close">
                       <div class="common-list-cell">
                               <span>{$v['post_count']}</span>   
                       </div>
                </div>
                 <div class="group-ht common-list-item open-close">
                       <div class="common-list-cell">
                               <span >{$v['thread_count']}</span>  
                       </div>
                </div>
                 <div class="group-jm common-list-item open-close">
                       <div class="common-list-cell">
                               <span >{$v['group_unconfirmed_member_count']}/{$v['group_member_count']}</span> 
                       </div>
                </div>
                 <div class="group-lx common-list-item open-close">
                       <div class="common-list-cell">
                               <span>{$v['type_name']}</span>   
                       </div>
                </div>
                 <div class="group-zt common-list-item open-close">
                       <div class="common-list-cell">
                               <span id="text_{$v['group_id']}">{if $v['audit']}已审核{else}待审核{/if}</span>
                       </div>
                </div>
                 <div class="group-tjr common-list-item open-close">
                       <div class="common-list-cell">
                               <span>{$v['user_name']}</span>
			                   <span class="create-time">{$v['create_time']}</span>
                       </div>
                </div>
         </div>
		 <div class="common-list-biaoti ">
	    <div class="common-list-item group-title biaoti-transition">
			   <div class="common-list-cell">
			   <span>
			   {if $v['logo']}
				<img style="vertical-align:middle;" width="40" height="30" src="{if is_array($v['logo'])}{$v['logo']['host']}{$v['logo']['dir']}50x50/{$v['logo']['filepath']}{$v['logo']['filename']}{else}{$_configs['app_url']}{$v['logo']}{/if}" alt="{$v['name']}">
				{/if}
			   </span>
                <span class="common-list-overflow" id="title_{$v['group_id']}" onclick="hg_show_opration_group({$v['group_id']},{if $_INPUT['_type']}{$_INPUT['_type']}{else}''{/if},{if $_INPUT['_id']}{$_INPUT['_id']}{else}''{/if});">{$v['name']}</span>
            </div>  
	    </div>
   </div>
</li>