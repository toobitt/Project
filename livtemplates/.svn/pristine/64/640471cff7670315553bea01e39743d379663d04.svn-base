{template:head}
{css:ad_style}
<div id="channel_form" style="margin-left:40%;"></div>
<div class="ad_middle">
<form name="editform" id="editform" action="./run.php?mid={$_INPUT['mid']}" method="post"  enctype='multipart/form-data' class="ad_form h_l">
	<h2><font color="green">{$formdata['server_name']}</font>的<font color="green">{$formdata['service_name']}</font>配置</h2>
	<ul class="form_ul">
		<li id="con_li" class="i clear">
			<div class="form_ul_div clear">
				<span class="title form_ul_div_l">配置：</span>
				<div class="form_ul_div_r" style="padding-top:5px;">
					<textarea style="width:100%;height:500px;" name="service_content">{$formdata['config']}</textarea>
				</div>
			</div>
		</li>
	</ul>
</br>
<input type="submit" name="sub" value="保存" id="sub" class="button_6_14"/>
<input type="hidden" name="a" value="save_config" />
<input type="hidden" name="service_name" value="{$formdata['service_name']}" />
<input type="hidden" name="config_path" value="{$formdata['config_path']}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}"  />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</form>
</div>
<div class="right_version">
	<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
</div>
{template:foot}