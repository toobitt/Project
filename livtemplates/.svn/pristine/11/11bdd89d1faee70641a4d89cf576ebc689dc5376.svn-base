<li class="magazine-each" data-id="{$v['id']}" data-magid="{$v['magazine_id']}">
	<input type="checkbox" name="infolist[]" value="{$v[$primary_key]}" class="m2o-check" />
	<div class="mag-img">
		<img src="{$v['url']}" />
		{code}
			if($v['audit'] == "已审核" ){
				$op = 'back';
			}else{
				$op = 'audit';
			}
		{/code}
		<p><em class="m2o-state audit" data-method="{$op}" _id="{$v['id']}" _status="{$v['state']}" style="color:{$_configs['status_color'][$v['state']]};" >{$v['audit']}</em></p>
		<a class="newest-href" href="./run.php?a=relate_module_show&app_uniq=magazine&mod_uniq=maga_article&mod_a=show&maga_id={$_INPUT['maga_id']}&issue_id={$v['id']}&maga_name={$v['name']}&infrm=1"></a>
	</div>
	<h4>{$v['year']}第{$v['issue']}期 总{$v['volume']}期</h4>
	<p><span>{$v['user_name']}</span>{$v['create_time']}</p>
	<a class="del" data-method="delete"></a>
</li>