{template:head}
{css:2013/button}
{css:2013/form}
{css:2013/list}
{css:market-info}
{js:jqueryfn/jquery.tmpl.min}
{js:common/common_form}
{js:2013/list}
{js:supermarket/special_offer_activity}
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
//print_r($list);
{/code}
{js:supermarket/special_offer_activity}
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
				<div class="m2o-main m2o-flex" tid="t_02">
					<section class="market-box m2o-flex-one">
						<form action="./run.php?mid={$_INPUT['mid']}&market_id={$market_info['id']}&activity_id={$activity_info['id']}" method="post" enctype="multipart/form-data" class="market-list">
                       <div class="m2o-list">
					        <div class="m2o-title m2o-flex m2o-flex-center">
					        	<div class="choice-area m2o-flex-one">
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
					                {/code}
					                {template:form/search_source,status,$state_default,$_configs['activity_status'],$state_item_source}
					                <div class="key-search">
					                	<input type="text" name="k" class="search-k" value="{$_INPUT['k']}" speech="speech" x-webkit-speech="x-webkit-speech" x-webkit-grammar="builtin:translate" placeholder="内容标题搜索">
					                </div>
					                <input type="submit" class="serach-btn" value=""/>
					        	</div>
					        	<div class="member-menu">
					        		<a class="mem-pink">新增活动</a>
					        	</div>
					        </div>
							<div class="m2o-each-list">
								<div class="m2o-each m2o-flex m2o-flex-center">
						        	<div class="m2o-item m2o-paixu" title="排序">
						        		<a title="排序模式切换/ALT+R" class="common-list-paixu"></a>
						        	</div>
						        	<div class="m2o-item m2o-flex-one m2o-bt common-list-biaoti" title="特惠活动">特惠活动</div>
						            <div class="m2o-item m2o-goodsnum" title="商品数">商品数</div>
						            <div class="m2o-item m2o-state" title="状态">状态</div>
						            <div class="m2o-item m2o-operate" title="操作">操作</div>
						            <div class="m2o-item m2o-time" title="添加人/时间">添加人/时间</div>
						        </div>
						        {if $list}
							        {foreach $list AS $k => $v}
									<div class="m2o-each m2o-flex m2o-flex-center" _id="{$v['id']}" orderid="{$v['order_id']}">
									    <div class="m2o-item m2o-paixu">
									    	<input type="checkbox"  value="{$v[$primary_key]}" _id="{$v[$primary_key]}"  name="infolist[]" class="m2o-check" />
										</div>
										<div class="m2o-item m2o-flex-one m2o-bt common-list-biaoti">
											<div class="m2o-title-transition max-wd">
									    	 <a class="m2o-title-overflow"  href="./run.php?mid={$relate_module_id}&activity_id={$v['id']}&market_id={$_INPUT['market_id']}" target="formwin">
									            <span class="m2o-common-title">{$v['title']}</span>
									         </a>
									       </div>
										</div>
							            <div class="m2o-item m2o-goodsnum">{$v['product_num']}</div>
									    <div class="m2o-item m2o-state" _status="{$v['status']}" style="color:{$_configs['status_color'][$v['status']]}">{$v['status_format']}</div>
							            <div class="m2o-item m2o-operate">
							            	<a class="m2o-edit">编辑</a>
							            	<a class="m2o-delete">删除</a>
							            </div>
									    <div class="m2o-item m2o-time">
									        <span class="name">{$v['user_name']}</span>
									        <span class="time">{$v['create_time']}</span>
									    </div>
									</div>
									{/foreach}
								{else}
								<div class="m2o-each m2o-flex m2o-flex-center">
									<div class="m2o-flex-one" style="color:#da2d2d;text-align:center;font-size:14px; font-family:Microsoft YaHei;">没有您要找的内容</div>
								</div>
								{/if}
							</div>
							<div class="m2o-bottom-opera">
								<div class="m2o-bottom m2o-flex m2o-flex-center">
						            <div class="m2o-item m2o-paixu">
						        		<input type="checkbox" name="checkall" class="checkAll" rowtag="m2o-item" title="全选"/>
						    		</div>
						    		<div class="m2o-batch batch-delete m2o-flex-one" data-method="delete">删除</div>
						    		<div class="m2o-item m2o-page">
						    			<div id="page_size">{$pagelink}</div>
						    		</div>
						    	</div>
					    	</div>
					     </div>
					     </form>
					</section>
					<aside class="market-info">
						<form method="post" action="run.php?mid={$_INPUT['mid']}&market_id={$market_info['id']}" class="market-form">
							<div class="market-edit">
							</div>
						</form>
					</aside>
				</div>
			</div>
	</div>
</div>

{code}
$store_html = '';
 foreach($market_store AS $_k => $_store){
 	$store_html .= '<span><input type="checkbox" name="activity_store[]" value="' .$_store['id']. '" /><label>' .$_store['name']. '</label></span>';
 }
{/code}

<script type="text/x-jquery-tmpl" id="add-member-tpl">
	<div class="market-item market-mode">
		<label>${opera}活动</label>
		<input type="text" name="title" value="${title}"/>
	</div>
	<div class="market-item market-activity">
		<label>开始：</label>
		<input type="text" name="start_time" value="${start_time}" />
	</div>
	<div class="market-item market-activity">
		<label>结束：</label>
		<input type="text" name="end_time" value="${end_time}" />
	</div>
	<div class="market-item">
		<label>门店：</label>
		<div class="shop">
			{$store_html}
		</div>
	</div>
	<div class="market-save">
		<input type="submit" value="${value}" class="save-pink"/>
		<input type="hidden" name="id" value="${id}" />
		<input type="hidden" name="a"  value="${method}" />
	</div>
</script>
<script>
(function($){
	var data = $.globalListData = {code}echo $list ? json_encode($list) : '{}';{/code};
    $.extend($.geach || ($.geach = {}), {
        data : function(id){
            var info;
            $.each(data, function(i, n){
               if(n['id'] == id){
                   info = {
                       id : n['id']
                   }
                   return false;
               }
            });
            return info;
        }
    });

})(jQuery);
</script>