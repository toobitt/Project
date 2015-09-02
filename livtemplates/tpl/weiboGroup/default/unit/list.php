<li class="common-list-data clear" id="r_{$v['id']}"  name="{$v['id']}"   orderid="{$v['order_id']}"  cname="{$v['cid']}" corderid="{$v['order_id']}">
	<div class="common-list-left">
        <div class="common-list-item circle-paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>
    </div>
	<div class="common-list-right">
		<div class="common-list-item wd80">
			<div class="common-list-cell">
		    <div>
			{if is_array($v['circle_id']) && count($v['circle_id'])>0}
				{foreach $v['circle_id'] as $kk => $vv}		
			         <a><span class="common-list-pub" >{$vv}</span></a>
			    {/foreach}
			{/if}
			</div>
		    </div>
		</div>    
	    <div class="common-list-item wd60">
            <div class="common-list-cell">
                <div class="common-switch-status"><span id="statusLabelOf{$v['id']}" _id="{$v['id']}" _state="{$v['state']}" style="color:{$list_setting['status_color'][$v['status']]};">{$v['status']}</span></div>
            </div>
        </div>
        <div class="common-list-item wd80">
            <div class="common-list-cell">
                <span class="m2o-common-title">{$v['group_name']}</span>
            </div>
        </div>
        <div class="common-list-item" style="width:150px;">
            <div class="common-list-cell">
			   <span class="common-user">{$v['uname']}</span>
			   <span class="common-time">{$v['create_time']}</span>
            </div>
        </div>
   </div>
   <div class="common-list-biaoti min-wd" style="cursor:pointer;" onclick="hg_show_opration_info({$v['id']});">
	    <div class="common-list-item biaoti-transition">
		   <div class="common-list-cell">
	            {code}
				 	$pic = '';
				  	if($v['img'])
				  	{
				  		$pic = $v['img'][0]['host'] . $v['img'][0]['dir'] . $v['picsize']['thumbnail'] . $v['img'][0]['filepath'] . $v['img'][0]['filename'];
				  	}
				{/code}
				{if $pic}<img src="{$pic}" style="width:40px;height:30px;margin-right:10px;" />{else}{/if}<span class="common-list-overflow max-wd" style="max-width:350px;" id="title_{$v['id']}">{$v['text']}</span>
           </div>
	    </div>
   </div>
</li> 
                