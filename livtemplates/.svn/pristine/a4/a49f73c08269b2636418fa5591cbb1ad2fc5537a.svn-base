{template:head}
{css:2013/button}
{css:2013/form}
{css:order_list}
{js:2013/ajaxload_new}
{js:2013/list}
{js:page/page}
{js:cheapbuy/order_list}
{code}

$product_info 	= $formdata['product_info'];
$order_info		= $formdata['info'];
$page_info		= $formdata['page_info'];
//print_r($order_info);
{/code}
<script type="text/javascript">
function createExecl(obj)
{
	window.location.href = $(obj).attr('_href');
}

</script>
<header class="m2o-header">
      <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
        	<h1 class="m2o-l">订单管理</h1>
            <div class="m2o-m m2o-flex-one">
                <input class="m2o-m-title" disabled="disabled" _value="" name="title" id="title" value="{$product_info['title']}" style="font-weight:normal;font-style:normal;color:undefined !important;border-bottom-color:undefined !important;" title="">
                <input type="hidden" value="{$product_info['id']}" name="product_id"> 
            </div>
            <div class="m2o-btn m2o-r">
                <span class="m2o-close option-iframe-back"></span>
            	<a onclick="createExecl(this);" href="javascript:###" _href="{$_configs['App_cheapbuy']['protocol']}{$_configs['App_cheapbuy']['host']}/{$_configs['App_cheapbuy']['dir']}admin/product.php?a=create_execl&id={$product_info['id']}&access_token={$_user['token']}"class="excel view-button">生成Excel</a>
            </div>
        </div>
      </div>
</header>
<div class="m2o-inner">
     <div class="m2o-main m2o-flex">
     	<aside class="m2o-l">
     		<div class="m2o-item">
        		<div class="indexpic">
        			<img src="{$product_info['indexpic_url']}">
                    <span class="flag"></span>
                 </div>
        	</div>
        	<div class="m2o-item">
        		<span class="title">机构:</span>
        		<span class="arrow">{$product_info['company_name']}</span>
        	</div>
        	<div class="m2o-item">
        		<span class="title">分类:</span>
        		<span class="arrow">{$product_info['sort_name']}</span>
        	</div>
        	<div class="m2o-item">
        		<span class="title">类型:</span>
        		<span class="arrow">{$product_info['type_name']}</span>
        	</div>
     	</aside>
		<section class="m2o-flex-one m2o-m" style="background: #fff;">
			<div class="choice-area m2o-flex-one clear">
				<form class="order-search">
        		{code}
                    if(!isset($_INPUT['status']))
					{
					    $_INPUT['status'] = -1;
					}
                    if(!isset($_INPUT['date_search']))
					{
					    $_INPUT['date_search'] = 1;
					}
					$attr_status=array(
						'class' => 'colonm down_list data_time',
						'show' => 'status_show',
						'width' =>104,
						'state' =>0,
						'is_sub'=> 0,
					);
					$attr_date = array(
						'class' => 'colonm down_list data_time',
						'show' => 'colonm_show',
						'width' => 104,/*列表宽度*/
						'state' => 1,/*0--正常数据选择列表，1--日期选择*/
					);
                {/code}
                {template:form/search_source,status,$_INPUT['status'],$_configs['status'],$attr_status}
                {template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
                <input type="hidden" name="a" value="order_search"/>
                <input type="submit" class="serach-btn" value=""/>
                </form>
        	</div>
     		<div class="order-wrap">
     		{foreach $order_info as $k => $v}
	     			<div class="order-item m2o-each" data-id="{$v['id']}">
		     			<div class="head">
		     				<span class="select">
			     				<input type="checkbox" class="m2o-check"/>
		     				</span>
		     				<span class="i">订单号：{$v['id']}</span>
		     				<span class="i">成交时间：{$v['create_time']}</span>
		     				<span class="i">成交人：{$v['user_name']}</span>
		     				<a class="del m2o-delete" data-method="delete">删除</a>
		     			</div>
		     			<div class="info m2o-flex m2o-flex-center">
		     				<div class="m2o-flex-one first-div">
		     					<div class="m2o-flex">
		     						<div class="title">客户姓名：</div>
		     						<div class="m2o-flex-one">
		     							{$v['custom_name']}
		     						</div>
		     					</div>
		     					<div class="m2o-flex">
		     						<div class="title">手机号码：</div>
		     						<div class="m2o-flex-one">
		     							{$v['phone']}
		     						</div>
		     					</div>
		     					<div class="m2o-flex">
		     						<div class="title">收货信息：</div>
		     						<div class="m2o-flex-one">
		     							{$v['address']}
		     						</div>
		     					</div>
		     					{if $v['email']}
		     					<div class="m2o-flex">
		     						<div class="title">邮箱：</div>
		     						<div class="m2o-flex-one">
		     							{$v['email']}
		     						</div>
		     					</div>
		     					{/if}
		     					{if $v['remark']}
		     					<div class="m2o-flex">
		     						<div class="title">备注信息：</div>
		     						<div class="m2o-flex-one">
		     							{$v['remark']}
		     						</div>
		     					</div>
		     					{/if}
		     				</div>
		     				<div class="sdiv">
		     					<span class="title">数量：</span>{$v['product_num']}
		     				</div>
		     				<div class="sdiv">
		     					<span class="title m2o-audit" _status="{$v['status']}" style="color:{$_configs['status_color'][$v['status']]}">{$v['audit']}</span>
		     				</div>
		     				<div class="sdiv">
		     					<p>{$v['order_sum']}</p>
		     					<span class="title">(含 快递 :{$product_info['fare']})</span>
		     				</div>
		     			</div>
	     			</div>
     		{/foreach}
     		</div>
     		<div class="m2o-flex order-bottom">
     			<div class="handler-box m2o-flex-one">
					<input type="checkbox" name="checkall" class="checkAll" title="全选">
					<a name="delete" data-method="delete" class="batch-handle">删除</a>
				</div>
				<div class="page-link"></div>
			</div>
     	</section>
     </div>
</div>
{template:foot}
<script type="text/x-jquery-tmpl" id="list-item-tpl">
<div class="order-item m2o-each" data-id="{{= id}}">
	<div class="head">
		<span class="select">
			<input type="checkbox" class="m2o-check" />
		</span>
		<span class="i">订单号：{{= id}}</span>
		<span class="i">成交时间：{{= create_time}}</span>
		<span class="i">成交人：{{= user_name}}</span>
		<a class="del m2o-delete" data-method="delete">删除</a>
	</div>
	<div class="info m2o-flex m2o-flex-center">
		<div class="m2o-flex-one first-div">
			<div class="m2o-flex">
				<div class="title">收货信息：</div>
				<div class="m2o-flex-one">{{= address}}</div>
			</div>
			<div class="m2o-flex">
				<div class="title">备注信息：</div>
				<div class="m2o-flex-one">{{= remark}}</div>
			</div>
		</div>
		<div class="sdiv">
			<span class="title">数量：</span>{{= product_num}}
		</div>
		<div class="sdiv">
			<span class="title m2o-audit" style="color:{{if status == 0}}#8ea8c8{{/if}}{{if status == 1}}#17b202{{/if}}{{if status == 2}}rgb(248, 166, 166){{/if}}">{{= audit}}</span>
		</div>
		<div class="sdiv">
			<p>{{= order_sum}}</p>
			<span class="title">(含 快递 :{{= fare}})</span>
		</div>
	</div>
</div>
</script>
<script>
$(function(){
	var pageDate = $.pageDate = {code}echo $page_info ? json_encode($page_info) : '{}'{/code};
	pageDate['page'] = function( event, page, count ){
		$.globalAjax($('.page-link'), function(){
			var product_id = $('input[name="product_id"]').val();
			var url = './run.php?mid=' + gMid + getSearchInfo() +'&a=get_more_order&page='+ page +'&product_id=' + product_id;
			return $.getJSON( url,function(json){
					var data = json['0'];
					$.MC.list.orderlist('refresh',data);
	            }
	        );
        });
	};
	function getSearchInfo(){
		var form = $('.order-search');
		return '&'+ form.serialize();
	};
	$('.page-link').page(pageDate);
});
</script>