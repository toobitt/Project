{code}
	$index_img = $v['pic'] ?$v['pic']['host'].$v['pic']['dir'].'40x30/'.$v['pic']['filepath'].$v['pic']['filename'] : '';
{/code}
<div class="m2o-each m2o-flex m2o-flex-center" data-id="{$v['id']}" orderid="{$v['order_id']}">
    <div class="m2o-item m2o-paixu">
		<input type="checkbox" value="{$v['id']}" title="{$v['id']}" name="infolist[]" class="m2o-check" style="visibility: visible;">
	</div>
	<div class="m2o-item m2o-flex-one m2o-bt" title="{$v['title']}">
		 <div class="m2o-title-transition m2o-title-overflow">
			 <a href="modify.php?app_uniqueid=news&mod_uniqueid=news&id={$v['id']}" target="formwin">
				{if $index_img}<img style="margin-right:10px" src="{$index_img}" width="40" height="30">{/if}
				<span>{$v['title']}</span>
			</a>
		 </div>
	</div>
	<div class="m2o-item m2o-gather">
		{if $v['pub']}
			{foreach $v['pub'] as $key=>$val}
				{if $v['pub_url']}
					{code} $set_url_keys = array_keys($v['pub_url']); {/code}
					{if in_array($key, $set_url_keys)}
					<span class = "common-list-pub"_key="{$key}">{$val}</span>
					{else}
					<span class="common-list-pre-pub"_key="{$key}">{$val}</span>
					{/if}
				{else}
					<span class="common-list-pre-pub" _key="{$key}">{$val}</span>
				{/if}
			{/foreach}
		{/if}
	</div>
   	<div class="m2o-item m2o-audit" _status="{$v['state']}" style="color:{$_configs['status_color'][$v['state']]};" >{$v['status']}</div>
    <div class="m2o-item m2o-time">
        <span class="name">{$v['user_name']}</span>
        <span class="time">{$v['cre_time']}</span>
    </div>
    <div class="m2o-item m2o-ibtn">
    </div>
</div>
