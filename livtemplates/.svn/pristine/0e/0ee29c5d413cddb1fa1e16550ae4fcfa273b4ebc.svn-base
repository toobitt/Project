{template:head}
{css:ad_style}
{js:ad}
{js:hg_water}
{css:column_node}
{js:column_node}
<script type="text/javascript">
$(function(){
	$.ajax({
		url:'./run.php?mid=527&a=desc&tbname={$_INPUT['tbname']}&infrm=1',
		cache:false,
		type:'POST',
		success:function(datas)
		{
			$('#data_form').html('');
			$('#data_form').html(datas);
		}
	});
});
</script>
<form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post"   id="content_form">

<div id='data_form'></div>
<input type="hidden" name="a" value="create">
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
<input type="submit" id="submit_ok"  value="{$optext}数据" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>

{template:food}