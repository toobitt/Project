{template:head}
{css:2013/button}
{css:2013/form}
{css:2013/list}
{css:market-info}
{js:jqueryfn/jquery.tmpl.min}
{js:common/common_form}
{js:supermarket/market_message}
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
//print_r($list);
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
				<div class="m2o-main m2o-flex" tid="t_02">
					<section class="market-box m2o-flex-one">
					 <form action="./run.php?mid={$_INPUT['mid']}&market_id={$market_info['id']}" method="post" enctype="multipart/form-data" class="market-list">
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
					                {template:form/search_source,status,$state_default,$_configs['message_status'],$state_item_source}
					                <div class="key-search">
					                	<input type="text" name="k" class="search-k" value="{$_INPUT['k']}" speech="speech" x-webkit-speech="x-webkit-speech" x-webkit-grammar="builtin:translate" placeholder="内容标题搜索">
					                </div>
					                <input type="submit" class="serach-btn" value=""/>
					        	</div>
					        	<div class="member-menu">
					        		<a class="mem-pink">新增消息</a>
					        	</div>
					        </div>
							<div class="m2o-each-list">
								<div class="m2o-each m2o-flex m2o-flex-center">
						        	<div class="m2o-item m2o-paixu" title="排序">
						        		<a title="排序模式切换/ALT+R" onclick="hg_switch_order('newslist');" class="common-list-paixu"></a>
						        	</div>
						        	<div class="m2o-item m2o-flex-one m2o-bt common-list-biaoti" title="消息">消息</div>
						            <div class="m2o-item m2o-scope" title="用户范围">用户范围</div>
						            <div class="m2o-item m2o-state" title="状态">状态</div>
						            <div class="m2o-item m2o-operate" title="操作">操作</div>
						            <div class="m2o-item m2o-time" title="添加人/时间">添加人/时间</div>
						        </div>
						        {if $list}
						        {foreach $list AS $k => $v}
									<div class="m2o-each m2o-flex m2o-flex-center" _id="{$v['id']}" data-id="r_{$v['order_id']}">
									    <div class="m2o-item m2o-paixu">
									    	<input type="checkbox"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  name="infolist[]" class="m2o-check" />
										</div>
										<div class="m2o-item m2o-flex-one m2o-bt common-list-biaoti">
											<div class="m2o-title-transition max-wd">
										    	 <a class="m2o-title-overflow"  href="#">
										            <span class="m2o-common-title">{$v['title']}</span>
										         </a>
									       </div>
										</div>
							            <div class="m2o-item m2o-scope">{$v['scope_format']}</div>
									    <div class="m2o-item m2o-state" _status="{$v['status']}" style="color:{$_configs['status_color'][$v['status']]}">{$v['status_format']}</div>
							            <div class="m2o-item m2o-operate">
							            	<a class="m2o-edit"></a>
							            	<a class="member-write"></a>
							            	<a class="m2o-delete"></a>
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
						    		<div class="m2o-item m2o-flex-one list-config">
						    		   <a class="batch-delete">删除</a>
						    		   <a class="batch-back">推消息</a>
						    		</div>
						    		<div class="m2o-item m2o-page">
						    			<div id="page_size">{$pagelink}</div>
						    		</div>
						    	</div>
					    	</div>
					     </div>
					</form>
					</section>
					<aside class="market-info">
						<form method="post" action="" name="vod_sort_listform" class="market-form">
							<div class="market-edit" data-id={$_INPUT['market_id']}>
							</div>
						</form>
					</aside>
				</div>
			</div>
	</div>
</div>

{code}
$constellation_html = '';
$device_html = '';
  foreach($_configs['constellation'] AS $constellation_id => $constellation_name){
 	$constellation_html .= '<span><input type="checkbox" name="constellation[]" value="' .$constellation_id. '" /><label>' .$constellation_name. '</label></span>';
 }
  foreach($_configs['device'] AS $device_id => $device_name){
 	$device_html .= '<span><input type="checkbox" name="device[]" value="' .$device_id. '" /><label>' .$device_name. '</label></span>';
 }
$scope_item_source = array(
    'class' 	=> 'down_list',
    'show' 		=> 'scope_show',
    'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
    'is_sub'	=>	1,
    'width'     =>  140,
);

if($scope)
{
	$scope_default = $scope;
}
else
{
	$scope_default = 2;
}
{/code}

<div class="scope-data">
	{template:form/search_source,scope,$scope_default,$_configs['message_scope'],$scope_item_source}
</div>


<script type="text/x-jquery-tmpl" id="add-message-tpl">
	<div class="market-item market-tip">
		<label>标题：</label>
		<input type="text" name='title' value="${title}"/>
	</div>
	<div class="market-item">
		<label>内容：</label>
		<textarea name='content' cols="120" rows="3" placeholder="活动内容">${cont}</textarea>
	</div>
	<div class="market-item">
		<label>过期日期：</label>
		<span class="expire-time"><input name="expire_time" type="text" value="${expire_time}"/></span>
	</div>
	<div class="market-item market-scope">
		<label>范围：</label>
        
	</div>
	<div class="market-item market-addmember">
		<label></label>
        <div class="member-box">
        	<span class="member-each"><input name="member[]" placeholder="请输入会员名" type="text" value=""/><em class="add"></em></span>
		</div>
	</div>
	<div class="scope_toggle">
		<div class="market-item market-check">
			<label>设备：</label>
			<div class="shop device">
				{$device_html}
			</div>
		</div>
		<div class="market-item">
			<label>年龄：</label>
			<span class="birth"><input type="text" name='age_start' value="${age_start}" />-<input type="text" name="age_end" value="${age_end}" /></span>
		</div>
		<div class="market-item">
			<label>生日：</label>
			<span class="birth date-setting"><input type="text" name='birthday_start' value="${birthday_start}" />-<input type="text" name="birthday_end" value="${birthday_end}" /></span>
		</div>
		<div class="market-item market-check">
			<label>星座：</label>
			<div class="shop constell">
			{$constellation_html}
			</div>
		</div>
	</div>
	<div class="market-save">
		<input type="submit" value="${value}" class="save-pink"/>
		<input type="hidden" name="market_id" value="${market_id}" />
		<input type="hidden" name="id" value="${id}" />
		<input type="hidden" name="a" value="${method}"/>
	</div>
</script>
<script type="text/x-jquery-tmpl" id="add-member-tpl">
	<span class="member-each member-mode"><input name="member[]" placeholder="请输入会员名" type="text" value="${mem}"/><em class="add"></em></span>
</script>
<script type="text/javascript">
	$(function($){
	$.globalscope = {code} echo  $_configs['message_scope'] ?  json_encode($_configs['message_scope']) : '{}'; {/code};
});
</script>