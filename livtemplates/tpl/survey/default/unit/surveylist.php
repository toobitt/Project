<div class="m2o-each m2o-flex m2o-flex-center" data-id="{$v['id']}" orderid="{$v['order_id']}">
    <div class="m2o-item m2o-paixu">
		<input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">
	</div>
	<div class="m2o-item m2o-flex-one m2o-bt" title="{$v['title']}">
		<a class="common-title" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="formwin" need-back>
			{code}
			 $indexpic_url = hg_fetchimgurl($v['indexpic']);
			{/code}
			{if  $indexpic_url}
			<img src="{$indexpic_url}" />
			{/if}
			<span class="common-list-overflow vote-biaoti-overflow m2o-common-title" title="{$v['title']}">{$v['title']}</span>
		</a>
	</div>
	  <div class="m2o-item m2o-audit" _status="{$v['status']}" style="color:{$_configs['status_color'][$v['status']]};" >{if $v['status']==0}待审核{else if $v['status']==1}已审核{else}已打回{/if}</div>
    <div class="m2o-item m2o-sort color">
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
     <div class="m2o-item m2o-link w80">
		<div class="link-url{if $v['url']} hasurl{/if}">
			<span class="common-list-pub">查看链接</span>
			<div class="link-box">
				<a href="{$v['url']}">{$v['url']}</a>
			</div>
		</div>
     </div>
	<div class="m2o-item m2o-num w80 color"><a href="./run.php?mid={$_INPUT['mid']}&a=show_result&id={$v['id']}&infrm=1" need-back>{$v['used_survey_id']}</a></div>
    <div class="m2o-item m2o-style w80 color">{$v['problem_num']}</div>
    <div class="m2o-item m2o-style w80 color">{$v['sort_name']}</div>
    <div class="m2o-item m2o-time">
        <span class="name">{$v['user_name']}</span>
        <span class="time">{$v['create_time']}</span>
    </div>
    <div class="m2o-item m2o-ibtn">
    </div>
</div>
