{template:head}
{css:ad_style}
{js:ad}
{js:hg_water}
{css:column_node}
{js:column_node}
<?php 
$op = $_INPUT['op'];
if($op=='create')
{
	$op_txt = '新增';
}
if($op=='update')
{
	$op_txt = '更新';
}

?>
<script type="text/javascript">
$(function(){
	
	$.ajax({
	
		url:'./run.php?mid={$_GET['relation_module_id']}&a=get_cate&id={$_INPUT['cate_id']}&infrm=1',
		cache:false,
		type:'POST',
		success:function(datas)
		{
			console.log( datas );
			$('#data_form').html('');
			$('#data_form').html(datas);
			var jsonList = <?php echo json_encode($formdata);?>;
			var jsonData = eval(jsonList.data);
			for(var j in jsonData)
			{
				var domid = "data\\["+j+"\\]";
				$("#"+domid).val(jsonData[j]);
			}
		}
	});
});






</script>
<form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post"   id="content_form">

<div id='data_form'></div>
<div id="data_test"></div>
<input type="hidden" name="a" value="data_{$op}">
<input type="hidden" name="cate_id" value="{$_INPUT['cate_id']}">
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
<input type="submit" id="submit_ok"  value="{$op_txt}数据" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>

{template:food}