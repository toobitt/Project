<li class="common-list-data clear"  id="r_{$v['id']}">
	<div class="common-list-left">
        <div class="common-list-item paixu">
                <a class="lb" name="alist[]"><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}" /></a>	
        </div>
    </div>
	
	
	<div class="common-list-right">
	    
	    <div class="common-list-item mem-huiyuan">
                <span>{if $v['node_name']}{$v['node_name']}{else}网友注册{/if}</span>
        </div>
        <div class="common-list-item mem-email wd150 overflow">
                <span>{$v['email']}</span>
        </div>
        <div class="common-list-item mem-zc overflow wd100">
                 <span>{$v['ip']}</span>
        </div>
        <div class="common-list-item mem-zt">
			<div class="common-switch-status">
		     <span _id="{$v['id']}" _state="{$v['status']}" id="statusLabelOf{$v['id']}" style="color:{$list_setting['status_color'][$v['status']]};">{if $v['status']}已审核{else}待审核{/if}</span>
			</div>
        </div>
        <div class="common-list-item mem-jh">
                <span>{if $v['is_email']}<span style="color:#17b202;">已激活</span>{else}未激活{/if}</span>
        </div>
        <div class="common-list-item mem-sj wd150">
                <span class="common-time">{$v['create_time']}</span>
        </div>
	</div>
	
	
	
	<div class="common-list-i" onclick="hg_show_opration_info({$v['id']})"></div>
	
	
	
	
	<div class="common-list-biaoti min-wd">
	    <div class="common-list-item biaoti-transition">
	      <div class="common-list-overflow max-wd">
	      	<a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
		    	{if $v['avatar_url']}
		        <img width="35" height="35" src="{$v['avatar_url']}" class="biaoti-img" />
		    	{/if}
		   		<span id="title_{$v['id']}" class="common-title m2o-common-title">{$v['member_name']}</span>
			</a>
		   </div>
		</div>
   </div>
	
</li>