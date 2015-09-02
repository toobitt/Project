<li class="common-list-data clear"  id="r_{$v['id']}" _id="{$v['id']}" order_id="{$v['order_id']}" name="{$v['id']}">
	<div class="common-list-left">
        <div class="common-list-item paixu">
                <a class="lb" name="alist[]"><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}" /></a>	
        </div>
    </div>
	
	
	<div class="common-list-right">
	     <div class="common-list-item seekhelp-tszt wwd100"  style="cursor:pointer;">
                <span id="push_{$v['id']}" {if $v['is_push']}style="color: green"{/if}
                  {if $personal_auth['is_complete'] || in_array('audit', $personal_auth['action'])} 
                  onclick="push({$v['id']},{$v['is_push']});" 
                  {/if}
                  >{$v['push_status']}</span>
        </div>
	    <div class="common-list-item seekhelp-qzdx wwd100">
                <span>{if $v['account_name']}{$v['account_name']}{else}-----{/if}</span>
        </div>
        <div class="common-list-item seekhelp-fl wwd100">
                <span>{$v['sort_name']}</span>
        </div>
        <div class="common-list-item seekhelp-fl wwd100">
        			{if $v['is_reply']==1}
                <span style="color:green;">已回复</span>
                {else}
                <span style="color:#8fa8c6;">未回复</span>
                {/if}
        </div>
        <div class="common-list-item seekhelp-zc wwd100 change-status" style="cursor:pointer;">
                 <span class="current-status"  id="audit_{$v['id']}" style="color:{if $v['status']==1}#17b202{elseif $v['status']==2}#f8a6a6{elseif $v['status']==3}#51677b{/if}" 
                 {if $personal_auth['is_complete'] || in_array('audit', $personal_auth['action'])} 
                 onclick="change_status({$v['id']},{$v['status']});" 
                 {/if}
                 >{$v['status_name']}</span>
        </div>
        <div class="common-list-item seekhelp-sj wd150">
        	<div class="common-list-cell">
                 <span class="common-user">{$v['member_name']}</span>
                 <span class="common-time">{$v['format_create_time']}</span>
            </div>
        </div>
	</div>
	{if $personal_auth['is_complete'] || in_array('audit', $personal_auth['action'])}
	<div class="common-list-i" onclick="hg_show_opration_info({$v['id']})"></div>
	{/if}
	<div class="common-list-biaoti min-wd">
	    <div class="common-list-item biaoti-transition">
	      <div class="common-list-overflow max-wd">
	      	<a href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="formwin">
		   		{code}
					$tip = '';
					if ($v['banword'] && is_array($v['banword']))
					{
						foreach ($v['banword'] as $key=>$val)
						{
							if ($val)
							{
								switch($key)
								{
									case 'title'	: $tip .= '标题'.','; break;
									case 'content' 	: $tip .= '描述'.','; break;
									case 'reply'    : $tip .= '回复'.','; break;
								}
							}
						}
					}
					$tip = $tip ? rtrim($tip,',') : '' ;
				{/code}
				
		   		<span id="title_{$v['id']}" class="common-title">{if $tip}
					<font color="red"><span class="m2o-common-title">{$v['title']}</span></font>
					{else}
					<span class="m2o-common-title">{$v['title']}</span>
				{/if}
		   		&nbsp;&nbsp;
		   		{if $v['is_img']}
		   			<img src ="{$RESOURCE_URL}vote_files.png"/>&nbsp;&nbsp;
		   		{/if}
				{if $v['is_video']}
					<img src ="{$RESOURCE_URL}hg_play_go.png"/>
				{/if}
		   		</span>
			</a>
		   </div>
		</div>
   </div>
	
</li>