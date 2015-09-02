<li class="common-list-data clear"  id="r_{$v['appid']}"    name="{$v['appid']}"   orderid="{$v['appid']}">
    <div class="common-list-left">
        <div class="common-list-item common-paixu">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['appid']}" title=""  /></a>
        </div>
    </div>
	<div class="common-list-right">
        <!--  <div class="common-list-item">
                <a class="btn-box" onclick="hg_showAddShare({$v['appid']});"><em class="b2"></em></a>
        </div>-->
        <div class="common-list-item wd90">
                 <span>{$v['appid']}</span>
        </div>
        <div class="common-list-item wd180 overflow">
              <span id="shareplat_{$v['appid']}">
                    		{code}
                    			foreach(explode(',',$share_app_list[0]['auth_data'][$v['appid']]['platIds']) as $k1=>$v1)
                    			{
                    				echo $share_app_list[0]['platdata'][$v1]."&nbsp;&nbsp;&nbsp;";
                    			}
                    		{/code}
               </span>
        </div>
        <div class="common-list-item wd80">
			    <span id="share_status_{$v['appid']}">{if !empty($share_app_list[0]['auth_data'][$v['appid']])}{if $share_app_list[0]['auth_data'][$v['appid']]['status']==2}关闭{else}<span style="color:#17b202;">启用</span>{/if}{else}<span style="color:#17b202;">启用</span>{/if}</span>
        </div>
        <div class="common-list-item wd120">
			    <span class="common-time" id="updatetimea_{$v['appid']}">{code}echo date('Y-m-d H:i',$v['updatetime']){/code}</span>
        </div>
   </div>
   <div class="common-list-i" onclick="hg_show_opration_info({$v['appid']});"></div>
   <div class="common-list-biaoti ">
	    <div class="common-list-item admin-biaoti biaoti-transition">
			   <span class="common-list-overflow">
		         <a class="common-title" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['appid']}&infrm=1">
		         <span class="m2o-common-title">{$v['custom_name']}</span></a>	
               </span>
	    </div>
   </div>
</li> 
                