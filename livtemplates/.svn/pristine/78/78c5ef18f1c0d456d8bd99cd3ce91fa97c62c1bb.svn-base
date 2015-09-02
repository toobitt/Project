{template:head}
{if is_array($formdata)}
	{foreach $formdata as $key => $value}
	{code}
		$$key=$value;
	{/code}
	{/foreach}
{/if}
{css:news_add}
{js:group}
{js:ad}
{js:ckeditor/ckeditor}
<style type="text/css">
.i {color:#7d7d7d;}
.down_list {margin-right:10px;}
.additional {margin:4px auto 16px auto;}
.additional .add_data {width:80px; display:inline-block;}
</style>
<script type="text/javascript">
 var MAP_CENTER_POINT = '{$lat}X{$lng}';
 var map_op_type = 0;
 function  expendMap()
 {
	 document.getElementById("map_relative").style.display="block";
	 document.getElementById("expend_spen").style.display="none";
}
function hideMap()
{
	document.getElementById("map_relative").style.display="none";
	document.getElementById("expend_spen").style.display="block";
}
var this_act=1;

$(function() {
	$('#charge').click(function() {
		$('#moneyText').show();
	});
	$('#free').click(function() {
		$('#moneyText').hide();
	});
	$('input[name="money"]').blur(function() {
		var v = $(this).val();
		$('#charge').val(v);
	});
});
 </script>
{js:bmap}
{if $_configs['map_using_type']}
	<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.2&services=true"></script>
	<script type="text/javascript">
		window.onload=function(){
			initialize();
		}
	</script>
{else}
	<script type="text/javascript" src="http://ditu.google.cn/maps?file=api&amp;v=2&amp;key={$_configs['map_key']}&sensor=false"></script>
	
	<script type="text/javascript">
		window.onload=function(){
			initialize();
		}
	</script>
{/if}
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
	<div class="ad_middle">
	<form class="ad_form h_l" method="post" id="content_form">
		<h2>编辑行动</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">行动标题：</span><input type="text" name="q_action_name" value="{$action_name}" size="60" />
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
				<span class="title">行动类型：</span>
					{code}
						$item_sourc = array(
							'class' => 'down_list',
							'show' => 'status_show',
							'width' => 104,/*列表宽度*/		
							'state' => 0, /*0--正常数据选择列表，1--日期选择*/
						);
					  $default = $type_id ? $type_id : 0;
					  $actionTypes[] = '全部';
					  foreach($action_types[0] as $k => $v)
					  {
							$actionTypes[$v['id']] = $v['name'];
					  }
					{/code}
					{template:form/search_source, q_type_id, $default, $actionTypes, $item_sourc}
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">行动宣言：</span>
					<div style="float:left;">
						<textarea rows="10" cols="50" name="q_slogan">{$slogan}</textarea>
						<script type="text/javascript">
						CKEDITOR.replace('q_slogan', {
							toolbar : 'Basic',
							width : '600',
					        height : '200',
					    });
					    </script>
					</div>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">海报图片：</span>
					<div style="float:left; margin-top:4px;">
						{code}
						$lurl1 = $lurl2 = '';
						$lurl1 .= isset($action_img['host']) ? $action_img['host'] :"";
						$lurl1 .= isset($action_img['dir']) ? $action_img['dir'] :"";
						$lurl2 .= isset($action_img['filepath']) ? $action_img['filepath'] :"";
						$lurl2 .= isset($action_img['filename']) ? $action_img['filename'] :"";
						{/code}
						{if $lurl1 && $lurl2}
						<img src="{$lurl1}100x1{$lurl2}" />
						{else}
						<p>暂没有图片</p>
						{/if}
					</div>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">添加视频：</span>
					<input type="text" name="q_swfurl" value="{$swfurl}" size="80" />
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">行动时间：</span>
					<label>开始时间：</label><input type="text" name="q_start_time" value="{code}echo hg_get_format_date($start_time, 2);{/code}" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" class="Wdate" readonly="readonly" />
					<label style="margin-left:20px;">结束时间：</label><input type="text" name="q_end_time" value="{code}echo hg_get_format_date($end_time, 2);{/code}" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" class="Wdate" readonly="readonly" />
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">行动费用：</span>
					<div>
						<input type="radio" name="q_need_pay" id="charge" {if $need_pay}value="{$need_pay}" checked="checked"{/if} class="n-h" /><label for="charge">收费</label>
						<span id="moneyText"{if !$need_pay} style="display:none;"{/if}><input type="text" name="money" value="{$need_pay}" size="3" /><label>元</label></span>
						<input type="radio" name="q_need_pay" id="free" value="0"{if !$need_pay} checked="checked"{/if} class="n-h" /><label for="free">免费</label>
					</div>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">行动地点：</span>
					{code}
						$item_sourc = array(
							'class' => 'down_list',
							'show' => 'province_show',
							'width' => 104,/*列表宽度*/		
							'state' => 0, /*0--正常数据选择列表，1--日期选择*/
						);
					  $default = $province_id ? $province_id : 0;
					  $province[$default] = '--选择省--';
					  foreach($province_data as $k => $v)
					  {
							$province[$v['action_id']] = $v['action_name'];
					  }
					{/code}
					{template:form/search_source,$province_id,$default,$province,$item_sourc}
					{code}
						$item_sourc = array(
							'class' => 'down_list',
							'show' => 'city_show',
							'width' => 104,/*列表宽度*/		
							'state' => 0, /*0--正常数据选择列表，1--日期选择*/
						);
					  $default = $city_id ? $city_id : 0;
					  $city[$default] = '--选择市--';
					  foreach($city_data as $k => $v)
					  {
							$city[$v['action_id']] = $v['action_name'];
					  }
					{/code}
					{template:form/search_source,$city_id,$default,$city,$item_sourc}
					{code}
						$item_sourc = array(
							'class' => 'down_list',
							'show' => 'town_show',
							'width' => 104,/*列表宽度*/		
							'state' => 0, /*0--正常数据选择列表，1--日期选择*/
						);
					  $default = $town_id ? $town_id : 0;
					  $town[$default] = '--选择区--';
					  foreach($town_data as $k => $v)
					  {
							$town[$v['action_id']] = $v['action_name'];
					  }
					{/code}
					{template:form/search_source,$town_id,$default,$town,$item_sourc}
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">具体地址：</span>
					<input type="text" name="q_place" value="{$place}" size="80" />
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">设置地图：</span>
				    <div id="map_relative">
						<div id="map_canvas" name="map_canvas"  class="formbox" style="width:600px;height:400px;"></div>
					</div>
					<p style="padding:5px; text-indent:6em;">点击地图：将小红气球定位到你需要位置</p>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">行动详情：</span>
				    <div style="float:left;">
						<textarea rows="10" cols="50" name="q_introduce">{$introduce}</textarea>
						<script type="text/javascript">
						CKEDITOR.replace('q_introduce', {
							width : '600',
							height : '200',
						});
						</script>
					</div>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">行动标签：</span>
				    <input type="text" name="q_sign" value="{$sign}" size="80" />
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">附加信息：</span>
				    <div style="float:left;">
				    	<p class="additional"><span>参加权限：</span><input type="radio" name="q_rights" id="everyone" value="0" class="n-h"{if !$rights} checked="checked"{/if} /><label for="everyone">任何人可参加</label>
				    	<input type="radio" name="q_rights" id="audit" value="1" class="n-h"{if $rights} checked="checked"{/if} /><label for="audit">需要我审核</label></p>
				    	<p class="additional"><label class="add_data">更多组织者：</label><input type="text" name="q_connection_user" value="{$connection_user}" size="30" /></p>
				    	<p class="additional"><label class="add_data">关联圈子：</label><input type="text" name="q_connection_group" value="{$connection_group}" size="30" /></p>
				    </div>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">
					<span class="title">发布至：</span>
            		{template:unit/publish, 1, $formdata['column_id']}
		             <script>
		             jQuery(function($){
		                $('#publish-1').css('margin', '10px auto').commonPublish({
		                    column : 2,
		                    maxcolumn : 2,
		                    height : 224,
		                    absolute : false
		                });
		             });
             		 </script>
				</div>
			</li>
		</ul>

		<!-- 隐藏于表单-->
		<input type="hidden" name="q_lat" value="{$lat}" id="g_lat" />
		<input type="hidden" name="q_lng" value="{$lng}" id="g_lng" />
		<input type="hidden" name="action_id" value="{$id}" id="action_id" />
		<input type="hidden" name="a" value="update" />
		<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		<br/>
		<input type="submit" id="submit_ok" name="sub" value="编辑行动" class="button_6_14" />
		<input type="button" class="button_6_14" value="取消编辑" style="margin-left:28px;" onclick="javascript:history.go(-1);" />
	</form>
	</div>


	<div class="right_version">
		<h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
	</div>

</div>
{template:foot}