{template:head}
{css:ad_style}
{js:ad}
{css:column_node}
{js:column_node}


<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post"   id="content_form">
<h2>新增消息</h2>
<ul class="form_ul">
{code}
	$item_source = array(
		'class' => 'down_list',
		'show' => 'item_show',
		'width' => 100,/*列表宽度*/		
		'state' => 0, /*0--正常数据选择列表，1--日期选择*/
		'is_sub'=>1,
	);
	$default = $group_id ? $group_id : -1;
	$group_data[$default] = '选择分类';
	foreach($group as $k =>$v)
	{
		$group_data[$v['id']] = $v['title'];
	}
{/code}


<script>
$(function(){
	$.ajax({
		url:'./run.php?mid=477&a=list_org&infrm=1',
		cache:false,
		type:'POST',
		success:function(datas)
		{
			$('#get_orgs').html(datas);
		}
	});
});




$("#show_orgs").live("change", function() {
	var id = $('#show_orgs option:selected').val();
	$.ajax({
		url:'./run.php?mid=477&a=list_org_users&org_id='+id+'&infrm=1',
		cache:false,
		type:'POST',
		success:function(datas)
		{
			$('#get_org_users').html(datas);
		}
	});	
});

</script>



{code}
if(isset($_GET['token_id']))
{/code}
	<input type="text" name="token_id" value="{$_GET['token_id']}" style="display:none"/>
{code}
eles
{/code}
	






<li class="i">
	<div class="form_ul_div clear" >
		<span class="title">选择用户：</span>
		<span id="get_orgs"></span>
		<span id="get_org_users"></span>
	</div>
</li>

<li class="i">
	<div class="form_ul_div clear">
		<span class="title">内容：</span><textarea name="content">{$content}</textarea>
	</div>
</li>

</ul>
<input type="hidden" name="a" value="create" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="新增消息" class="button_6_14"/>
<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>

{template:foot}