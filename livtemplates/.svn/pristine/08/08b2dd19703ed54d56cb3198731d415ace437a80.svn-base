{template:./head}
{js:qingao/base}
{js:qingao/group_groupcreate}
{js:qingao/group_indexmap_b}
{js:qingao/jquery-ui-1.8.23.custom.min}
{js:qingao/jquery.upload}
{js:qingao/action_modify}
{js:My97DatePicker/WdatePicker}
{css:manage}
<script type="text/javascript">
//<![CDATA[
var MAP_CENTER_POINT = '<?php  echo (($action_info['lat'] == "0.00000000000000") ? '32.039665' : $action_info['lat']);?>X<?php  echo (($action_info['lng'] == "0.00000000000000") ? '118.808604' : $action_info['lng']);?>';

window.onload = initialize;

$(document).ready(function(){
	$(".add_attach").tabs({"tabPanel": ".add_thread_file"});
	$( "#datepicker, #datepicker1" ).datepicker();
});

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
//]]>
</script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.2&services=true"></script>
	</section><!--展示区完-->
<div class="gong_main">
	<div class="g_main_box">
		<div class="g_main_box_l">
			<ul>
				<li><a style="">设置</a></li>
				<li><a style="background:#fd8c02; color:#fff;" href="activity.php?action_id={$_INPUT['action_id']}&a=getMemberByAid">成员</a></li>
				<li><a style="background:#fd8c02; color:#fff;" href="activity.php?action_id={$_INPUT['action_id']}&a=setParams">参数设置</a></li>
			</ul>
		</div>
		<div class="g_main_box_r">
			<a href="activity.php?action_id={$_INPUT['action_id']}">返回活动</a>
		</div>
	</div>
	<div class="g_main_con">
		<div class="g_main_con_set">
			<div class="g_main_left_line">
				<div class="g_main_right_line">
					<form action="activitys.php?a=doupdate" method="post"
						enctype="multipart/form-data" id="actionForm">
						<p>
							<label class="actionLabel">行动类型：</label>
							<div class="actionItem">
								<select
									name="thread_type"> {foreach $action_type as $v}
									<option value="{$v['id']}"
										{code}echo ($action_info['type_id']==$v['id'])? "selected" : ""; {/code} >{$v['name']}</option>
									{/foreach}
								</select>
							</div>
							<div class="clear"></div>
						</p>
						<p>
							<label class="actionLabel">行动标题：</label>
							<div class="actionItem">
								<input type="text" value="{$action_info['action_name']}" name="action_title" size="70" />
							</div>
							<div class="clear"></div>
						</p>
						<p>
							<label class="actionLabel">行动宣言：</label>
							<div class="actionItem">
								<textarea rows="10" cols="50" name="slogan">{$action_info['slogan']}</textarea>
								<script type="text/javascript">
								CKEDITOR.replace('slogan', {
									toolbar : 'Basic',
									width : '600',
							        height : '200',
							    });
							    </script>
						    </div>
						    <div class="clear"></div>
						</p>
						<p>
							<label class="actionLabel">行动详情：</label>
							<div class="actionItem">
								<textarea rows="10" cols="50" name="action_content">{$action_info['introduce']}</textarea>
								<script type="text/javascript">
								CKEDITOR.replace('action_content', {
									width : '600',
							        height : '200',
							    });
							    </script>
						    </div>
						    <div class="clear"></div>
						</p>
						<p>
							<label class="actionLabel">上传海报：</label>
							<div class="actionItem">
								<em id="show_img"><img width="626" height="290" src="{$action_info['action_img']}"></em>
								<input type="button" id="upload_img" value="上传图片" />
							</div>
							<div class="clear"></div>
						</p>
						<p>
							<label class="actionLabel">行动时间：</label>
							<div class="actionItem">
								<label>开始：</label><input type="text" name="action_start" value="{$action_info['start_time']}" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" class="Wdate" />
								<label style="margin-left:20px;">结束：</label><input type="text" name="action_end" value="{$action_info['end_time']}" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" class="Wdate" />
							</div>
							<div class="clear"></div>
						</p>
						<p>
							<label class="actionLabel">添加视频：</label>
							<div class="actionItem">
								<input type="text" name="swfurl" value="{$action_info['swfurl']}" size="70" />
							</div>
							<div class="clear"></div>
						</p>
						<p>
							<label class="actionLabel">行动费用：</label>
							<div class="</div>">
								<input type="radio" name="q_need_pay" id="charge" {if $action_info['need_pay']}value="{$action_info['need_pay']}" checked="checked"{/if} class="n-h" /><label for="charge">收费</label>
								<span id="moneyText" {if !$action_info['need_pay']} style="display: none;"{/if}><input
									type="text" name="money" value="{$action_info['need_pay']}" size="3" /><label>元</label>
								</span>
								<input type="radio" name="q_need_pay" id="free" value="0" {if !$action_info['need_pay']} checked="checked" {/if} class="n-h" /><label for="free">免费</label>
							</div>
							<div class="clear"></div>
						</p>
						<div class="mark_loaciton">
							<div class="group_tip">
								<span>小提示:</span>点击地图：将小红气球定位到你需要的位置
							</div>
							<div class="mark_map info_map">
								<div id="map_canvas" name="map_canvas" class="formbox"
									style="width: 587px; height: 400px;"></div>
							</div>
							<div class="mark_city">
								<span class="types" style="display: none;" id="showgname"></span>
								<span class="types" style="display: none;" id="showgroup_type"></span>
								<span class="types" style="display: none;" id="showprovince"></span>
								<div class="fm_txt">
									所在位置：<span id="this_group_addr">{code}if(empty($action_info['place'])){echo "江苏省南京报业大厦";}else{echo $action_info['place'];}{/code}</span>
								</div>
							</div>
						</div>
						<p>
							<label class="actionLabel">乘车路线：</label>
							<div class="actionItem">
								<input type="text" name="bus" value="{$action_info['bus']}" size="70" />
							</div>
							<div class="clear"></div>
						</p>
						<p>
							<label class="actionLabel">行动标签：</label>
							<div class="actionItem">
								<input type="text" name="tag" value="{$action_info['tags']}" size="70" />
							</div>
							<div class="clear"></div>
						</p>
						<p>
							<label class="actionLabel">附加信息：</label>
							<div class="actionItem">
								<div class="additional">
									<span class="add_data">参加权限：</span><input type="radio" name="q_rights"
										id="everyone" value="0" class="n-h"
										{if !$action_info['rights']} checked="checked" {/if} /><label for="everyone" style="margin-right:10px;">任何人可参加</label>
									<input type="radio" name="q_rights" id="audit" value="1"
										class="n-h" {if $action_info['rights']} checked="checked" {/if} /><label
										for="audit">需要我审核</label>
								</div>
								<!--  <div class="additional">
									<label class="add_data">更多组织者：</label><input type="text"
										name="q_connection_user" value="{$action_info['connection_user']}" size="50" />
								</div>
								<div class="additional">
									<label class="add_data">关联圈子：</label><input type="text"
										name="q_connection_group" value="{$action_info['connection_group']}" size="50" />
								</div>
								-->
							</div>
							<div class="clear"></div>
						</p>
						
						<input type="hidden" name="hid_lat" value="{$action_info['lat']}" id="g_lat" />
						<input type="hidden" name="hid_lng" value="{$action_info['lng']}" id="g_lng" />
						<input type="hidden" name="action_id" value="{$action_info['id']}" id="action_id" />
						<input type="hidden" name="group_addr" value="{$action_info['place']}" id="group_addr" />
						<input type="hidden" name="id" value="{$action_info['img_id']}" />
						<input type="hidden" name="host" value="{$action_info['img_host']}" />
						<input type="hidden" name="filepath" value="{$action_info['img_filepath']}" />
						<div class="add_group_btn">
							<input type="submit" name="add_group_btn" value="保存" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
{template:./footer}