{template:head}
{js:ad}
<script type="text/javascript">

	function hg_put_settings(html)
	{
		$('#config_show').html(html);
	}

	function hg_get_cfg(app_uniqueid,module_uniqueid,obj)
	{
		$('#nav_left div').css('background','#fcf4ea');
		$(obj).css('background','#70a9de');
		var url = "run.php?mid="+gMid+"&a=get_settings&module_uniqueid="+module_uniqueid+"&app_uniqueid="+app_uniqueid;
		hg_ajax_post(url);
	}

</script>
<style type="text/css">
.relate_c{width:1000px;height:746px;}
.nav_left{width:200px;height:100%;border:1px solid #77b7f8;float:left;background:#f0f0f0;text-align:center;}
.nav_left div{width:198px;height:38px;background:#fcf4ea;border:1px solid #ffffff;line-height:38px;text-align:center;cursor:pointer;}
.config_show{width:760px;height:100%;float:left;}
.config_show .config_show_box{width:733px;height:772px;margin:0 auto;margin-top:13px;}
.config_show .config_show_box .global_config{width:100%;height:350px;border:1px solid #77b7f8;overflow:auto;background:#f0f0f0;}
.config_show .config_show_box .global_config .item_box{width:500px;height:30px;margin:0 auto;margin-top:10px;}
.config_show .config_show_box .global_config .item_box div{width:200px;height:30px;text-align:left;float:left;line-height:30px;}
.config_show .config_show_box .global_config .item_box input{float:left;}

</style>
<div class="relate_c">
	<div class="nav_left" id="nav_left">
		{foreach $list[0] AS $k => $v}
			<div onclick="hg_get_cfg('{$v['fbundle']}','{$v['bundle']}',this)">{$v['name']}</div>
		{/foreach}
	</div>
	<div class="config_show" id="config_show"></div>
</div>
{template:foot}

