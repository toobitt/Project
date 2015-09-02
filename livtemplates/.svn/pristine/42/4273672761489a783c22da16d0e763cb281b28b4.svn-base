{template:head}
{css:common/common_form}
{css:2013/button}
{css:2013/form}
{css:2013/list}
{css:hg_sort_box}
{css:market-info}
{js:hg_sort_box}
{js:jqueryfn/jquery.tmpl.min}
{js:common/ajax_upload}
{js:2013/list}
{js:supermarket/special_commodity}
{code}
if($_relate_module)
{
	foreach($_relate_module AS $k => $v)
	{
		$tmp = explode('_',$v);
		$_relate_module[$k] = $tmp[1];
	}
}
/*append超市信息*/
$market_info = $market_info[0];
/*append门店信息*/
$market_store = $market_store[0];
/*append活动信息*/
$activity_info = $activity_info[0];
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
				<div class="m2o-main m2o-flex" market_id="{$market_info['id']}">
					<section class="market-box m2o-flex-one">
					<form action="./run.php?mid={$_INPUT['mid']}&market_id={$market_info['id']}&activity_id={$activity_info['id']}" method="post" enctype="multipart/form-data" class="market-list">
                       <div class="m2o-list">
					        <div class="m2o-title m2o-flex m2o-flex-center">
					        	<div class="commodity-adv" data-id={$activity_info['id']}>
					        		<a class="back" target="mainwin"><span class="commodity-slogan">{$activity_info['title']}</span></a>
					        	</div>
					        	<div class="choice-area m2o-flex-one">
					        		<div style="float:right;">
					        		{code}
					                    $state_item_source = array(
					                        'class' 	=> 'down_list',
					                        'show' 		=> 'state_show',
					                        'width'     => 88,
					                        'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
					                        'is_sub'	=>	1,
					                    );
					                    
					                    if($_INPUT['status'])
					                    {
					                    	$state_default = $_INPUT['status'];
					                    }
					                    else
					                    {
					                    	$state_default = -1;
					                    }
					                    $recomd_item_source = array(
					                        'class' 	=> 'down_list product_list',
					                        'show' 		=> 'recomd_show',
					                        'width'     => 88,
					                        'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
					                        'is_sub'	=>	1,
					                    );
					                    
					                    if($_INPUT['is_recommend'])
					                    {
					                    	$recomd_default = $_INPUT['is_recommend'];
					                    }
					                    else
					                    {
					                    	$recomd_default = -1;
					                    }
					                {/code}
					                {template:form/search_source,status,$state_default,$_configs['market_status'],$state_item_source}
					                {template:form/search_source,is_recommend,$recomd_default,$_configs['product_recommend'],$recomd_item_source}
					                </div>
					        	</div>
					        </div>
							<ul class="commodity-list clear">
								<li class="commodity-add">
									<div class="commodity-img commodity-default">
										新增商品
									</div>
								</li>
								{foreach $list AS $k => $v}
								<li class="commodity-each" _id="{$v['id']}" orderid="{$v['order_id']}">
									<div class="commodity-img">
										{code}
											if($v['index_pic']){
												$index_img = $v['index_pic']['host'].$v['index_pic']['dir'].'139x139/'.$v['index_pic']['filepath'].$v['index_pic']['filename'];
											}
										{/code}
										<img src="{$index_img}" />
										<p _id="{$v['id']}"  _status="{$v['status']}" class="reaudit" style="color:{$_configs['status_color'][$v['status']]}">{$v['status_format']}</p>
										<em class="zan {if $v['is_recommend']}agree-zan{/if}" zid="{$v['is_recommend']}"></em>
										<span class="del"></span>
										<a class="cover-layer"></a>
									</div>
						      		<h5>{$v['name']}</h5>
						      		<p class="price"><span class="current-price">{$v['now_price']}</span>/<span class="original-price">{$v['old_price']}</span></p>
								</li>
								{/foreach}
							</ul>
							{$pagelink}
					     </div>
					   </form>
					</section>
					<input type="file" name="index_pic" multiple="multiple" accept="image/png,image/jpeg" class="image-file" style="display: none;" />
					<aside class="market-info">
						<form method="post" action="run.php?mid={$_INPUT['mid']}&market_id={$market_info['id']}&activity_id={$activity_info['id']}" class="market-form">
							<aside class="market-edit">
							</aside>
						</form>
					</aside>
				</div>
			</div>
	</div>
</div>
<div class="type-select" style="display: none;">
	<div class="form-dioption-sort form-dioption-item" id="sort-box">
		<label style="color:#9f9f9f">分类： </label>
		<p style="display:inline-block;" class="sort-label common-head-drop" _multi="market_product_sort">{$product_sort_name}</p>
		<div class="sort-box-outer"><div class="sort-box-inner"></div></div>
		<input type="hidden" value="{$sort_default}" name="product_sort_id" id="sort_id" />
	</div>
</div>

<script type="text/x-jquery-tmpl" id="add-commodity-tpl">
	<div class="market-item market-mode">
		<label>${opera}商品</label>
		<input type="text" name="name" value="${nname}"/>
		<div class="market-figure">
			<img src="${index_img}" />
		</div>
		<ul class="pic-list clear"></ul>
		<input type="hidden" name="index_img_id" class="market-logo" value="${index_img_id}" />
	</div>
	<div class="market-item">
		<label>描述：</label>
		<textarea name="brief" cols="120" rows="3" placeholder="商品描述">${brief}</textarea>
	</div>
	<div class="market-item market-type">
		
	</div>
	<div class="market-item">
		<label>规格：</label>
		<input type="text" name="product_standard" value="${product_standard}"/>
	</div>
	<div class="market-item">
		<label>商品单位：</label>
		<input type="text" name="product_unit" value="${product_unit}"/>
	</div>
	<div class="market-item">
		<label>厂家：</label>
		<input type="text" name="vender" value="${vender}"/>
	</div>
	<div class="market-item">
		<label>原价：</label>
		<input type="text" name="old_price" value="${old_price}"/>
	</div>
	<div class="market-item">
		<label>现价：</label>
		<input type="text" name="now_price" value="${now_price}"/>
	</div>
	<div class="market-item">
		<label>外链：</label>
		<input type="text" name="url" value="${url}"/>
	</div>
	<div class="market-save">
		<input type="submit" value="${value}" class="save-pink"/>
		<input type="hidden" name="activity_id" value="${activity_id}" />
		<input type="hidden" name="a"  value="${method}" />
		<input type="hidden" name="market_id"  value="${market_id}" />
		<input type="hidden" name="id" value="${id}" />
	</div>
</script>

<script type="text/x-jquery-tmpl" id="add-pic-tpl">
	<li class="pic-each">
        <img src="${img_info}" />
		<span class="pic-del"></span>
		<input type="hidden"  name="img_id[]" value="${imginfoid}" />
	</li>
</script>