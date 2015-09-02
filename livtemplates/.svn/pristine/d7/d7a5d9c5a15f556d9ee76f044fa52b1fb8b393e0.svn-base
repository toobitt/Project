<li class="common-list-data clear"  id="r_{$v['id']}" _id="{$v['id']}"  name="{$v['id']}"   order_id="{$v['order_id']}">
	<div class="common-list-left">
        <div class="common-list-item common-paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" /></a>
            </div>
        </div>
        <div class="common-list-item content-slt">
            <div class="common-list-cell">
                <div style="width:40px;height:30px;overflow:hidden">
                {code}
                    $hg_attr['list'] = true;
                {/code}
                {template:unit/adv_mtype, adv, adv, $v}
                </div>
            </div>
        </div>
    </div>
	<div class="common-list-right">
        
        <div class="common-list-item content-zt wd50">
            <div class="common-list-cell">
                 <a id="status_{$v['id']}"><span style="color:{$_configs['status_color'][$v['status']]}">{$_configs['status_search'][$v['status']]}</span></a>
            </div>
        </div>
        <div class="common-list-item wd80">
            <div class="common-list-cell">
              <span id="uniqueid_{$v['id']}">
              	{if $v['customer_name']}
              	{$v['customer_name']}
              	{else}
              	本站投放
              	{/if}
              </span>
            </div>
        </div>
        <div class="common-list-item wd50">
            <div class="common-list-cell">
			    {if $v['distribution']}
					<div style="color:#8397BC;position:relative;">
						<i class="hg_icons earth_icon {if $v['status']==6}earth_icon_gray{/if}" onmouseover="$(this).next().show();" onmouseout="$(this).next().hide();"></i>
						<span class="fb_column" style="display:none;position:absolute;left:-9px;top:32px;z-index:10;background:#fff;border:1px solid #69A3D8;border-radius:2px;box-shadow:0 0 8px #ccc;">
							<span class="fb_column_m" style="background:none;white-space:normal;width:127px;">
								<em style="width:16px;top:-6px;"></em>
								<span class="fsz" style="margin-top:0;">
									{foreach $v['distribution'] as $i=>$p}
									{if $p}
									{code}echo implode('=>',$p){/code}
									{/if}
									{/foreach}
								</span>
							</span>
						</span>
					</div>
				{else}
					待发布
				{/if}
            </div>
        </div>
        {if isset($_INPUT['_id'])}
        <div class="common-list-item">
            <div class="common-list-cell">
                <span id="outside_ip_{$v['id']}">{$v['output']}/{$v['click']}</span>
            </div>
        </div>
        {/if}
        <div class="common-list-item content-fbr wd100">
            <div class="common-list-cell">
                <span class="common-user">{$v['user_name']}</span>
                <span class="common-time">{$v['create_time']}</span>
            </div>
        </div>
   </div>
   <div class="common-list-i" onclick="hg_show_opration_info({$v['id']});"></div>
   <div class="common-list-biaoti ">
	    <div class="common-list-item content-biaoti biaoti-transition">
			   <div class="common-list-cell">
		          <span id="t_{$v['id']}" class="common-title m2o-common-title" href="./run.php?mid={$_INPUT['mid']}&a=content_form&id={$v['id']}&infrm=1">{$v['title']}</span>
            </div>  
	    </div>
   </div>
</li>