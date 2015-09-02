{code}
	$img = ( $v['indexpic_url'] ? '<img style="margin-right:10px" src='.$v['indexpic_url'].' width="40" height="30" />' : '' );
{/code}
<div class="m2o-each m2o-flex m2o-flex-center" data-id="{$v['id']}" orderid="{$v['order_id']}">
    <div class="m2o-item m2o-paixu">
    	<input type="checkbox"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  name="infolist[]" class="m2o-check" />
	</div>
    <div class="m2o-item m2o-flex-one m2o-bt">
       <div class="m2o-title-transition max-wd">
    	 <a class="m2o-title-overflow"  href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&issue_id={$_INPUT['issue_id']}&infrm=1" target="formwin">
    	 	{$img}
            <span>{$v['title']}</span>
         </a>
       </div>
    </div>
    <div class="m2o-item m2o-classify">{$v['sort_name']}</div>
    <div class="m2o-item m2o-author">{$v['article_author']}</div>
    <div class="m2o-item m2o-editor">{$v['redactor']}</div>
    {code}
		if($v['audit'] == "已审核" ){
			$op = 'back';
		}else{
			$op = 'audit';
		}
	{/code}
    <div class="m2o-item m2o-state" data-method="{$op}"_status="{$v['state']}" _id="{$v['id']}" style="color:{$_configs['status_color'][$v['state']]};" >{$v['audit']}</div>
    <div class="m2o-item m2o-time">
        <span class="name">{$v['user_name']}</span>
        <span class="time">{$v['create_time']}</span>
    </div>
    <div class="m2o-item m2o-ibtn">
    </div>
    <span class="title" style="overflow:auto;position:static;">
		<span class="fb_column"  style="display:none;"   id="conPub_{$v['id']}" >
			<span class="fb_column_l"></span>
			<span class="fb_column_r"></span>
			<span class="fb_column_m"><em></em><span class="fsz">发送至网站：</span>
			{if $v['pubinfo'][1]}
				{foreach $v['pubinfo'][1] as $c}
					<a class="overflow">{$c}</a>
				{/foreach}
			{/if}
			</span>
		</span>
		<span class="fb_column phone"  style="display:none;"   id="conPhone_{$v['id']}" >
			<span class="fb_column_l"></span>
			<span class="fb_column_r"></span>
			<span class="fb_column_m"><em></em><span class="fsz" >发送至手机：</span>
				{if $v['pubinfo'][2]}
					{foreach $v['pubinfo'][2] as $c}
						<a class="overflow">{$c}</a>
					{/foreach}
				{/if}
			</span>
		</span>
	</span>
</div>
