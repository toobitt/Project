{code}
$iframeheight = $hg_data['height'] - 20;
{/code}
<iframe src="./googlemap.php?latitude={$hg_data['latitude']}&longitude={$hg_data['longitude']}&height={$hg_data['height']}&width={$hg_data['width']}&areaname={$hg_data['areaname']}&zoomsize={$hg_data['zoomsize']}&objid={$hg_data['objid']}&drag={$hg_data['is_drag']}" height="{$iframeheight.'px'}" width="{$hg_data['width'].'px'}" id="google_map" style="margin:0;padding:0">
</iframe>
<div class="form_ul_div clear">
<span style="margin-left:70px;">经度:&nbsp;<input type="text" name="{$hg_name}" id="{$hg_name}" value="{$hg_data['longitude']}" size="35" /></span>
<span>纬度:&nbsp;<input type="text" name="$hg_value" id="$hg_value" value="{$hg_data['latitude']}" size="35" /></span>
</div>
<script type="text/javascript">
function syscPoint(value) {
	var val;
	var longitude = "{$hg_name}";
	var latitude = "$hg_value";
	if (value)
	{
		val = value.split('x');
	}
	$('#'+longitude).val(val[0]);
	$('#'+latitude).val(val[1]);
}
</script>