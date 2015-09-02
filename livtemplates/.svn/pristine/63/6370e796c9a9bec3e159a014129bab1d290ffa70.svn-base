{template:head}
{code}
	$opname = "CDN";
	if($id)
	{
		$optext="更新";
		$ac="pushforfront";
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
<script>
$(function(){
	$('#datatype').change(function() {
		  var type = $("#datatype option:selected").text();
		  $(".cdndataform").attr( 'name', type+'[]' );
	});
	
});

function addinput()
{
	var obj = $("#datainput");
	html = obj.html();
	
	$("#inputappend").append("<li class='i'>"+html+"</li>");
}

</script>
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post"   id="content_form">
<h2>{$optext}{$opname}</h2>
<div id="test">

</div>
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


<li class="i">
	<div class="form_ul_div clear">
		<span class="title">类型:</span>
		<select name='type' id="cdntype">
			<option value='UpYun'>默认</option>
			<!--<option value='Varnish'>Varnish</option>-->
		</select>
	</div>
</li>

<li class="i">
	<div class="form_ul_div clear">
		<span class="title">类型:</span>
		<select id="datatype">
			<option value='urls'>url</option>
			<option value='dirs'>dir</option>
		</select>
	</div>
</li>

<li class="i" id="datainput">
	<div class="form_ul_div clear">
		<span class="title" >数据:</span>
		<!--
			<input class="cdndataform" type="text" name="urls[]" value="" size="100"><a onclick='addinput()'>+</a>
		

		<textarea name="urls">-->
		<?php
		$json = unserialize($formdata['data']);
		$datas = json_decode($json['task'],1);	
		$data = $datas['urls'];
		$string = "";
		foreach ($data as $key => $url) {
			//echo $url."\r\n";
			$string .= $url."\r\n";
		}
		$datas = "
http://www.ahtv.cn/news/jrgz/2013/08/2013-08-271214264.html
http://www.ahtv.cn/news/jrgz/2013/08/2013-08-271214574.html
http://www.ahtv.cn/news/jrgz/2013/08/2013-08-271214255.html
http://www.ahtv.cn/news/jrgz/2013/08/2013-08-271214437.html
http://www.ahtv.cn/news/jrgz/2013/08/2013-08-271214696.html
		";
		?>
		<!--</textarea>-->
		{template:form/textarea,urls,$string}
		<!--<span style="color:red;margin-left:10px;">*多个以换行隔开</span>-->	
	</div>
	<div style="color:red;margin-left:90px;">*多个以换行隔开</div>
	<!--<div class="form_ul_div clear">
		
		{template:form/textarea,testurls,$datas}
		<span class="title" >格式:</span>
                
	</div>-->
</li>
<div id="inputappend"></div>

</ul>
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="a" value="{$ac}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}{$opname}" class="button_6_14"/>
<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>

{template:foot}