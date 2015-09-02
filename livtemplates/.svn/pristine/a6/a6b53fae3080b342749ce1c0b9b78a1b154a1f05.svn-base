{template:head}
{css:ad_style}
{css:column_node}
{js:jquery-ui-1.8.16.custom.min}
{css:jquery.lightbox-0.5}
{js:jquery.lightbox-0.5}
<style type="text/css">
.source_item {cursor:pointer; border:1px solid #CCC; display:inline-block; padding:3px 5px; margin:5px;}
.weather-img-a{float:right;position:relative;margin-right:10px;}
.weather-img-a span{position:absolute;top:-18px;right:-18px;font-size:18px;}
</style>
<script type="text/javascript">
function del_pic(id,type)
{
	if(type==1)
	{
		if(confirm("确定要删除自定义图片吗?"))
		var url = "run.php?mid="+gMid+"&a=del_pic&id="+id+"&type="+type;
		hg_ajax_post(url);
	}
	if(type==2)
	{
		if(confirm("确定要删除背景图片吗?"))
		{
			var url = "run.php?mid="+gMid+"&a=del_pic&id="+id+"&type="+type;
			hg_ajax_post(url);
		}
	}
	
}
function hg_del_pic_back(json)
{
	var json_data = $.parseJSON(json);
	for(var a in json_data.id)
	{
		if(json_data.type==1)
		{
			$("#user_pic_"+json_data.id[a]).html('');
		}
		if(json_data.type==2)
		{
			$("#bg_pic_"+json_data.id[a]).html('');
		}
	}	
}
function preview_system_image(id)
{
	 $('#system_image_' +id+ ' a').lightBox();
}
function preview_app_user_image(id)
{
	 $('#app_user_image_' +id+ ' a').lightBox();
}
function preview_app_bg_image(id)
{
	 $('#app_bg_image_' +id+ ' a').lightBox();
}
function preview_user_image(id)
{
	$('#user_image_' +id+ ' a').lightBox();
}
function preview_bg_image(id)
{
	$('#bg_image_' +id+ ' a').lightBox();
}
function delete_app_user_image(id, btn)
{
	$(btn).parent().remove();
	$('#delete_app_user_image_'+id).val(1);
}
function delete_app_bg_image(id, btn)
{
	$(btn).parent().remove();
	$('#delete_app_bg_image_'+id).val(1);
}
function delete_user_image(btn)
{
	$(btn).parent().remove();
	$('#delete_user_image').val(1);
}
function delete_bg_image(btn)
{
	$(btn).parent().remove();
	$('#delete_bg_image').val(1);
}
</script>
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
				<h2>{$optext}天气图片</h2> 
				<ul class="form_ul">

					<li class="i">
						<div class="form_ul_div">
							<span  class="title">图片名：</span>
							<input type="text" value="{$formdata['title']}" name='title' style="width:440px;" {if $a=='update'} disabled="disabled" {/if}>
							<font class="important" style="color:red">*</font>
						</div>
					</li>
					{if $formdata['id']}
					<li class="i">
						<div class="form_ul_div" style="min-height: 20px" id = "system_image_{$formdata['id']}">
						<span  class="title">系统图：</span>
						{if $formdata['system_img'] && !in_array('',array_values($formdata['system_img']))}
								{code}
									$org = $formdata['system_img']['host'].$formdata['system_img']['dir'].$formdata['system_img']['filepath'].$formdata['system_img']['filename'];
									$url = $formdata['system_img']['host'].$formdata['system_img']['dir'].'40x30/'.$formdata['system_img']['filepath'].$formdata['system_img']['filename'];
								{/code}
							<a  href="{$org}"><img alt="天气图" src="{$url}" onclick="preview_system_image({$formdata['id']})"/></a>
						{/if}	
						</div>
					</li>
					{/if}
					
					<li class="i">
						<div class="form_ul_div" >
							<span  class="title">天气图片：</span>
							<input type="file" name="Filedata_user_image"/>
							{if $formdata['user_img'] && !empty($formdata['user_img'])}				
								<div class="weather-img-a" id = "user_image_{$formdata['id']}" >
									{code}
									$org = $formdata['user_img']['host'].$formdata['user_img']['dir'].$formdata['user_img']['filepath'].$formdata['user_img']['filename'];
									$url = $formdata['user_img']['host'].$formdata['user_img']['dir'].'40x30/'.$formdata['user_img']['filepath'].$formdata['user_img']['filename'];
									{/code}
									<a  href="{$org}" ><img alt="天气图" src="{$url}" onclick="preview_user_image({$formdata['id']})"/></a>
									<span onclick="delete_user_image(this)" style="cursor: pointer">x</span>
								</div>
				
							{/if}
						</div>
					</li>
					<input type="hidden" name="delete_user_image" value="" id="delete_user_image"/>
					<li class="i">
						<div class="form_ul_div" >
							<span  class="title">背景图片：</span>
							<input type="file" name="Filedata_bg_image"/>
							{if $formdata['bg_image'] && !empty($formdata['bg_image'])}
			
								{code}
									$org = $formdata['bg_image']['host'].$formdata['bg_image']['dir'].$formdata['bg_image']['filepath'].$formdata['bg_image']['filename'];
									$url = $formdata['bg_image']['host'].$formdata['bg_image']['dir'].'40x30/'.$formdata['bg_image']['filepath'].$formdata['bg_image']['filename'];
								{/code}
								<div class="weather-img-a" id = "bg_image_{$formdata['id']}" >
									<a  href="{$org}" ><img alt="天气图" src="{$url}" onclick="preview_bg_image({$formdata['id']})"/></a>
									<span onclick="delete_bg_image(this)" style="cursor: pointer">x</span>
								</div>
							{/if}
						</div>
					</li>
					<input type="hidden" name="delete_bg_image" value="" id="delete_bg_image"/>
					
					
					
					{if !empty($apps)}
					{foreach $apps[0] as $key=>$val}
					<li class="i">
						<h4>{$val['custom_name']}</h4>
						<div class="form_ul_div" >
							<span  class="title">天气图片：</span>
							<input type="file" name="Filedata_app_user_{$val['appid']}"/>
							{if $formdata['app_user_image'] && !empty($formdata['app_user_image'])}
							{foreach $formdata['app_user_image'] as $kk=>$vv}
							{if $kk==$val['appid'] }
							{if !in_array('',array_values($vv['app_user_image']))}
								{code}
									$org = $vv['host'].$vv['dir'].$vv['filepath'].$vv['filename'];
									$url = $vv['host'].$vv['dir'].'40x30/'.$vv['filepath'].$vv['filename'];
								{/code}
								<div class="weather-img-a" id = "app_user_image_{$val['appid']}" >
									<a  href="{$org}" ><img alt="天气图" src="{$url}" onclick="preview_app_user_image({$kk})"/></a>
									<span onclick="delete_app_user_image({$val['appid']}, this)" style="cursor: pointer">x</span>
								</div>
							{/if}
							{/if}
							{/foreach}
							{/if}
						</div>
					</li>	
					
					<li class="i">
						<div class="form_ul_div">
							<span  class="title">背景图片：</span>
							<input type="file" name="Filedata_app_bg_{$val['appid']}"/>
							{if $formdata['app_bg_image'] && !empty($formdata['app_bg_image'])}
							{foreach $formdata['app_bg_image'] as $kk=>$vv}
							{if $kk==$val['appid'] }
							{if !in_array('',array_values($vv['app_bg_image']))}
								{code}
									$org = $vv['host'].$vv['dir'].$vv['filepath'].$vv['filename'];
									$url = $vv['host'].$vv['dir'].'40x30/'.$vv['filepath'].$vv['filename'];
								{/code}
								<div class="weather-img-a" id = "app_bg_image_{$val['appid']}">
									<a  href="{$org}"><img alt="背景图" src="{$url}" onclick="preview_app_bg_image({$kk})"/></a>
									<span onclick="delete_app_bg_image({$val['appid']}, this)" style="cursor: pointer">x</span>
								</div>
							{/if}
							{/if}
							{/foreach}
							{/if}
						</div>
					</li>	
					<input type="hidden" name="app[]" value="{$val['appid']}" />
					<input type="hidden" name="custom_name[]" value="{$val['custom_name']}" />
					<input type="hidden" name="delete_app_user_image[{$val['appid']}]" value="0"  id="delete_app_user_image_{$val['appid']}"/>
					<input type="hidden" name="delete_app_bg_image[{$val['appid']}]" value="0"   id="delete_app_bg_image_{$val['appid']}"/>
					{/foreach}
					{/if}
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
