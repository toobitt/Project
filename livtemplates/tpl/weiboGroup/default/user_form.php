{template:head}
{code}
	if($id)
	{
		$optext="更新";
		$ac="update";
	}
	else
	{
		$optext="新增";
		$ac="create";
	}
{/code}
{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;	
		{/code}
	{/foreach}
{/if}
{css:ad_style}
{js:ad}
{css:column_node}
{js:column_node}
<script type="text/javascript">
$(document).ready(function(){
	$("#button").click(function(){	   
		  var type_id = $("input[name='group_id']").val();
		  var keys = $("input[name='name']").val();
		  if(type_id == 0)
		  {
			  jAlert('请选择类型').position(tmp);
			  return fasle;
		  } 
		  if(keys == '')
		  {
			  jAlert('请输入搜索关键字');
		  }
		  $.get("./run.php?mid=" + gMid + "&a=search_user&type_id="+type_id,{key:keys},
				function (data)	{
				data = eval(data);
				var str = '';
				if(!data)
				{
					str += '没有搜索到用户';
				}
				else
				{
					for(var i=0;i<data.length;i++)
					{
						if($("input[name='id']").val())
						{
							str += '<label><input type="radio" onclick="change_user(this);" value="'+data[i].name+'" name="user_s" class="n-h user_s" _groupid="'+data[i].platId+'"><span>'+data[i].name+' ('+data[i].plat_type_name+')</span></label>';
						}
						else
						{
							str += '<label><input type="checkbox" onclick="add_user(this);" value="'+data[i].name+'" name="user_s" class="n-h user_s" _groupid="'+data[i].platId+'"><span>'+data[i].name+' ('+data[i].plat_type_name+')</span></label>';
						}
					}
				}
				$("#sreach_user").show();
				$("#sreach_user").html(str);
				hg_resize_nodeFrame();
		  });	
	});
	
});

function change_user(e)
{
	$("input[name='name']").val(e.value);
}


function add_user(e)
{
	var name = $("input[name='name']").val();
	if($(e).attr('checked'))
	{
		if(name == '')
		{
			$("input[name='name']").val(e.value);
		}
		else
		{
			$("input[name='name']").val(name + ',' + e.value);
		}
	}
	else
	{
		if(name.indexOf(',') < 0)
		{
			name = name.replace(e.value,'');
		}
		else
		{
			name = name.replace(','+e.value,'');
		}
		$("input[name='name']").val(name);
	}
}

</script>
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post"   id="content_form">
<h2>{$optext}用户</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">微博类型：</span>
		{code}
			$attr_date = array(
				'class' => 'down_list data_time',
				'show' => 'app_show',
				'width' => 104,/*列表宽度*/		
				'state' => 0, /*0--正常数据选择列表，1--日期选择*/
			);		
			$group_id = $group_id ? $group_id : 0;
			$default = 0;
			$group = $group[0];  
			$group[$default] = '选择类型';
		{/code}
		{template:form/search_source,group_id,$group_id,$group,$attr_date}
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">用户：</span>
		<input type="text" value='{$uname}' name='name' class="title">
		<span id="button" style="margin-left:10px;cursor:pointer;"><a>点击搜索用户</a></span>
		<span class="site_fill_tip" style="margin-left:75px">腾讯微博填写用户帐号，新浪微博填写用户昵称。批量添加用户时以逗号隔开,只能添加同一微薄类型的用户</span>
		<div id="sreach_user" style="none;margin:10px 0 0 75px;"></div>
		<!-- <input type="hidden" name="groupid" value="" id="groupid"/> -->
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">所属圈子：</span>
		{if $circle}
			{foreach $circle as $k => $v}
				<label>
				<input type="checkbox" value="{$v['id']}" name="circle_id[]" class="n-h" {if in_array($v['id'], $circle_id)}checked{/if}><span>{$v['name']}</span>
				</label>
			{/foreach}
		{else}
			<span>暂无圈子</span>
		{/if}
	</div>
</li>
</ul>
<input type="hidden" name="a" value="{$ac}" />
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}用户" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>

{template:foot}