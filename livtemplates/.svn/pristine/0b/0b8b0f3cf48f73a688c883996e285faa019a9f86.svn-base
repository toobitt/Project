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
{code}
if ($lat == 0.0 && $lng == 0.0)
{
	$point = "'".$_configs['map_center_point']."'";
}
else
{
	$point = "'".$lat."X".$lng."'";
}
{/code}
<style type="text/css">
.per {float:left; margin-right:20px; color:#7d7d7d;}
.per p {margin-left:20px; margin-top:10px;}
</style>
<script type="text/javascript">
var MAP_CENTER_POINT = {$point};
var map_op_type = 0;
function expendMap()
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
			<h2>编辑讨论区</h2>
			<ul class="form_ul">
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">圈子名称：</span><input type="text" name="gname" value="{$name}" />
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">圈子域名：</span><input type="text" name="domain" value="{$group_domain}" />
					</div>
				</li>
				<!--
				<li class="i">
					<div class="form_ul_div clear">
					<span class="title">上级地盘：</span>
						{code}
							$item_sourc = array(
								'class' => 'down_list',
								'show' => 'status_show',
								'width' => 104,/*列表宽度*/		
								'state' => 0, /*0--正常数据选择列表，1--日期选择*/
							);
						  $default=$fatherid ? $fatherid : 0;
						  $father[$default]='根级底盘';
						  foreach($father_group as $k => $v)
						  {
								$father[$v['group_id']] = $v['name'];
						  }
						{/code}
						{template:form/search_source,father_id,$default,$father,$item_sourc}
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">选择类型：</span>
						{code}
									$attr_group = array(
										'class' => 'down_list ',
										'show' => 'group_show',
										'width' => 104,/*列表宽度*/
										'state' => 0,/*0--正常数据选择列表，1--日期选择*/
									);
								  $default=$group_type ? $group_type : 0;
								  $type[$default]='全部类型';
								  foreach($type_group as $k => $v)
								  {
										$type[$v['typeid']] = $v['type_name'];
								  }
						{/code}
						{template:form/search_source,group_type,$default,$type,$attr_group}
					</div>
				</li>
				-->
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">圈子宣言：</span><textarea name="description">{$description}</textarea>
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">圈子头像：</span><img src="{if is_array($logo)}{$logo['host']}{$logo['dir']}100x100/{$logo['filepath']}{$logo['filename']}{else}{$_configs['app_url']}{$logo}{/if}" />
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">圈子顶图：</span><img src="{if is_array($background)}{$background['host']}{$background['dir']}333x222/{$background['filepath']}{$background['filename']}{else}{$_configs['app_url']}{$background}{/if}" />
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear">
					{code}
					$arr = array();
					foreach($tag as $v)
					{
						$arr[] = $v['mark_name'];
					}
					{/code}
						<span class="title">圈子标签：</span><input type="text" name="tags" value="{code}echo implode(',', $arr);{/code}" size="50" />
					</div>
				</li>
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">参与权限：</span>
						<div>
							<div class="per"><input type="radio" class="n-h" name="permission" value="6"{if $permission == 6} checked="checked"{/if} /><label>公共圈子</label><p>(所有人都可以访问)</p></div>
							<div class="per"><input type="radio" class="n-h" name="permission" value="0"{if $permission == 0} checked="checked"{/if} /><label>私密圈子</label><p>(只有圈子成员才可以访问)</p></div>
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
				<!--
				<li class="i">
					<div class="form_ul_div clear">
				        <div style="margin-bottom:5px;">拖动地标可以更改地盘的经纬度，此操作可能会导致讨论区的位置与实际不符，请慎重操作</div>
						<span id="expend_spen" style="display:none;"><a href="javascript:void(0);" onclick="expendMap();" id="mark_map">展开地图</a></span>
					    <div id="map_relative">
							<div id="map_canvas" name="map_canvas" class="formbox" style="width:600px; height:400px;"></div>
							<div style="margin-top:5px;"><a href="javascript:void(0);" onclick="hideMap();">收起地图</a></div>
						</div>
					</div>
				</li>
				-->
			</ul>
	
			<!-- 隐藏于表单-->
			<!--
			<input type="hidden" name="hid_lat" value="{$lat}" id="g_lat" />
			<input type="hidden" name="hid_lng" value="{$lng}" id="g_lng" />
			<input type="hidden" name="hid_addr" value="{$group_addr}" id="g_addr" />
			-->
			<input type="hidden" name="hid_gid" value="{$group_id}" id="g_gid" />
			<input type="hidden" name="a" value="update" />
			<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
			<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
			<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
			<br/>
			<input type="submit" id="submit_ok" name="sub" value="编辑圈子" class="button_6_14" />
			<input type="button" class="button_6_14" value="取消编辑" style="margin-left:28px;" onclick="javascript:history.go(-1);" />
		</form>
	</div>


	<div class="right_version">
		<h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
	</div>

</div>
{template:foot}