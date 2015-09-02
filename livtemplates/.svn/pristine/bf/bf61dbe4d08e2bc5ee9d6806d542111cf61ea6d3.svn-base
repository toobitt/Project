<li class="market-each" id="r_{$v['id']}" name="{$v['id']}" _id="{$v['id']}"  orderid="{$v['order_id']}">
	<div class="market-name">
		<a href="./run.php?mid={$relate_module_id}&market_id={$v['id']}" target="formwin">
		<span class="m2o-common-title">{$v['market_name']}</span></a>
		<input type="text" value="{$v['market_name']}" name='market_name' class="market-head"/>
		<span class="mk-save save">保存</span>
	</div>
	<div class="market-content">
		<div class="market-info m2o-flex">
		    <div class="market-img">
		    	{code}
		    		$logo = '';
	    			if($v['logo']){
						$logo = $v['logo']['host'].$v['logo']['dir'].'55x55/'.$v['logo']['filepath'].$v['logo']['filename'];
					}
				{/code}
				{if $logo}
					<img src="{$logo}" class="mk-logo" width="55" height="55" id="img_{$v['id']}"/>
				{else}
					<img src="{$RESOURCE_URL}market/default_logo.png" class="mk-logo" width="55" height="55" id="img_{$v['id']}"/>
				{/if}
				<a class="cover-img"></a>
				<input type="hidden" name="logo_id" class="market-logoid" value="{$v['logo_id']}" />
		        <span _id="{$v['id']}"  _status="{$v['status']}" class="reaudit" style="color:{$_configs['status_color'][$v['status']]}">{$v['status_format']}</span>
		    </div>
		    <div class="market-profile m2o-flex-one">
			    <div class="market-intro"><label>门店：</label><span>{$v[total_store]}</span></div>
			    <div class="market-intro"><label>会员：</label><span>{$v['bind_member']}/{$v['total_member']}</span></div>
			    <div class="market-intro"><label>商品：</label><span>{$v['featured_product']}/{$v['total_product']}</span></div>
		    </div>
	    </div>
		<div class="market-time">
				<span>{$v['update_user_name']}</span><span>{$v['create_time']}</span><em class="edit"></em><em class="del"></em>
		</div>
	</div>
</li>				
