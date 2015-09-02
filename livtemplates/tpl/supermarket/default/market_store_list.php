{template:head}
{css:2013/button}
{css:2013/form}
{css:market-info}
{js:jqueryfn/jquery.tmpl.min}
{js:common/common_form}
{js:common/ajax_upload}
{js:supermarket/store_list}
{code}
if($_relate_module)
{
	foreach($_relate_module AS $k => $v)
	{
		$tmp = explode('_',$v);
		$_relate_module[$k] = $tmp[1];
	}
}
$market_info = $market_info[0];
{/code}
<div class="wrap clear">
	<div class="market-wrap">
		<header class="m2o-header">
	    	<div class="market-inner">
		    	<div class="m2o-flex m2o-flex-center">
			    	<div class="market-title">
			    		{if $market_info['logo']}
			    		<img src="{$market_info['logo']}" />
			    		{else}
			    		<img src="{$RESOURCE_URL}market/default_logo_white.png" />
			    		{/if}
			    		<h3>{$market_info['market_name']}</h3>
			    	</div>
			    	<div class="m2o-m m2o-flex-one">
			    		<ul class="market-menu">
			    			{foreach $_relate_module AS $_m => $_b}
			    			<li class="market-index {if $_m == $_INPUT['mid']}selected{/if}"><a href="run.php?mid={$_m}&market_id={$_INPUT['market_id']}">{$_b}</a></li>
			    			{/foreach}
			    		</ul>
			    	</div>
			    	<div class="m2o-r">
			    		<a class="close-button2 option-iframe-back"></a>
			    	</div>
		    	</div>
	    	</div>
	    </header>
		<div class="m2o-inner"> 
			<div class="m2o-main m2o-flex" data-id="{$_INPUT['market_id']}">
				<section class="market-box m2o-flex-one">
					<ul class="subbranch-list clear">
						<li class="subbranch-add">
							新增门店
						</li>
                        {foreach $list as $k=>$v}
						<li class="subbranch-each" id="r_{$v['id']}" name="{$v['id']}" _id="{$v['id']}"  orderid="{$v['order_id']}">
							<div class="subbranch-item subbranch-title">
							<span class="m2o-common-title">{$v['name']}</span></div>
							<em class="del"></em>
							<div class="subbranch-item">
								<label>地址：</label>
								<span>{$v['address']}</span>
							</div>
							<div class="subbranch-item">
								<label>时间：</label>
								<span>{$v['opening_time']}</span>
							</div>
							<div class="subbranch-item">
								<label>电话：</label>
								{foreach $v['tel'] as $num=>$tel}
								<span>{$tel}{if ($v['tel'][$num+1])},{/if}</span>
								{/foreach}
							</div>
						</li>
						{/foreach}
					</ul>
					{$pagelink}
				</section>
				<input type="file" name="index_pic" multiple="multiple" accept="image/png,image/jpeg" class="image-file" style="display: none;" />
	     		<aside class="market-info">
		     		<form method="post" action="run.php?mid={$_INPUT['mid']}&market_id={$_INPUT['market_id']}" name="vod_sort_listform" class="market-form">
						<div class="market-edit">
						</div>
						<div class="market-map">
						{code} 
							$hg_bmap = array(
								'height' => 280,
								'width'  => 300,
								'longitude' => $baidu_longitude? $baidu_longitude : '0', 
								'latitude'  => $baidu_latitude? $baidu_latitude : '0',
								'zoomsize'  => 13,
								'areaname'  => $city_name?$city_name:$_configs['city']['name'],
								'is_drag'   => 1,
							);
						{/code}
						{template:map/baidu_map,baidu_longitude,baidu_latitude,$hg_bmap}
					</div>
					<div class="market-save">
						<input type="submit" value="新增" class="save-pink"/>
					</div>
					</form>
				</aside>
			</div>
		</div>
	</div>
</div>

<script type="text/x-jquery-tmpl" id="add-subbranch-tpl">
	<div class="market-item market-mode">
		<label>${opera}门店</label>
		<input name="name" type="text" value="${nname}"/>
		<div class="market-figure">
			<img src="${index_pic}" />
		</div>
		<ul class="pic-list clear">
			
		</ul>
		<input type="hidden" name="logo" class="market-logo" value="${index_pic_id}" />
	</div>
	<div class="market-item">
		<label>地址：</label>
		<input name="address" type="text" value="${address}" id="detailed_address"/>
	</div>
	<div class="market-item">
		<label>时间：</label>
		<input name="opening_time" type="text" value="${opening_time}"/>
	</div>
	<div class="market-item market-tel">
		<label>电话：</label>
		<div class="tel-list">
		 <span class="tel-each"><input name="tel[]" type="text" value=""/><em class="add"></em></span>
		</div>
	</div>
	<div class="market-item">
		<label>车位：</label>
		<input name="parking_num" type="text" value="${parking_num}"/>
	</div>
	<div class="market-item">
		<label>简介：</label>
		<textarea name='brief' cols="120" rows="3" placeholder="商家超市简介">${brief}</textarea>
	</div>
	<div class="market-item">
		<label>交通：</label>
		<textarea name='traffic' cols="120" rows="3" placeholder="交通线路">${traffic}</textarea>
	</div>
	<div class="market-item">
		<label>免费班车：</label>
		<textarea name='free_bus' cols="120" rows="3" placeholder="免费班车">${free_bus}</textarea>
	</div>
	<div class="market-item market-time">
		<label></label>
		<span>${update_user_name} ${update_time}</span>
	</div>
	<input type="hidden" name="id" value="${id}" />
	<input type="hidden" name="a" value="${method}"/>
    <input type="hidden" name="order_id" value="${order_id}"/>	
</script>
<script type="text/x-jquery-tmpl" id="add-tel-tpl">
	 <span class="tel-each tel-mode"><input name="tel[]" type="text" value="${telp}"/><em class="add"></em></span>
</script>
<script type="text/x-jquery-tmpl" id="add-pic-tpl">
	<li class="pic-each">
        <img src="${logot}" />
		<span class="pic-del"></span>
		<input type="hidden"  name="logo_id[]" value="${logotid}" />
	</li>
</script>
