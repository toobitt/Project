<li class="common-list-data public-list clear" id="r_{$v['id']}"  name="{$v['id']}"   orderid="{$v['order_id']}"  cname="{$v['cid']}" corderid="{$v['order_id']}">
	<div class="common-list-left">
        <div class="common-list-item" style="width:35px;">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>
    </div>
	<div class="common-list-right">
        <div class="common-list-item  wd50">
            <div class="common-list-cell" style="padding-left:5px;">
                    <a title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&site_id={$v['site_id']}&infrm=1"><em class="b2" style="background-position: -60px -24px;width:16px;height:16px;margin:10px 0 0 0 ;"></em></a>
            </div>
        </div>
        <div class="common-list-item  wd50">
            <div class="common-list-cell" style="padding-left:5px;">
                    <a title="删除" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3" style="background-position: -64px -118px;width:16px;height:16px;margin:10px 10px 0 0 ;"></em></a>
            </div>
        </div>	
		<div class="common-list-item wd80">
			<div class="common-list-cell">
				<span>{$v['mark']}</span>
		    </div>
		</div>   	
		<div class="common-list-item wd60">
			<div class="common-list-cell">
				<span>{if $v['site_name']}{$v['site_name']}{else}共享套系{/if}</span>
			</div>
		</div>    
	    <div class="common-list-item wd60">
            <div class="common-list-cell">
            	{code}
            		switch($v['state'])
            		{
            			case 0:
            				$v['status'] = '未启用';
            				break;
            			case 1:
            				$v['status'] = '启用';	
            				break;
            			default:
            				break;
            		}
            	{/code}
                <div class="common-switch-status"><span id="statusLabelOf{$v['id']}" _id="{$v['id']}" _state="{$v['state']}" style="color:{$list_setting['status_color'][$v['state']]};">{$v['status']}</span></div>
            </div>
        </div>
        <div class="common-list-item wd60">
            <div class="common-list-cell">
            	{code}
            		switch($v['isusing'])
            		{
            			case 0:
            				$v['using'] = '否';
            				break;
            			case 1:
            				$v['using'] = '是';
            				break;
            			default:
            				break;
            		}
            	{/code}
                <span style="color:{$list_setting['status_color'][$v['isusing']]};">{$v['using']}</span>
            </div>
        </div>
        <div class="common-list-item wd100">
            <div class="common-list-cell">
			   <span class="common-user">{$v['user_name']}</span>
			   <span class="common-time">{$v['create_time']}</span>
            </div>
        </div>
   </div>
   <div class="common-list-biaoti" style="cursor:pointer;">
	    <div class="common-list-item biaoti-transition">
		   <div class="common-list-cell">
	            {code}
				 	$pic = '';
				  	if($v['pic'][0])
				  	{
				  		$pic = $v['pic'][0]['host'] . $v['pic'][0]['dir'] . "40x30/" . $v['pic'][0]['filepath'] . $v['pic'][0]['filename'];
				  	}
				{/code}
				{if $pic}
					<img src="{$pic}" style="width:40px;height:30px;margin-right:10px;" />
				{else}
				{/if}
				<span class="common-list-overflow max-wd m2o-common-title" id="title_{$v['id']}">{$v['title']}</span>
           </div>
	    </div>
   </div>
</li> 
                