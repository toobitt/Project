<li class="magazine-each" data-id="{$v['id']}" data-issueid="{$v['issue_id']}" >
	<input type="checkbox" name="infolist[]" value="{$v[$primary_key]}" class="m2o-check" />
	<div class="mag-img {if !$v['url']}mag-noImg{/if}">
		<a class="newest-href" title="进入往期列表" href="run.php?a=form&mid={$_INPUT['mid']}&id={$v['id']}" target="formwin">
			{if $v['index_pic']}
			<img src="{$v['index_pic']['host']}{$v['index_pic']['dir']}{$v['index_pic']['filepath']}{$v['index_pic']['filename']}" />
			{else}
			暂无封面
			{/if}
		</a>
		<p><em class="m2o-state" data-method="audit" _id="{$v['id']}" _status="{$v['status']}" style="color:{$_configs['status_color'][$v['status']]};" >{$v['audit']}</em>{$v['title']}</p>
	</div>
	
	<h4>{$v['title']}</h4>
	<p>{$v['create_time']}</p>
	<a class="del" data-method="delete"></a>
</li>