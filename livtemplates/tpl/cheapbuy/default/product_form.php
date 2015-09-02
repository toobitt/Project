<!-- 商品表单页 -->
{template:head}
{css:2013/button}
{css:2013/form}
{css:product_form}
{css:hg_sort_box}
{js:hg_sort_box}
{js:2013/ajaxload_new}
{js:common/common_form}
{js:common/ajax_upload}
{js:cheapbuy/product_form}
{code}
//hg_pre($formdata);
if($id)
	{
		$optext="更新";
	}
	else
	{
		$optext="新增";
	}
	
{/code}
{if $a}
	{code}
		$action = $a;
	{/code}
{/if}

{if is_array($formdata)}
	{foreach $formdata AS $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
<form class="product_form" action="run.php?mid={$_INPUT['mid']}" method="post" data-id="{$id}" id="vote-form" enctype="multipart/form-data">
<header class="m2o-header">
      <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
        	<h1 class="m2o-l">{$optext}商品</h1>
            <div class="m2o-m m2o-flex-one">
                <input class="m2o-m-title " _value="" name="title" id="title" value="{$title}" placeholder="商品名称" style="font-weight:normal;font-style:normal;color:undefined !important;border-bottom-color:undefined !important;" title="">
            </div>
            <div class="m2o-btn m2o-r">
                <span class="m2o-close option-iframe-back"></span>
				<input type="submit" id="save-btn" value="保存商品" style="display:none;">
				<a class="save-button">保存商品</a>
				<div class="btn-mask"></div>
				<input type="hidden" name="a" value="{$action}" id="action" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
            </div>
        </div>
      </div>
</header>
<div class="m2o-inner">
     <div class="m2o-main m2o-flex">
     	<aside class="m2o-l">
     		<div class="m2o-item">
        		<div class="indexpic">
        			<img src="{$indexpic_url}">
                    <span class="flag"></span>
                    <input name="indexpic_id" type="hidden" value="{$indexpic_id}" id="indexpic_id" />
                 </div>
                 <input type="file" style="display:none;">
        	</div>
        	<div class="m2o-item drop-select clear">
        		<span class="title">机构:</span>
        			{code}
        			
        				$_INPUT['company_id'] = $company_id ? $company_id : 0;
						$company = $company[0];
						$company[0] = '选择机构';

						$attr_company=array(
								'class' => 'colonm down_list data_time',
								'show' => 'company_show',
								'width' =>104,
								'state' =>0,
								'is_sub'=> 0,
						);
					{/code}
        			{template:form/search_source,company_id,$_INPUT['company_id'],$company,$attr_company}
        	</div>
        	<div class="m2o-item drop-select clear">
        		<span class="title">类型:</span>
        			{code}
						if(!isset($_INPUT['buy_type']))
						{
						    $_INPUT['buy_type'] = '0';
						}
						$buy_type_style=array(
								'class' => 'colonm down_list data_time',
								'show' => 'buy_show',
								'width' =>104,
								'state' =>0,
								'is_sub'=> 0,
						);
						$_INPUT['type_id'] = $type_id ? $type_id : 0;
						$buy_types = $buy_type[0];
						$buy_types[0] = '选择类型';
					{/code}
        			{template:form/search_source,type_id,$_INPUT['type_id'],$buy_types,$buy_type_style}
        	</div>
        	<div class="form-dioption-sort m2o-item"  id="sort-box">
                <label style="color:#9f9f9f;">分类： </label><p style="display:inline-block;" class="sort-label" _multi="sort"> {$formdata['sort_name']}<img class="common-head-drop" src="{$RESOURCE_URL}tuji/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
				<div class="sort-box-outer"><div class="sort-box-inner"></div></div>
                <input name="sort_id" type="hidden" value="{$sort_id}" id="sort_id" />
            </div>
     	</aside>
     	<section class="m2o-flex-one m2o-m">
     		<input type="hidden" value="{$formdata['id']}" id="product-id"/>
     		<div class="product-item m2o-flex desc">
     			<div class="title">商品描述：</div>
     			<div class="m2o-flex-one">
	     			<textarea name="brief">{$brief}</textarea>
     			</div>
     		</div>
     		<div class="product-item">
     			<div class="column clear">
     				<!--  
     				<div>
     					<span class="title">库存计数：</span>
     					<input name="count_type" value="{$count_type}"/>
     				</div>
     				-->
     				<div _name="数量">
     					<span class="title">总数：</span>
     					<input name="amount" value="{$amount}" class="must-count"/>
     				</div>
     				<div>
     					<span class="title">成团人数：</span>
     					<input name="group_num" value="{$group_num}"/>
     				</div>
     				<div>
     					<span class="title">最高人数：</span>
     					<input name="max_num" value="{$max_num}"/>
     				</div>
     				<div>
     					<span class="title">ID限购数：</span>
     					<input name="id_limit" value="{$id_limit}"/>
     				</div>
     				<div>
     					<span class="title">定金：</span>
     					<input name="front_money" value="{$front_money}"/>
     				</div>
     				<div>
     					<span class="title">原价：</span>
     					<input name="list_price" value="{$list_price}"/>
     				</div>
     				<div _name="优惠价">
     					<span class="title">优惠价：</span>
     					<input name="youhui_price" value="{$youhui_price}" class="must-price"/>
     				</div>
     				<div>
     					<span class="title">运费：</span>
     					<input name="fare" value="{$fare}"/>
     				</div>
     				<div>
     					<span class="title">商品链接：</span>
     					<input name="prod_url" value="{$prod_url}"/>
     				</div>
     			</div>
     			<div>
     				<span class="title">时间：</span>
     				<input name="start_time" class="date-picker" _time=true value="{$start_time}" placeholder="开始时间"/> - 
		            <input name="end_time" class="date-picker" _time=true value="{$end_time}" placeholder="结束时间"/>
     			</div>
     		</div>
     		<div class="product-item m2o-flex pic-box">
     			<div class="title">图片：</div>
     			<div class="m2o-flex-one">
     				<ul class="pic-list media-list">
     					<!-- 图片列表 -->
     				</ul>
     				<a class="add-btn add-pic">添加图片</a>
     				<input name="Filedata" type="file" style="display:none;">
     			</div>
     		</div>
     		<div class="product-item m2o-flex vod-box">
     			<div class="title">视频：</div>
     			<div class="m2o-flex-one">
     				<ul class="vod-list media-list">
     					<!-- 视频列表 -->
     				</ul>
     				<a class="add-btn add-vod">添加视频</a>
     				<input name="videofile" type="file" style="display:none;">
     			</div>
     		</div>
     		
     		<div class="product-item m2o-flex channel-wrap">
     			<div class="title">频道：</div>
     			<div class="m2o-flex">
     			<!-- 
     				<div class="common-switch {if 开启xxxx}common-switch-on{/if}">
		               <div class="switch-item switch-left" data-number="0"></div>
		               <div class="switch-slide"></div>
		               <div class="switch-item switch-right" data-number="100"></div>
		            </div>
		         -->
		            <div>
		        			{code}
								if(!isset($_INPUT['channel_id']))
								{
								    $_INPUT['channel_id'] = '0';
								}
								$live_style=array(
										'class' => 'colonm down_list data_time',
										'show' => 'live_show',
										'width' =>104,
										'state' =>0,
										'is_sub'=> 0,
								);
								$_INPUT['channel_id'] = $channel_id ? $channel_id : 0;
								$channels = $channels[0];
								$channels[0] = '选择频道';
							{/code}
		        			{template:form/search_source,channel_id,$_INPUT['channel_id'],$channels,$live_style}
		        	</div>
		            <div style="margin-left:10px;">
		            	<div class="live-time" style="display:inline-block;margin-right:5px;{if !$_INPUT['channel_id']}display:none;{/if}">
			            	<input class="date-picker" name="live_start_time" _time=true value="{$live_start_time}"/> - 
			            	<input class="date-picker" name="live_end_time" _time=true value="{$live_end_time}"/>
		            	</div>
		            	
		            	<div class="live-time" style="display:inline-block;margin-right:5px;{if !$_INPUT['channel_id']}display:none;{/if}">
			            	<input type="checkbox" {if $program_record_id} checked="checked"{/if} name="need_program_record"/>
			            	<span>收录视频</span> 
		            	</div>
		            </div>
		            <input type="hidden" name="直播流" value=""/>
     			</div>
     		</div>
     		
     		<div class="product-item m2o-flex prefer">
     			<div class="title">优惠政策：</div>
     			<div class="m2o-flex">
     				<textarea name="cheap_policy">{$cheap_policy}</textarea>
     			</div>
     		</div>
     		<div class="product-item m2o-flex">
     			<div class="title">订单选项：</div>
     			<div class="more-info m2o-flex-one">
	     			<div>
	     				<input type="checkbox" value="1" name="need_address" {if $need_address}checked="checked"{/if}>
	     				<span>订单地址</span>
	     			</div>
	     			<div>
	     				<input type="checkbox" value="1" name="need_email" {if $need_email}checked="checked"{/if}>
	     				<span>邮箱地址</span>
	     			</div>
     			</div>
     		</div>
     		<div class="product-item m2o-flex prefer">
     				<div>
     					<span class="title">销量基数：</span>
     					<input name="sale_base" value="{$sale_base}">
     				</div>
     		</div>
     		<div class="player-box"></div>
     	</section>
     </div>
     <span id="loading"></span>
</div>
</form>
<script type="text/x-jquery-tmpl" id="pic-item-tpl">
<li class="pic-item" _id="{{= id}}">
	<img src="{{= src}}">
	<a class="del"></a>
	<input type="hidden" name="img_id[]" value="{{= id}}">
</li>
</script>
<script type="text/x-jquery-tmpl" id="vod-item-tpl">
<li class="vod-item" _id="{{= id}}" _mid="${material_id}">
	<img src="{{= src}}">
	<span class="play"></span>
	<a class="del"></a>
	<input type="hidden" name="video_id[]" value="{{= material_id}}">
</li>
</script>
<script type="text/x-jquery-tmpl" id="vedio-tpl">
<div class="flash-box" style="width:240px;height:240px;">
	<object id="vodPlayer" type="application/x-shockwave-flash" data="{$RESOURCE_URL}swf/vodPlayer.swf?11122713" width="240" height="240">  
		<param name="movie" value="{$RESOURCE_URL}swf/vodPlayer.swf?11122713">  
		<param name="allowscriptaccess" value="always">  
		<param name="allowFullScreen" value="true">  
		<param name="wmode" value="transparent"> 
		<param name="flashvars" value="videoUrl=${video_url}&amp;autoPlay=true&amp;aspect=16:9"">
	</object>
</div>
<span class="vedio-back-close"></span>
</script>
<script>
$(function(){
	var picJson = {code} echo $formdata['pic_info'] ? json_encode($formdata['pic_info']) : '{}'  {/code},
		vodJson = {code} echo $formdata['video_info'] ? json_encode($formdata['video_info']) : '{}'  {/code};
			console.log(picJson);
	var picData = [],
		vodData = [];
	$.each( picJson, function(k, v){
		var data = {
				id : v.id,
				src : $.createImgSrc(v,{width:130,height:90})
			};
		picData.push(data);
	});
	$.each( vodJson, function(k, v){
		var data = {
				id : k,
				material_id : v['material_id'],
				src : v['source_img']
			};
		vodData.push(data);
	});
	if( picData ){
		$('#pic-item-tpl').tmpl( picData ).appendTo('.pic-list');
	}if( vodData ){
		$('#vod-item-tpl').tmpl( vodData ).appendTo('.vod-list');
	}
});
</script>
<script>
$(function(){
	$('#live_show').find('li').click(function(){
		var id = $(this).find('a').attr('attrid');
		id!=0 ? $('.live-time').css('display','inline-block') : $('.live-time').css('display','none')
	});
});
</script>
{template:foot}