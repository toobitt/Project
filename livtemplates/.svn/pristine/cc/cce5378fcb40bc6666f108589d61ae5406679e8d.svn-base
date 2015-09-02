{template:head}
{code}
    $opname = "数据";
    if($id)
    {
        $optext="查看";
        $ac="update";
    }
    else
    {
        $optext="新增";
        $ac="create";
    }
{/code}
{if is_array($formdata)}
    {foreach $formdata as $key => $value}
        {code}
            $$key = $value; 
        {/code}
    {/foreach}
{/if}
{css:ad_style}
{css:2013/list}
{js:ad}
{css:column_node}
{js:column_node}
<style>
.w80{width:80px!important;}
.w100{width:100px!important;}
.w150{width:150px!important;}
.w200{width:200px!important;}
.overhidden{white-space: nowrap;overflow: hidden;text-overflow: ellipsis;}
.m2o-each-list{margin:0px;}
.m2o-each{padding:0px 20px;}
.good-list{border: 1px solid #c8d4e0;}
.info-item-list{padding:15px 0px;}
.info-item-list-title{display: block;font-size: 18px;padding-bottom: 5px;}
</style>
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post"   id="content_form">
<h2>{$optext}{$opname}</h2>
<div id="test">

</div>
<ul class="form_ul">
	{code}
	    $item_source = array(
	        'class' => 'down_list',
	        'show' => 'item_show',
	        'width' => 100,/*列表宽度*/     
	        'state' => 0, /*0--正常数据选择列表，1--日期选择*/
	        'is_sub'=>1,
	    );
	    $default = $group_id ? $group_id : -1;
	    $group_data[$default] = '选择分类';
	    foreach($group as $k =>$v)
	    {
	        $group_data[$v['id']] = $v['title'];
	    }
	{/code}

	<li class="i" id="datainput">
	    <div class="form_ul_div clear">
	        <span class="title w100" >下单人:</span>
	        {$user_name}
	        <!--<input type="text" disabled="disabled" value="{$user_name}"/>-->
	    </div>
	</li>
	<li class="i" id="datainput">
	    <div class="form_ul_div clear">
	        <span class="title w80" >下单时间:</span>
	        {$create_time}
	        
	        <!--<input type="text" disabled="disabled" value="{$create_time}"/>-->
	    </div>
	</li>
	<li class="i" id="datainput">
	    <div class="form_ul_div clear">
	        <span class="title w80" >订单名称:</span>
	        {$title}
	        <!--<input type="text" disabled="disabled" value="{$title}"/>-->
	    </div>
	</li>
	<li class="i" id="datainput">
	    <div class="form_ul_div clear">
	        <span class="title w80" >订单应付金额:</span>
	        {$order_value}
	        <!--<input type="text" disabled="disabled" value="{$order_value}"/>-->
	    </div>
	</li>
	<li class="i" id="datainput">
	    <div class="form_ul_div clear">
	        <span class="title w80" >商品总数:</span>
	        {$order_quantity}
	        <!--<input type="text" disabled="disabled" value="{$order_quantity}"/>-->
	    </div>
	</li>
	
	<li class="i" id="datainput">
	    <div class="form_ul_div clear">
	        <span class="title w80" >发票抬头:</span>
	        {$bill_header_content}
	        <!--<input type="text" disabled="disabled" value="{$pay_status_title}"/>-->
	    </div>
	</li>
	
	<li class="i" id="datainput">
	    <div class="form_ul_div clear">
	        <span class="title w80" >订单支付状态:</span>
	        {$pay_status_title}
	        <!--<input type="text" disabled="disabled" value="{$pay_status_title}"/>-->
	    </div>
	</li>
	<li class="i" id="datainput">
	    <div class="form_ul_div clear">
	        <span class="title w80" >订单状态:</span>
	        {$order_status_title}
	        <!--<input type="text" disabled="disabled" value="{$order_status_title}"/>-->
	    </div>
	</li>
	<li class="i" id="datainput">
	    <div class="form_ul_div clear">
	        <span class="title w80" >订单编号:</span>
	        {$order_id}
	        <!--<input type="text" disabled="disabled" value="{$order_id}" size="40"/>-->
	    </div>
	</li>
	<li class="i" id="datainput">
	    <div class="form_ul_div clear">
	        <span class="title w80" >订单描述:</span>
	        {$brief}
	        <!--{template:form/textarea,brief,$brief}-->
	    </div>
	</li>
</ul>

<div class="info-item-list">
	<span class="info-item-list-title">商品列表</span>
	<div class="good-list">
		<div class="m2o-title m2o-flex m2o-flex-center">
		     <div class="m2o-item m2o-flex-one m2o-bt" title="商品ID">商品ID</div>
		     <div class="m2o-item w80 overhidden" title="应用标示">应用标示</div>
		     <div class="m2o-item w100 overhidden" title="商品名称">商品名称</div>
		     <div class="m2o-item w80" title="商品价格">商品价格</div>
		     <div class="m2o-item w80" title="优惠">优惠</div>
		     <div class="m2o-item w80" title="购买数量">购买数量</div>
		     <div class="m2o-item w200 overhidden" title="商品描述">商品描述</div>
		</div>
		<div class="m2o-each-list">
		<?php foreach($goodslist as $goods):?>
			<div class="m2o-each m2o-flex m2o-flex-center m2o-list-data">
				<div class="m2o-item m2o-flex-one m2o-bt" title="{$goods['goods_id']}">{$goods['goods_id']}</div>
				<div class="m2o-item w80 overhidden" title="{$goods['bundle_id']}">{$goods['bundle_id']}</div>
				<div class="m2o-item w100 overhidden" title="{$goods['goods_title']}">{$goods['goods_title']}</div>
				<div class="m2o-item w80" title="{$goods['goods_value']}">{$goods['goods_value']}</div>
				<div class="m2o-item w80" title="{$goods['goods_discount']}">{$goods['goods_discount']}</div>
				<div class="m2o-item w80" title="{$goods['goods_number']}">{$goods['goods_number']}</div>
				<div class="m2o-item w200 overhidden" title="{$goods['goods_brief']}">{$goods['goods_brief']}</div>
			</div>
		<?php endforeach;?>
		</div>
	</div>
</div>

<div class="info-item-list">
	<span class="info-item-list-title">收货人/联系人信息</span>
	<div class="good-list">
		<div class="m2o-title m2o-flex m2o-flex-center">
		     <div class="m2o-item m2o-flex-one m2o-bt" title="姓名">姓名</div>
		     <div class="m2o-item w100 overhidden" title="电话">电话</div>
		     <div class="m2o-item w100 overhidden" title="手机">手机</div>
		     <div class="m2o-item w150" title="电子邮箱">电子邮箱</div>
		     <div class="m2o-item w200" title="地址">地址</div>
		</div>
		<div class="m2o-each-list">
		<?php if($nameofcontact||$contact_telphone){?>
			<div class="m2o-each m2o-flex m2o-flex-center m2o-list-data">
				<div class="m2o-item m2o-flex-one m2o-bt" title="{$nameofcontact}">{$nameofcontact}</div>
				<div class="m2o-item w100overhidden" title="{$contact_phone}">{$contact_phone}</div>
				<div class="m2o-item w100 overhidden" title="{$contact_telphone}">{$contact_telphone}</div>
				<div class="m2o-item w150 overhidden" title="{$contact_email}">{$contact_email}</div>
				<div class="m2o-item w200 overhidden" title="{$contact_address}">{$contact_address}</div>
			</div>
		<?php }else{?>
			<div class="m2o-each m2o-flex m2o-flex-center m2o-list-data">
				<div class="m2o-item m2o-flex-one m2o-bt" title="{$nameofconsignee}">{$nameofconsignee}</div>
				<div class="m2o-item w100 overhidden" title="{$consignee_phone}">{$consignee_phone}</div>
				<div class="m2o-item w100 overhidden" title="{$consignee_telphone}">{$consignee_telphone}</div>
				<div class="m2o-item w150 overhidden" title="{$consignee_email}">{$consignee_email}</div>
				<div class="m2o-item w200 overhidden" title="{$consignee_address}">{$consignee_address}</div>
			</div>
		<?php };?> 
		</div>
	</div>
</div>
</div>
<div class="right_version">
    <h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
{template:foot}