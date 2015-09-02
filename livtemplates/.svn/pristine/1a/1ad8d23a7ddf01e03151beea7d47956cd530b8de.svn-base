<li class="common-list-data clear"  id="r_{$v['appid']}"    name="{$v['appid']}"   orderid="{$v['order_id']}">
	<div class="common-list-left">
        <div class="common-list-item auth-paixu">
            <div class="common-list-cell">
                <a class="lb"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['appid']}" title="{$v['appid']}"  /></a>
            </div>
        </div>
    </div>
	<div class="common-list-right">
        <div class="common-list-item auth-bj">
            <div class="common-list-cell">
                <a class="btn-box" href="javascript:void(0);"  onclick="hg_showAddAuth({$v['appid']});"><em class="b2" ></em></a>
            </div>
        </div>
        <div class="common-list-item auth-sc">
            <div class="common-list-cell">
            	{if $v['is_update']}
                 <a class="btn-box" href="run.php?mid={$_INPUT['mid']}&a=delete&appid={$v['appid']}"  onclick="return hg_ajax_post(this,'删除',1);"><em class="b3" ></em></a>
            	{/if}
            </div>
        </div>
        <div class="common-list-item auth-khxs">
            <div class="common-list-cell">
              <span id="display_name_{$v['appid']}">{$v['display_name']}</span>
            </div>
        </div>
        <div class="common-list-item auth-khbs">
            <div class="common-list-cell">
			    <span id="bundle_id_{$v['appid']}">{$v['bundle_id']}</span>
            </div>
        </div>
        <div class="common-list-item auth-khms">
            <div class="common-list-cell">
                <span id="custom_desc_{$v['appid']}">{$v['custom_desc']}</span>
            </div>
        </div>
        <div class="common-list-item auth-app overflow">
            <div class="common-list-cell">
                <span title="{$v['appkey']}" href="run.php?mid={$_INPUT['mid']}&a=see_appkey&appid={$v['appid']}&infrm=1">{$v['appkey']}</span>
            </div>
        </div>  
        <!--   
        <div class="common-list-item auth-zt">
            <div class="common-list-cell">
		       <div class="btn-box-cz" id="auth_status_{$v['appid']}" style="background:none">
		             <div class="auth-sh">{$v['status_name']}</div>
                     <div class="btn-box-cz-menu">
				           {if $v['status'] != 2}
					       <input type="button" value="审核" class="button_2" id="audit_button_{$v['appid']}"  />
					      {else}
					       <input type="button" value="打回" class="button_2" id="audit_button_{$v['appid']}"  />
					      {/if}
			         </div>
			    </div> 
            </div>
       </div>
       -->
       <div class="common-list-item auth-gqsj">
            <div class="common-list-cell">
                <span id="expire_time_{$v['appid']}">{if $v['expire_time']}{$v['expire_time']}{else}永久有效{/if}</span>
            </div>
        </div>
        <div class="common-list-item auth-cjsj">
            <div class="common-list-cell">
                <span>{$v['create_time']}</span>
            </div>
        </div>
   </div>
   <div class="common-list-biaoti ">
	    <div class="common-list-item auth-biaoti biaoti-transition">
			   <div class="common-list-cell">
			   <span class="common-list-overflow auth-biaoti-overflow m2o-common-title" id="custom_name_{$v['appid']}">{$v['custom_name']}</span>
            </div>  
	    </div>
   </div>
</li> 
