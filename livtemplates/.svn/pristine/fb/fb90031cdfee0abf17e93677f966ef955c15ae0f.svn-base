{template:head}
{css:ad_style}
{css:column_node}
{js:interview}
<style type="text/css">
.source_item {cursor:pointer; border:1px solid #CCC; display:inline-block; padding:3px 5px; margin:5px;}
</style>
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
				<h2>{$optext}访谈信息</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">访谈主题：</span>
								<input type="text" value="{$formdata['title']}" name='title' style="width:440px;">
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">访谈描述：</span>
								<textarea rows="3" cols="80" name="description">{$formdata['description']}</textarea>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div"><span class="title">预告时间：</span>
								<input type="text" name="notice_time" value="{$formdata['notice_time']}" style="width:220px;"  onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm:ss'})">
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title overflow">开始时间：</span>
								<input type="text" name="start_time" value="{$formdata['start_time']}"  style="width:220px;"  onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm:ss'})">
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">访谈时长：</span>
								<input type="text" name="input_time" value="{$formdata['input_time']}"  style="width:50px;" onkeyup="check_input_time(this)">
								<font class="important"></font>
								<select name="input_time_unit">
									<option value="day" {if  $formdata['input_time_unit']=='day'}selected="selected"{/if}>天</option>
									<option value="hour" {if  $formdata['input_time_unit']=='hour'}selected="selected"{/if}>小时</option>
									<option value="minute" {if  $formdata['input_time_unit']=='minute'}selected="selected"{/if}>分钟</option>
								</select>
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">能否提问：</span>
								<input type="radio" name="is_pre_ask" value="1" {if  $formdata['is_pre_ask']==1}checked="checked"{/if}/> 能
								<input type="radio" name="is_pre_ask" value="0" {if  $formdata['is_pre_ask']==0}checked="checked"{/if}/> 否
								
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">需要登录：</span>
								<input type="radio" name="need_login" value="1"  {if  $formdata['need_login']==1}checked="checked"{/if}/> 是
								<input type="radio" name="need_login" value="0"  {if  $formdata['need_login']==0}checked="checked"{/if}/> 否
								
							</div>
						</li>
						{if $a != 'create'}
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">设为历史：</span>
								<input type="radio" name="is_lishi" value="1"  {if  $formdata['is_lishi']==1}checked="checked"{/if}/> 是
								<input type="radio" name="is_lishi" value="0"  {if  $formdata['is_lishi']==0}checked="checked"{/if}/> 否
								
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">是否关闭：</span>
								<input type="radio" name="isclose" value="1"  {if  $formdata['isclose']==1}checked="checked"{/if}/> 是
								<input type="radio" name="isclose" value="0"  {if  $formdata['isclose']==0}checked="checked"{/if}/> 否
							
							</div>
						</li>
						{/if}
						{code}
							$object_css = array(
								'class' => 'transcoding down_list',
								'show' => 'object_type_show',
								'width' => 120,	
								'state' => 0,
							);
							
							$formdata['object_type'] = $formdata['object_type'] ? $formdata['object_type'] : 0;
						{/code}
						
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">主题类型：</span>
								{template:form/search_source,object_type,$formdata['object_type'],$_configs['object_type'],$object_css}			
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">直播源：</span>
								{foreach $show_liv[0] as $key=>$val}
								<input type="checkbox" name="live_source[]" value="{$key}" {if in_array($key,$formdata['live_source'])} checked="checked" {/if}/>{$val}
								{/foreach}
							</div>					
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span><font color='red'>*</font>为必填选项</span>
							</div>
						</li>
					</ul>
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}