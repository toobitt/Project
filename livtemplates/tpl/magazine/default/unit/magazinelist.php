<li class="magazine-each" data-id="{$v['id']}" data-issueid="{$v['issue_id']}" >
	<input type="checkbox" name="infolist[]" value="{$v[$primary_key]}" class="m2o-check" />
	<div class="mag-img {if !$v['url']}mag-noImg{/if}">
		<a class="newest-href" title="进入往期列表" href="./run.php?a=relate_module_show&app_uniq=magazine&mod_uniq=issue&maga_id={$v['id']}&cur_nper={$v['current_nper']}&infrm=1" target="mainwin">
			{if $v['url']}
			<img src="{$v['url']}" />
			{else}
			暂无封面
			{/if}
		</a>
		<p><em class="m2o-state" data-method="audit" _id="{$v['id']}" _status="{$v['state']}" style="color:{$_configs['status_color'][$v['state']]};" >{$v['audit']}</em>{$v['sort_name']}/{$_configs['release_cycle'][$v['release_cycle']]}</p>
		<!-- <a class="newest-href" href="./run.php?a=relate_module_show&app_uniq=magazine&mod_uniq=maga_article&mod_a=show_last_issue&cur_nper={$v['current_nper']}&infrm=1" target="mainwin"></a>
		<a class="period-href" title="往期列表" href="./run.php?a=relate_module_show&app_uniq=magazine&mod_uniq=issue&maga_id={$v['id']}&cur_nper={$v['current_nper']}&infrm=1" target="mainwin"></a> -->
	</div>
	<h4>{$v['name']} {if $v['issue_id']}{$v['year']}第{$v['issue']}期{/if}</h4>
	<p><span>{$v['user_name']}</span>{$v['create_time']}</p>
	<a class="del" data-method="delete"></a>
</li>