{template:head}
{js:ad}
{js:tuji}
{js:water_hander}
{code}
	$list = $access_settings_list[0];
{/code}
<script type="text/javascript">

	function hg_put_settings(html)
	{
		$('#config_show').html(html);
	}

	function hg_put_app_settings(html)
	{
		$('.relate_c').html(html);
	}

	function hg_get_cfg(bundle_id,module_id,obj)
	{
		$('#nav_left div').css('background','#fcf4ea');
		$(obj).css('background','#70a9de');
		var url = "run.php?mid="+gMid+"&a=get_settings&bundle_id="+bundle_id+"&module_id="+module_id;
		hg_ajax_post(url);
	}

</script>
<style type="text/css">
.relate_c{width:1000px;min-height:800px;height:auto;}
.nav_left{width:200px;min-height:800px;height:auto;border:1px solid #77b7f8;float:left;background:#f0f0f0;text-align:center;}
.nav_left div{width:198px;height:38px;background:#fcf4ea;border:1px solid #ffffff;line-height:38px;text-align:center;cursor:pointer;}
.config_show{width:760px;height:auto;float:left;}

</style>
<div class="relate_c">
	<div class="nav_left" id="nav_left">
		{foreach $list AS $k => $v}
			<div onclick="hg_get_cfg('{$v[father_bundle]}','{$v[bundle]}',this)">{$v['name']}</div>
		{/foreach}
	</div>
	<div class="config_show" id="config_show"></div>
</div>
{template:foot}

