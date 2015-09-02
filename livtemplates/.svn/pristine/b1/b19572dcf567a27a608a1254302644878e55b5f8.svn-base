<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.3"></script>
<style>
label.error{color:red;font-size:10px;}
</style>
{code}
$data = null;
if($formdata)
{
	$formdata['action_compic'] = ($formdata['action_compic'] ? ($formdata['action_compic']) : array());
	foreach ($formdata['action_compic'] as $k => $v) 
	{
		$data[] = array(
			'id' => $v['m_id'],
			'url' => $v['img_info']['host'].$v['img_info']['dir']."100x100/".$v['img_info']['filepath'].$v['img_info']['filename'],
			'data' => serialize($v['img_info']),
			'info' => $v['img_intro']
		);
	}
}
{/code}

{css:topic_create}
<div class="pubAct-left">
	{template:unit/nav_form}
	<div class="pub-form">
		<div class="pub-head wd150">
			<em></em><span class="title-icon activity-icon">后台编辑</span>
			<span class="pub-tip">标注<b class="x-red">*</b>为必填项</span>
		</div>
		<div class="pub-con">
			<form id="actionForm" name="form1" method="post" action="run.php">
				<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
				<input type="hidden" name="a" value="update" />
				<div class="pub-item">
					<label for="pub-sub" class="pub-input-title"><em class="x-red">*</em>标题：</label>
					<input type="text"  id="pub-sub" name="q_action_name" data-default="建议不超过20个字"  value="{if $formdata}{$formdata['action_name']}{else}建议不超过20个字{/if}"
						class="pub-txt-input need-focusblurtxt" />
				</div>
				<div class="pub-item">
					<label for="pub-word" class="pub-input-title"><em class="x-red">*</em>宣言：</label>
					<textarea  id="pub-word" cols="45" rows="5" name="q_slogan" data-default="描述你行动宣言的一段话，朗朗上口，有号召力，建议不超过70字"
						class="pub-word need-focusblurtxt">{if $formdata}{$formdata['slogan']}{else}描述你行动宣言的一段话，朗朗上口，有号召力，建议不超过70字{/if}</textarea>
				</div>
				<div class="pub-item">
					<label for="pub-time" class="pub-input-title"><em class="x-red">*</em>报名时间：</label>
					<input  type="text" name="q_start_time" 
						class="pub-txt-input pub-startime mr10" id="pub-startime"
						value="{if $formdata['start_time']}{code}echo date("Y-m-d H:i",$formdata['start_time']);{/code}{else}报名开始时间{/if}" />至 
					<input  type="text"
						class="pub-txt-input pub-endtime ml10" id="pub-endtime" name="q_end_time"
						value="{if $formdata['end_time']}{code}echo date("Y-m-d H:i",$formdata['end_time']);{/code}{else}报名结束时间{/if}" />
				</div>
				<div class="pub-item">
					<label for="pub-time" class="pub-input-title">行动时间：</label>
					<div class="pub-item-right">
						<label id="timeTog1" class="mr25 ft12"><input name="has_register_time" type="radio" value="0" {if !$formdata['register_time']}checked="checked" {/if}/> 无</label> 
						<label id="timeTog2" class="ft12"><input name="has_register_time" type="radio" value="1" {if $formdata['register_time']}checked="checked" {/if}/> 有</label>
						<span class="small ml30">行动时间是指行动真正开展或执行的时间，有别于报名时间。</span>
						<div class="radio-detail" {if $formdata['register_time']}style="display:block;"{/if}>
							<input type="text"  id="act-time" name="q_register_time" value="{$formdata['register_time']}"
								class="pub-txt-input wd330" />
						</div>
						<p class="hr"></p>
					</div>
				</div>
				<div class="pub-item">
					<label class="pub-input-title">地点：</label>
					<div class="pub-item-right">
						<span id="addressToggle1"><label class="mr25 ft12"><input name="has_address" type="radio" value="0" {if !($formdata['address'] || $formdata['location'])}checked="checked" {/if}/> 没地址</label></span>
						<span id="addressToggle2"><label class="ft12"><input name="has_address" type="radio" value="1"  {if $formdata['address'] || $formdata['location']}checked="checked" {/if}/> 有地址</label></span>
						
						<div class="radio-detail address-detail" {if !($formdata['address'] || $formdata['location'])}style="display:none;"{else}style="display:block;"{/if}>
							<div class="pub-item" id="address-select-box">
								<select name="q_province"  id="province" class="mr10">
								</select> 
								<select name="q_city" id="city" class="mr10">	
								</select> 
								<select name="q_area" id="area" class="mr10">
								</select>
							</div>
							<div class="pub-item">
								<input  type="text" class="pub-txt-input pub-address-input need-focusblurtxt" data-default="详细地址" name="q_address" id="pub-address" value="{if $formdata['address']}{$formdata['address']}{else}详细地址{/if}" />
							</div>
							<div class="pub-map">
								<div id="baidu-map" style="height:320px;"></div>
							</div>
							<!-- 地图信息 -->
							<input type="hidden" name="q_location" id="location"  value="{$formdata['location']}"/> 
							<!-- 地图信息 -->
						</div>
						<p class="hr"></p>
					</div>
				</div>
				<div class="pub-item clearfix" style="position:relative;">
					<label class="pub-input-title"><em class="x-red">*</em>海报：</label>
					<input type="file" id="file-upload1" />
					<div style="float:left;margin:-10px 0 0 20px;width:400px;" id="pic-list1"></div><span style="position:absolute;left:280px;top:10px;color:#888;font-size:12px;">最佳尺寸高300像素X宽700像素</span>
					<p class="poster-view">
					{code}$haibao = hg_bulid_img($formdata['action_img'], '','');{/code}
					{if $haibao}
						<img src="{$haibao}" />
					{/if}
					</p>
					{code}$link=  serialize($formdata['action_img']);{/code}
					<input type="hidden"  id="action_img" name="q_action_img" value='{$link}'/> 
				</div>
				<div class="pub-item">
					<label class="pub-input-title">详细信息：</label>
					<div class="topic-form-box" style="padding:0;clear:left;margin:auto;padding-top:20px;min-height:auto;">
					<div class="form-item" id="vodTitle" style="position:relative;">
						<label>链接地址<span class="small-text">（支持 优酷 / 土豆 / 酷6 / 六间房 / 56网 / 乐视 / 新浪 / 搜狐 / 腾讯 的视频发布）</span></label>
						<input type="text" name="url" id="url" style="width:548px;" placeholder="请将第三方视频网站的视频发布链接复制到框内"  value="{$formdata['action_video']['url']}"/><label class="error" style="display:none;font-size:12px;margin-top:5px;">不支持的视频地址</label>
					</div>
					<div class="form-item video-pic">
						<img src="" />
						<a class="vod-shwo-close"></a>
					</div>
					<div class="form-item pic-uploader">
						<input type="file" id="file-upload" /> <span class="small-text">jpg、gif、png或bmp格式，单张图片不超过2MB</span>
					</div>
					<div class="form-item" id="pic-list">	
					</div>
					<div class="form-item"><textarea id="textEditor" style="width:560px;" name="q_summary">{$formdata['summary']}</textarea></div>
					</div>
				</div>
				<div class="pub-item">
					 <label class="pub-input-title">标签：</label>
					 <input type="input" name="q_mark" value="{$formdata['mark']}" need-tags="true" data-brief="填写小组标签有助于区别于其他小组，帮助用户深入了解并快速查找到本小组。此处最多可以填写六个标签。" />
					
				</div>
				<div class="pub-item">
					<label class="pub-input-title">人数：</label>
					<div class="pub-item-right">
						<label class="mr25 ft12" id="peapleNumTog1"><input name="has_need_num" type="radio" value="1" {if $formdata['need_num']}checked="checked" {/if}/> 限制</label>
						<label class="ft12" id="peapleNumTog2"><input name="has_need_num" type="radio" value="1" {if !$formdata['need_num']}checked="checked" {/if}/> 不限制</label>
						<div class="radio-detail" {if $formdata['need_num']}style="display:block"{/if}>
							满额<input type="text"  id="person-number" name="q_need_num" value="{$formdata['need_num']}"
								class="pub-txt-input wd80 ml10 mr10" />人
						</div>
						<p class="hr"></p>
					</div>
				</div>
				<div class="pub-item">
					<label class="pub-input-title">费用：</label>
					<div class="pub-item-right">
						<label id="payTog1" class="mr25 ft12"><input name="has_need_pay" type="radio" value="0" {if !$formdata['need_pay']}checked="checked" {/if}/> 免费</label>
						<label id="payTog2" class="ft12"><input name="has_need_pay" type="radio" value="1" {if $formdata['need_pay']}checked="checked" {/if}/> 收费</label>
						<div class="radio-detail" {if $formdata['need_pay']}style="display:block"{/if}>
							<input type="text"  id="money" name="q_need_pay" value="{$formdata['need_pay']}"
								class="pub-txt-input wd110 mr10" />元/人
						</div>
						<p class="hr"></p>
					</div>
				</div>
				<div class="pub-item">
					 <label class="pub-input-title">讨论标签：</label>
					 <input type="input" name="q_topic_mark" value="{$formdata['topic_mark']}" _rangelength="1" need-tags="true" data-brief="如果您想引导成员就一个或多个话题进行集中讨论，请将这些话题设为“主题建议标签”，这些标签会出现在讨论区发布话题页面内。" />
				</div>
				<div class="pub-item">
					<label for="pub-number" class="pub-input-title">报名信息：</label>
					<div class="pub-item-right">
						<span id="need_infoToggle1">
							<label class="mr25 ft12"><input name="has_need_info" type="radio" value="0" {if !$formdata['need_info']}checked="checked" {/if}/> 不需要</label>
						</span>
						<span id="need_infoToggle2">
							<label class="ft12"><input name="has_need_info" type="radio" id="radi4o" value="1" {if $formdata['need_info']}checked="checked" {/if}/> 需要</label>
						</span>
						<span class="small ml10">
							如果你需要报名者的信息，请选择“需要”，并在下拉框内勾选相关选项
						</span>
						{code}
							$arr = explode(',',$formdata['need_info']);
						{/code}
						<div class="radio-detail information-detail" {if $formdata['need_info']}style="display:block"{/if}>
							<ul class="information-list">
								<li class="info-default {code}
							if(in_array('true_name',$arr))
							{	
								echo ' info-selected';
							}
						{/code}"><em class="real-name"></em>
									<label for="checkbox">真实姓名</label> 
									<input type="checkbox" name="q_need_info[]" value="true_name"  {code}
							if(in_array('true_name',$arr))
							{	
								echo 'checked="checked"';
							}
						{/code}/></li>
								<li class="info-default 
								{code}
							if(in_array('id_card',$arr))
							{	
								echo ' info-selected';
							}
						{/code}"><em class="identity-card"></em> 
									<label for="checkbox1">身份证</label>
									<input type="checkbox" name="q_need_info[]" value="id_card" {code}
							if(in_array('id_card',$arr))
							{	
								echo 'checked="checked"';
							}
						{/code}/></li>
								<li class="info-default {code}
							if(in_array('mobile_num',$arr))
							{	
								echo ' info-selected';
							}
						{/code}" ><em class="information-tel"></em> 
									<label for="checkbox1">手机号码</label> 
									<input type="checkbox" name="q_need_info[]" value="mobile_num" {code}
							if(in_array('mobile_num',$arr))
							{	
								echo 'checked="checked"';
							}
						{/code}/></li>
								<li class="info-default {code}
							if(in_array('birthday',$arr))
							{	
								echo ' info-selected';
							}
						{/code}"><em class="information-birthday"></em>
									<label for="checkbox">生日</label> 
									<input type="checkbox" name="q_need_info[]" value="birthday" {code}
							if(in_array('birthday',$arr))
							{	
								echo 'checked="checked"';
							}
						{/code}/></li>
								<li class="info-default {code}
							if(in_array('part_place',$arr))
							{	
								echo ' info-selected';
							}
						{/code}"><em class="information-address"></em>
									<label for="checkbox1">地址</label> 
									<input type="checkbox" name="q_need_info[]" value="part_place" {code}
							if(in_array('part_place',$arr))
							{	
								echo 'checked="checked"';
							}
						{/code}/></li>
								<li class="info-default {code}
							if(in_array('email',$arr))
							{	
								echo ' info-selected';
							}
						{/code}"><em class="information-email"></em> 
									<label for="checkbox1">邮箱</label>
									<input type="checkbox" name="q_need_info[]" value="email" {code}
							if(in_array('email',$arr))
							{	
								echo 'checked="checked"';
							}
						{/code}/></li>
								<li class="info-default {code}
							if(in_array('unit_name',$arr))
							{	
								echo ' info-selected';
							}
						{/code}"><em class="information-company"></em>
									<label for="checkbox1">单位名称</label> 
									<input type="checkbox" name="q_need_info[]" value="unit_name" {code}
							if(in_array('unit_name',$arr))
							{	
								echo 'checked="checked"';
							}
						{/code}/></li>
								<li class="info-default {code}
							if(in_array('part_num',$arr))
							{	
								echo ' info-selected';
							}
						{/code}"><em class="information-number"></em> 
									<label for="checkbox1">参与人数</label>
									<input type="checkbox" name="q_need_info[]" value="part_num" {code}
							if(in_array('part_num',$arr))
							{	
								echo 'checked="checked"';
							}
						{/code}/></li>
								<li class="info-default {code}
							if(in_array('politics_state',$arr))
							{	
								echo ' info-selected';
							}
						{/code}"><em class="politics-status"></em> 
									<label for="checkbox1">政治面貌</label> 
									<input type="checkbox" name="q_need_info[]" value="politics_state" {code}
							if(in_array('politics_state',$arr))
							{	
								echo 'checked="checked"';
							}
						{/code}/></li>
								<li class="info-default {code}
							if(in_array('part_declaration',$arr))
							{	
								echo ' info-selected';
							}
						{/code}"><em class="information-word"></em> 
									<label for="checkbox1">参与宣言</label> 
									<input type="checkbox" name="q_need_info[]" value="part_declaration" {code}
							if(in_array('part_declaration',$arr))
							{	
								echo 'checked="checked"';
							}
						{/code}/></li>
							</ul>
						</div>
					</div>
				</div>
				<!-- 小组信息 -->
				<input type="hidden" name="q_team_id" id="team_id"  value="{if $formdata['team_id']}{$formdata['team_id']}{else}{$team_id}{/if}"/> 
				<!-- 小组信息 -->
				<!-- 活动类型-->
				<input type="hidden" name="q_team_type" id="team_type"  value="{if $formdata['team_type']}{$formdata['team_type']}{else}{$team_type}{/if}"/> 
				<!-- 活动类型 -->
				<!-- 活动id-->
				{if $formdata['action_id']}<input type="hidden" name="q_action_id" id="action_id"  value="{$formdata['action_id']}"/> {/if}
				<!-- 活动id -->
				<div class="pub-controll clearfix">
					<input type="submit" style="margin-left:75px;" name="button" id="button" value="确定"
						 /> <!--<a href="#" class="write-btn-cancel">取消</a>-->
				</div>
			</form>
		</div>
	</div>
</div>
{template:unit/tags}
{css:uploadify}
{css:action_create}
{css:jquery-ui}
{css:ueditor}
<script id="template_pic" type="text/template">
<div class="uploadify-queue-item upload-pic-list">
	<div class="upload-pic-wrap">
		<img src="<%= url %>">
	</div>
	<a class="upload-pic-close"></a>
	<textarea style="width:400px;" class="upload-pic-brief" name="review_info[<%= id %>]" placeholder="在此处对图片进行描述，发布后在图片下方展示"><%= info %></textarea>
	<input type="hidden" name="review_img[<%= id %>]" value='<%= data %>'>
</div>
</script>
<script>
seajs.use( ['$' ,JS_PATH + 'act/action_create.js', JS_PATH + '/act/focusBlurTxt.js'], function ($, Action, fnc) {
	{js:act/modules/uploadify/jquery.uploadify-3.1}
	$(function ($) {
		new Action({ 
			el: $('#actionForm'),
			province: "{$formdata['province']}",
			city: "{$formdata['city']}",
			area: "{$formdata['area']}",
			data: {code}echo json_encode($data);{/code}
		 });
	});
	fnc( $('form') );
});
</script>
