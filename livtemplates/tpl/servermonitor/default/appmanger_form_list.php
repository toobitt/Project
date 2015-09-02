{code}
$attr_server_source = array(
	'class' => 'transcoding down_list',
	'show' => 'server_name_show',
	'width' => 104,/*列表宽度*/
	'state' => 0,/*0--正常数据选择列表，1--日期选择*/
	'is_sub'=> 1,
	'onclick'=> 'hg_show_install()',
);

$server_array = array();
if(!$formdata['server_id'])
{
	$formdata['server_id'] = -1;
	$server_array[-1] = '选择服务器';
}

foreach($servers[0] AS $k => $v)
{
	$server_array[$v['id']] = $v['name'];
}

{/code}
<script type="text/javascript">
$(function(){
   $('input[name="type"]').click(function(){
	   var check_num = 0;
	   $('input[name="type"]').each(function(){
			if($(this).attr('checked'))
			{
				check_num++;
			}
	   });
	   if(check_num == 2)
	   {
		   $('input[name="type"]').attr('checked',false);
	   }
	   $(this).attr('checked','checked');
   });
});

function hg_get_app()
{
	var child = $('#app_content').html();
	if(!child)
	{
		var url = "run.php?mid="+gMid+"&a=get_app";
		hg_ajax_post(url);
	}
	else
	{
		$('#app_content').slideDown('normal');
		$('#close_app_content').show('normal');
	}
}

function hg_put_applist(html)
{
	$('#app_content').html(html).slideDown('normal',function(){
		$('#close_app_content').show('normal');
	});
}

function hg_put_name(obj)
{
	$('#app_name').val($(obj).text());
	$('#app_content').slideUp('normal');
	$('#close_app_content').hide();
}

function hg_get_dirs()
{
	var status = parseInt($('#server_id').val());
	var url = "run.php?mid="+gMid+"&a=get_dir&server_id="+status+"&dir="+$('input[name="install_dir"]').val();
	hg_request_to(url,'','','',1);
}

function hg_put_app_dir(obj)
{
	var obj = eval('('+obj+')');
	var html = '';
	for(var i = 0;i<obj.length;i++)
	{
		html += '<span onclick="hg_set_value(this);">'+obj[i]+'</span>';
	}
	$('#dir_content').html(html);
	$('#dir_content').slideDown();
	$('#close_dir_content').show();
}

function hg_set_value(obj)
{
	var install_dir = $('input[name="install_dir"]');
	var text_val = install_dir.val();
	if(text_val[text_val.length - 1] == '/')
	{
		text_val = text_val.substr(0,text_val.length - 1);
	}
	var dir = text_val + '/' + $(obj).text();
	install_dir.val(dir);
	var status = parseInt($('#server_id').val());
	var url = "run.php?mid="+gMid+"&a=get_dir&server_id="+status+"&dir="+dir;
	hg_request_to(url,'','','',1);
}

function hg_close_dir_box(obj)
{
	$('#dir_content').slideUp();
	$(obj).hide();
}

function hg_close_app_box(obj)
{
	$('#app_content').slideUp();
	$(obj).hide();
}

function hg_show_install()
{
	var status = parseInt($('#server_id').val());
	if(status != -1)
	{
		$('#install_box').slideDown();
	}
	else
	{
		$('#install_box').slideUp();
		$('input[name="install_dir"]').val('');
		$('#dir_content').slideUp().html('');
		$('#close_dir_content').hide();
	}
}
</script>
<style tyle="text/css">
   #app_content span{width:90px;height:26px;line-height:26px;border:1px solid gray;float:left;text-align:center;margin-left:8px;margin-top:8px;cursor:pointer;background:#1CE2EB;}
   #dir_content span{width:220px;height:26px;line-height:26px;border:1px solid gray;float:left;text-align:center;margin-left:1px;margin-top:1px;cursor:pointer;background:#1CE2EB;}
   #dir_content span:hover{width:220px;height:26px;line-height:26px;border:1px solid gray;float:left;text-align:center;margin-left:1px;margin-top:1px;cursor:pointer;background:#1498F0;}
   .close_dir_content{position:absolute;left:328px;top:22px;cursor:pointer;background-color:gray;margin-right:50px;padding:0px;float:right;color:rgb(255, 255, 255);border-top-left-radius:50px;border-top-right-radius:50px;border-bottom-right-radius:50px; border-bottom-left-radius:50px; width: 18px; height: 18px; line-height:18px;text-align:center;font-weight:bold;display:block;background-position:initial initial; background-repeat:initial initial;display:none;}
   #install_box{width:100%;margin-top:10px;position:relative;}
</style>
<form action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" name="serverform"  id="serverform"  onsubmit="return hg_ajax_submit('serverform');">
	<div style="width:100%;margin-top:10px;position:relative;">
		<label style="margin-left:24px;">应用名称：</label>
		<input id="app_name" type="text" name="name" value="{$formdata['name']}" style="width:242px;" onfocus="hg_get_app();" />
		<div style="position:absolute;left:356px;line-height:24px;top:0px;width:80px;height:24px;">所属服务器：</div>
		<div style="position:absolute;left:430px;top:0px;">{template:form/search_source,server_id,$formdata['server_id'],$server_array,$attr_server_source}</div>
		<span id="close_app_content" class="close_dir_content" style="left:524px;top:29px;" title="取消" onclick="hg_close_app_box(this);">X</span>
	</div>
	<div id="app_content" style="width:507px;height:auto;border:1px solid blue;margin-top:10px;margin-left:24px;overflow:hidden;padding-bottom:8px;display:none;"></div>
	<div id="install_box" style="display:{if $formdata['server_id'] == -1}none;{else}block;{/if}">
		<label style="margin-left:24px;">安装目录：</label>
		<input type="text" name="install_dir" value="{$formdata['install_dir']}" style="width:242px;" onkeyup="hg_get_dirs();" onfocus="hg_get_dirs();" />
		<div style="position:absolute;left:356px;line-height:24px;top:0px;width:80px;height:24px;">程序类型：</div>
		<div style="position:absolute;left:430px;top:2px;height:30px;">
			<div style="width:14px;height:20px;float:left;"><input type="checkbox" name="type" value="1" {if $formdata['type'] == 1}checked="checked"{/if} /></div>
			<span style="width:55px;height:15px;margin-top:3px;float:left;">MCP</span>
			<div style="width:14px;height:20px;float:left;"><input type="checkbox" name="type" value="2" {if $formdata['type'] == 2}checked="checked"{/if} /></div>
			<span style="width:55px;height:15px;margin-top:3px;float:left;">API</span>
		</div>
		<div id="dir_content" style="position:absolute;left:88px;top:30px;width:246px;height:auto;border:1px solid blue;overflow-y:auto;max-height:288px;padding-bottom:1px;background:#ffffff;display:none;"></div>
		<span id="close_dir_content" class="close_dir_content" title="取消" onclick="hg_close_dir_box(this);">X</span>
	</div>
	<div style="width:100%;margin-top:10px;margin-left:48px;">
		<label>域名：</label>
		<input type="text" name="dns"  value="{$formdata['dns']}"  style="width:442px;" />
	</div>
	<div style="width:100%;margin-top:10px;margin-left:48px;">
		<label>版本：</label>
		<input type="text" name="version"  value="{$formdata['version']}"  style="width:442px;" />
	</div>
	<div style="width:100%;margin-top:10px;">
		<input type="submit"  value="{$optext}" class="button_6" style="margin-left:430px;" />
	</div>
	<input type="hidden" value="{$a}" name="a" />
	<input type="hidden" value="{$$primary_key}" name="{$primary_key}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</form>