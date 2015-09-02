<?php 
/* $Id: record_config_form.php 20488 2013-05-07 02:44:14Z lijiaying $ */
?>
{template:head}
{css:ad_style}

{if $a}
	{code}
/*	hg_pre($formdata);*/
		$action = $a;
	{/code}
{/if}

{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
<script type="text/javascript">
	var gId = '{$id}';
	if (!gId)
	{
		$(function(){
			set_timeshift_file_path();
		});
	}
	
	
	function set_timeshift_file_path()
	{
		var url = './run.php?mid=' + gMid + '&a=set_timeshift_file_path';
		hg_ajax_post(url,'','','set_timeshift_file_path_callback');
	}
	
	function set_timeshift_file_path_callback(obj)
	{
		var obj = obj[0];
		if (obj)
		{
			$('#timeshift_filepath').html(obj);
		}
	}
</script>
<div class="ad_middle">
	<form name="editform" id="editform" action="./run.php?mid={$_INPUT['mid']}" method="post" enctype='multipart/form-data' class="ad_form h_l">
		<h2>{if $_INPUT['copy']}复制{else}{$optext}{/if}配置</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div">
					<span class="title">名称：</span>
					<input type="text" name="name" value="{if $_INPUT['copy']}复制{/if}{$name}" />
				</div>
				<div class="form_ul_div">	
					<span class="title">描述：</span>
					{template:form/textarea,brief,$brief}
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">	
					<span class="title">主机：</span>
					<input type="text" style="width:250px;" name="record_host" value="{$record_host}" placeholder="" />
					<span>端口：</span>
					<input type="text" style="width:80px;"  name="record_port" value="{if !$record_port}{$_configs['record_server']['port']}{else}{$record_port}{/if}" />
				</div>
				<div class="form_ul_div">	
					<span class="title" style="width:155px;">源视频目录访问地址：</span>
					<input type="text" style="width:50px;" value="http://" disabled="disabled" />
					<input type="text" style="width:235px;" name="record_output_host" value="{$record_output_host}" placeholder="" />
				</div>
				<div class="form_ul_div">	
					<span class="title"></span>
					<span style="margin-left: -40px;">源视频目录访问地址需要配置到此路径</span>
					<font style="margin-left: 10px;font-weight: bold;" id="timeshift_filepath">{$timeshift_filepath}</font>
				</div>
			</li>

		</ul>
	</br>
	<input type="submit" name="sub" value="{if $_INPUT['copy']}复制{else}{$optext}{/if}" id="sub" class="button_6_14"/>
	{if $_INPUT['copy']}
	<input type="hidden" name="a" value="create" id="action" />
	{else}	
	<input type="hidden" name="a" value="{$action}" id="action" />
	{/if}
	<input type="hidden" name="{$primary_key}" value="{$id}"/>
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</form>
</div>
<div class="right_version">
	<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
</div>
{template:foot}