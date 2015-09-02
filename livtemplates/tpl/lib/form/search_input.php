{code}
if($hg_attr['width'] && $hg_attr['width'] != 104 ){
	$width = $hg_attr['width'];
}else{
	$width = 90;
}
if($hg_attr['is_sub'])
	{
		$is_submit = 0;
	}
	else
	{
		$is_submit = 1;
	}
{/code}
<script type="text/javascript">
function hg_del_keywords()
{
	var value = $('#search_list_{$hg_name}').val();
	if(value == '关键字')
	{
		$('#search_list_{$hg_name}').val('');
	}

	return true;
}
$(document).ready(function(){
	$("#search_list_{$hg_name}").focus(function(){
		$("#search_{$hg_name}").addClass("search_width");
		
	});

	$("#search_list_{$hg_name}").blur(function(){
		$("#search_{$hg_name}").removeClass("search_width");
	});	
});

</script>
<div class="search input clear {if $hg_attr['class']}{$hg_attr['class']}{/if}" id="search_{$hg_name}" style="width:{code} echo $width . 'px'{/code};">
	<span class="input_left"></span>
	<span class="input_right"></span>
	<span class="input_middle"><input style="width:{code} echo $width . 'px'{/code};" type="text" {if $hg_attr['state']==3 || !$hg_attr['state']}onblur="if( hg_blur_value({$is_submit}) ){}"{/if} class="{if $hg_attr['state'] == 2 }autocomplete{/if}" name="{$hg_name}" id="search_list_{$hg_name}" value="{if $hg_value}{$hg_value}{/if}" placeholder="{$hg_attr['place']}"  speech="speech" {if !$hg_attr['place']}x-webkit-speech="x-webkit-speech"{/if} x-webkit-grammar="builtin:translate" onkeydown='if(event.keyCode==13) return false;'/></span>
</div>