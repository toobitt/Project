{template:head}
{css:config_style}
{code}
	$list = $settings_list[0];
	$gdbconfig = $list['gdb']['value'];
	$gdefine = $list['gdefine'];
	$gglobal = $list['gglobal'];
{/code}
<script type="text/javascript">
	function hg_opDefineCfg(obj,e)
	{
		if(e)
		{
			var html  = "<div class='child_box'  style='width:750px;height:30px;'>";
				html += "<div class='delete' style='float:left;'  onclick='hg_opDefineCfg(this,0);'><\/div>";
				html += "<div class='define_c'><div  style='margin-top:7px;'>key：<\/div><input type='text' name='const_key[]' \/><div style='margin-top:7px;margin-left:10px;'>value：<\/div><input type='text' name='const_value[]' \/><\/div>";
				html += "<div class='add'  style='float:left;' onclick='hg_opDefineCfg(this,1);' ><\/div>";
				html += "<\/div>";
			$('#Cfg_Define_Box').append(html);
		}
		else
		{
			$(obj).parent().remove();
		}
	}
</script>
<div class="config_box">
	<form action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" id="db_form" name="db_form" onsubmit="return hg_ajax_submit('db_form')">
		<div class="db_config_box">
			<div style="margin-left:20px;">
				<div class="child_box">
					<h2>数据库配置($gDBconfig)</h2>
				</div>
				<div class="child_box" style="margin-left:28px;">
					<label>host：</label>
					<input type="text" name="host"  value="{$gdbconfig['host']}" />
				</div>
				<div class="child_box"  style="margin-left:29px;">
					<label>user：</label>
					<input type="text" name="user"  value="{$gdbconfig['user']}" />
				</div>
				<div class="child_box"  style="margin-left:1px;">
					<label>password：</label>
					<input type="text" name="pass"  value="{$gdbconfig['pass']}" />
				</div>
				<div class="child_box" style="margin-left:3px;">
					<label>database：</label>
					<input type="text" name="database"  value="{$gdbconfig['database']}" />
				</div>
				<div class="child_box" style="margin-left:12px;">
					<label>charset：</label>
					<input type="text" name="charset"  value="{$gdbconfig['charset']}" />
				</div>
				<div class="child_box" style="margin-left:21px;">
					<label>prefix：</label>
					<input type="text" name="db_prefix"  value="{$gdbconfig['db_prefix']}" />
				</div>
				<div class="child_box" style="margin-left:25px;">
					<label>desc：</label>
					<input type="text" name="desc"  value="{$gdbconfig['desc']}" />
				</div>
				<div class="child_box">
					<label>pconncet：</label>
					<input type="text" name="pconncet"  value="{$gdbconfig['pconncet']}" />
				</div>
			</div>
			<input type="hidden"  name="cfg_type" value="1" />
			<input type="hidden"  name="a" value="update" />
			<input type="submit"  value="更新" class="button_6" style="float:right;margin-right:20px;" />
		</div>
	</form>
	
	<form action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data"  id="df_form" name="df_form" onsubmit="return hg_ajax_submit('df_form')">
		<div class="db_config_box" style="height:300px;overflow:auto;">
			<div style="margin-left:20px;" id="Cfg_Define_Box">
				<div class="child_box">
					<h2>常量配置(define)</h2>
				</div>
				{foreach $gdefine as $item}
				<div class="child_box" style="width:750px;height:30px;">
					<div class="define_c">
						<div style="margin-top:7px;">key：</div>
						<input type="text" name="df_key[]"  value="{$item['var_name']}" readonly="readonly"  />
						<div  style="margin-top:7px;margin-left:10px;">value：</div>
						<input type="text" name="df_val[]"  value="{$item['value']}" />
					</div>
				</div>
				{/foreach}
			</div>
			<input type="hidden" name="cfg_type" value="2" />
			<input type="hidden"  name="a" value="update" />
			<input type="submit"   value="更新" class="button_6" style="float:right;margin-right:20px;" />
		</div>
	</form>
	
	<form action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data"   id="dg_form" name="dg_form" onsubmit="return hg_ajax_submit('dg_form')">
		<div class="db_config_box" style="border:0px;overflow:auto;">
			<div style="margin-left:20px;">
				<div class="child_box">
					<h2>全局配置($gGlobalConfig)</h2>
				</div>
				
				<div class="child_box" style="width:750px;height:30px;">
					{foreach $gglobal AS $k => $v}
					<div class="define_c">
						<div style="margin-top:7px;">key：</div>
						<input type="text" name="gg_key[]" value="{$v['var_name']}" readonly="readonly"  />
						<div  style="margin-top:7px;margin-left:10px;">value：</div>
						{code}
						    if(is_array($v['value']))
						    {
						    	$v['value'] = var_export($v['value'],1);
						    }
						{/code}
						<input type="text" name="gg_val[]" value="{$v['value']}" />
					</div>
					{/foreach}
				</div>
			</div>
			<input type="hidden" name="cfg_type" value='3' />
			<input type="hidden"  name="a" value="update"  />
			<input type="hidden"  name="html" value="true"  />
			<input type="submit"  value="更新" class="button_6" style="float:right;margin-right:20px;" />
		</div>
	</form>
</div>
{template:foot}












