<li class="common-list-data clear"  id="r_{$v['id']}">
	<div class="common-list-left">
        <div class="common-list-item mem-paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]"><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}" /></a>	
            </div>
        </div>
        <div class="common-list-item mem-fengmian">
            <div class="common-list-cell">
                <img width="35" height="35" src="{$v['avatar']}" />
            </div>
        </div>
    </div>
	
	
	<div class="common-list-right">
	    <div class="common-list-item mem-cz">
            <div class="common-list-cell">
              <a id="cz" style="width:25px;position:relative;" title="操作" class="cz"> <em class="b4" style="margin-top: 0px;width: 20px;"></em>
			     <span style="display: none;z-index:999;width: 190px;margin-top:-12px;" class="show_opration_button">
				  <!--  <span onclick="window.location.href = './run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1'"  style="margin:4px 2px 0 0;" class="button_2">编辑</span> -->
				   <span onclick="hg_delete({$v['id']});" style="margin:4px 2px 0 0;" class="button_2">删除</span>
				   <span onclick="hg_audit({$v['id']}, 1);" style="margin:4px 2px 0 0;" class="button_2">审核</span>
				   <span onclick="hg_audit({$v['id']}, 2);" style="margin:4px 2px 0 0;" class="button_2">打回</span>
			   </span>
		    </a>
            </div>
        </div>
	    <div class="common-list-item mem-huiyuan">
            <div class="common-list-cell">
                <span>{$v['plat_name']}</span>
            </div>
        </div>
        
        <div class="common-list-item mem-jh">
            <div class="common-list-cell color">
                <span>
	            {if !$v['is_expired']}
	            	{$v['plat_expired_time']}
	            {else}
	            	<span style="cursor:pointer;color:red;" title="点击重新授权" onclick="hg_retset_oauthlogin({$v['id']});">授权已过期</span>
	            {/if}
                </span>
            </div>
        </div>
        <div class="common-list-item mem-zt">
            <div class="common-list-cell">
			    <span id="audit_{$v['id']}" name="status_dom">
			    {if $v['status'] == 1}已审核{elseif $v['status'] == 2}被打回{else}待审核{/if}
			    </span>
            </div>
        </div>
       
        <div class="common-list-item mem-sj">
            <div class="common-list-cell">
            	<span>{$v['user_name']}</span>
                <span style="color:#7B7B7B;font-size:9px;display: inline-block;">{$v['create_time']}</span>
            </div>
        </div>
	</div>
	<div class="mem-title biaoti-transition" style="margin-top:10px;cursor:pointer;">		
			<span id="sort_name_86" style="color:#333;padding-right:10px;margin-top:10px;">
			{$v['member_name']}</span>
		</a>
	</div>
</li>