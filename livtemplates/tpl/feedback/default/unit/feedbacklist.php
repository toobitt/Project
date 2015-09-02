<div class="m2o-each m2o-flex m2o-flex-center m2o-list-data" data-id="{$v['id']}" orderid="{$v['order_id']}">
    <div class="m2o-item m2o-paixu">
		<input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">
	</div>
	<div class="m2o-item m2o-flex-one m2o-bt" title="{$v['title']}">
		<a class="common-title" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"  target="formwin"  need-back>
			{if $v['indexpic']}
			{code}$index_pic = hg_fetchimgurl($v['indexpic'],40,30);{/code}
			<img src="{$index_pic}"/>
			{/if}
			<span style="max-width: 130px;" class="common-list-overflow vote-biaoti-overflow m2o-common-title" title="{$v['title']}">{$v['title']}</span>
		</a>
	</div>
	<div class="m2o-item m2o-col w80 color">
    {if ($v['column'])}
		    {foreach $v['column'] as $kk => $vv} 
		    	{code}$cu = $vv;{/code}
		    	{if ($v['column_url'][$kk])}
		    		{if (is_numeric($v['column_url'][$kk]))}
		    		<a href="./redirect.php?id={$v['column_url'][$kk]}" target="_blank"><span class="common-list-pub">{$cu}</span></a>
		    		{else}
						<a href="{$v['column_url'][$kk]}" target="_blank"><span class="common-list-pub">{$cu}</span></a>   			
		    		{/if}			
		    	{else}
		    		<span class="common-list-pre-pub">{$cu}</span>
		    	 {/if}  	
			{/foreach}
     {/if}
     </div>
      <div class="m2o-item m2o-style w80 color">{$v['sort_name']}</div>
	<div class="m2o-item m2o-col w80 color m2o-link">
			{if $v['url']}
			<span class="common-list-pub">查看链接</span>
			<div class="link-box">
				<a href="{$v['url']}">{$v['url']}</a>
			
			</div>
			{/if}
	</div>
	<div class="m2o-item m2o-audit w80" _status="{$v['status']}" style="color:{$_configs['status_color'][$v['status']]};" >{if $v['status']==0}待审核{else if $v['status']==1}已审核{else}已打回{/if}</div>
	{if $_configs['App_im']}
	<div class="m2o-item m2o-switch w80" _status="{$v['is_reply']}" >
    	<div class="common-switch {if $v['is_reply']}common-switch-on{/if}">
           <div class="switch-item switch-left" data-number="0"></div>
           <div class="switch-slide"></div>
           <div class="switch-item switch-right" data-number="100"></div>
        </div>
    </div> 
	<div class="m2o-item m2o-col w80 color">
		{if $v['new_reply']}<a class="common-list-pub" href="run.php?a=relate_module_show&app_uniq=feedback&mod_uniq=feedback_result&fid={$v['id']}&infrm=1" need-back><span class="common-list-pub">新回复</span></a>
		{else}<span class="color">无回复</span>
		{/if}		
	</div>
	{/if}
	<div class="m2o-item m2o-num w40"><a class="common-title color" href="run.php?a=relate_module_show&app_uniq=feedback&mod_uniq=feedback_result&fid={$v['id']}&infrm=1" need-back>
			<span class="common-list-pub ">{$v['counts']}</span></a></div>
	<div class="m2o-item m2o-num w40"><span class="color">{$v['processed_count']}</span></div>
	<div class="m2o-item m2o-num w40"><span class="color">{$v['unprocessed_count']}</span></div>
    <div class="m2o-item m2o-time w80">
            <div class="common-list-cell">
			    <a style="color:#7B7B7B;" {if $v['end_time']}title="{$v['end_time']}" {/if}>
			    <span class="time">
			    {if !$v['end_time_flag']}
					{if !$v['end_time']}<span style="color:#17b202;">永久有效</span>
					{else}<font style="color:red;">已过期</font>
					{/if}
				{else}
					{$v['end_time']}
				{/if}
				</span></a>
            </div>
    </div>
    <div class="m2o-item m2o-time">
        <span class="name">{$v['user_name']}</span>
        <span class="time">{$v['create_time']}</span>
    </div>
    <div class="m2o-item m2o-ibtn">
    </div>
</div>
