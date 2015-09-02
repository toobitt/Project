{code}
if($hg_attr['width'] && $hg_attr['width'] != 104 ){
	$width = $hg_attr['width'];
}else{
	$width = 90;
}
$_INPUT['start_weight'] = isset($_INPUT['start_weight']) ? $_INPUT['start_weight'] : -1;
$_INPUT['end_weight'] = isset($_INPUT['end_weight']) ? $_INPUT['end_weight'] : -1;
if ( $_INPUT['start_weight'] + $_INPUT['end_weight'] == -2 ) {
	$weightLabelShow = "所有权重";
} elseif ($_INPUT['start_weight'] == -1) {
	$weightLabelShow = "权重小于".$_INPUT['end_weight'];
} elseif ($_INPUT['end_weight'] == -1) {
	$weightLabelShow = "权重大于".$_INPUT['start_weight'];
} else {
	$weightLabelShow = "权重(".$_INPUT['start_weight']."-".$_INPUT['end_weight'].")";
}
$_INPUT['start_weight'] = $_INPUT['start_weight'] == -1 ? 0 : $_INPUT['start_weight'];
$_INPUT['end_weight'] = $_INPUT['end_weight'] == -1 ? 100 : $_INPUT['end_weight'];
{/code}
<style>
.weight-box{display:none;position:relative;width:262px;border:1px solid #cdcdcd;background:#fff;z-index:10000;}
.weight-box em{font-style:normal;}
.mb8{margin-bottom:8px;}
.mt10{margin-top:10px;}
.ml10{margin-left:10px;}
.weight-box .dotline{margin-bottom:10px;padding:0 10px 10px;background:url({$RESOURCE_URL}dottedLine.png) repeat-x bottom;}
.common-weight .item{display:inline-block;margin-right:20px;}
.common-weight .item input{float:left;margin-right:8px;}
.common-weight .item .weight-radio{float:left;margin:2px 10px 0 0;}
.common-weight .item .number{cursor:pointer;vertical-align:middle;}
.common-weight .item .number i{font-style:normal;}
.common-weight .item .number .start{margin-right:5px;}
.common-weight .item .number .end{margin-left:5px;}
.weight-box .weight-list{width:260px;}
.weight-box .weight-list ul{position:relative;width:252px;padding:0 0 8px 0;top:8px;left:6px;border:0;}
.weight-box .weight-list li{height:36px;width:114px;border:1px solid #d8d8d8;border-radius:2px;background:#f5f5f5;float:left;margin:2px 5px;text-align:left;}
.weight-list .weight-number{font-size:10px;height:24px;width:24px;line-height:16px;border-radius:24px;display:block;float:left;background:#fc1712;margin:6px 5px;text-align:center;}
.weight-list .weight-number span{display:block;width:auto;height:16px;margin:4px auto;border-radius:16px;}
.weight-list .weight-describe{border:0;color:#868686;font-size:12px;display:inline;line-height:36px;}
.weight-box .define-weight{padding:10px 10px 20px 10px;}
.weight-box .define-weight .txt{width:34px;text-align:center;padding:0;}
.weight-box .define-weight .btn{width:65px;height:24px;border:1px solid #adadad;border-radius:2px;margin-left:15px;background:#e2e3e5;}
.helpBox{cursor:pointer;}
.helpBox:hover a.help-icon{color:#ff9b01;}
.helpInfo{display:none;position:absolute;top:-11px;left:-2px;padding-left:18px;}
.helpInfo div{width:110px;height:170px;border:1px solid #ccc;padding:15px 10px 15px;background:#fff8ee;color:#ff9b01;border-left:0;overflow:hidden;line-height:1.6;}
.helpBox:hover .helpInfo{display:block;}
.slider-weight-box{vertical-align:middle;padding:0 10px;width:230px;}
.slider-weight-box i{display:inline-block;height:14px;line-height:12px;}
#weightSlider{float:left;width:165px;height:6px;margin:2px 15px 0 10px;border-radius:2px;}
.ui-slider-horizontal .ui-slider-range{background:#6d6d6d;}
.weightSliderLable{display:block;}
.slider-weight-box .start,.slider-weight-box .end{float:left;font-style:normal;}
.current-quanzhong .ui-widget-content .ui-state-default,.slider-weight-box .ui-widget-content .ui-state-default{width:18px;height:18px;background:url({$RESOURCE_URL}slider_button.png) no-repeat;border:0;}
.slider-number{display:block;position:absolute;top:24px;width:20px;height:19px;line-height:22px;color:#333;background:url({$RESOURCE_URL}slider_num_bg.png) no-repeat;font-size:11px;-webkit-text-adujst:none;text-align:center;}
.current-quanzhong .ui-widget-content .ui-state-default, .slider-weight-box .ui-widget-content .ui-state-default{top:-3px!important;width:12px!important;height:12px!important;background:-webkit-linear-gradient(#d0cfcf,#9d9d9d)!important;background:-moz-linear-gradient(#d0cfcf,#9d9d9d)!important;border-radius:50%!important;}
/*.slider-weight-box .ui-widget-content .ui-state-default:last-of-type{background:-webkit-linear-gradient(#3e9e16,#ca5b02)!important;background:-moz-linear-gradient(#3e9e16,#ca5b02)!important;}*/
.weight-box .ui-widget-content{background:#6d6d6d;border:0;border-radius:2px!impotant;}
@media only screen and (-webkit-min-device-pixel-ratio: 2),
only screen and (-moz-min-device-pixel-ratio: 2),
only screen and (-o-min-device-pixel-ratio: 2/1),
only screen and (min-device-pixel-ratio: 2) {
        .ui-widget-content .ui-state-default{background-image:url({$RESOURCE_URL}slider_button-2x.png);background-size:100%;}
        .slider-number{background-image:url({$RESOURCE_URL}slider_num_bg-2x.png);background-size:100%;}
}
</style>
<script>
$(function ($) {
	var box = $(".weightPicker .weight-box");
	var needHide = true;

	function render() {
		if ( !top.$.globalData ) return;
    	var configWeight = top.$.globalData.get('quanzhong');
    	if (configWeight) {
    		var el = $.tmpl( $('#search_weight_list_tpl').html(), {mydata: configWeight}, {} );
    		box.prepend(el);
    	}
	}
	render();
	
	box
		.hover(function () {
			needHide = false;
		}, $.noop)
		.on("click", ".weight-list li", function () {
			box.hide();
			needHide = true;
			var value = $(this).data('weight').split(',');
			$("#start_weight").val( value[0] );
			$("#end_weight").val( value[1] );
			$("#searchform").submit();
		})
		.on("click", ".cancel", function () { box.hide();needHide = true; })
		.on("click", ".submitBtn", function () {
			box.hide();
			needHide = true;
			$("#searchform").submit();
		});
	
	var weight_search = {code}echo json_encode($_configs['weight_search']);{/code};
		values = [{$_INPUT['start_weight']}, {$_INPUT['end_weight']}],
		num1 = $('#weight_num1'),
		num2 = $('#weight_num2');
		num1.val(values[0]);
		num2.val(values[1]);
	num1.on('blur', function() {
		var max = num2.val();
		var val = parseInt(num1.val());
		if ( !isNaN(val) && val >= 0 && val <= max ) {
		} else {
			val = 0;
		}
		$("#weightSlider").slider('values', [val, max]).trigger('slide');
		refreshValues([val, max]);
	});
	num2.on('blur', function() {
		var min = num1.val();
		var val = parseInt(num2.val());
		if ( !isNaN(val) && val <= 100 && val >= min ) {
		} else {
			val = 100;
		}
		$("#weightSlider").slider('values', [min, val]).trigger('slide');
		refreshValues([min, val]);
	});
	function refreshValues(values) {
		num1.val(values[0]);
		num2.val(values[1]);
		$('#start_weight').val(values[0]);
		$('#end_weight').val(values[1]);
	}
	$("#weightSlider").slider({
		create: function () {
			var start= $(this).find("a:first"), 
			    end= $(this).find("a:last");
		},
		animate: true,
		range: true,
		max: 100,
		min: 0,
		values: values,
		slide: function (e, ui) {
			values = ui.values;
			refreshValues(values);
		}
	});
});
</script>
<div style="width:{code} echo $width . 'px'{/code};" class="colonm down_list weightPicker">
	<span class="input_left"></span>
	<span class="input_right"></span>
	<span class="input_middle"><a><em></em><label class="overflow" id="display_colonm_show">{$weightLabelShow}</label></a></span>
	<div class="weight-box defer-hover-target">
		<!-- 模板在下面 -->
		<div class="define-weight clear">
		  <div class="mb8">
		  	<span>权重范围：</span>
		  	<span style=""><input id="weight_num1" type="text" class="txt">－<input id="weight_num2" type="text" class="txt"></span>
		  	<input type="submit" value="确定" class="btn">	
		  </div>
		  <input type="hidden" name="start_weight" id="start_weight" value="{$_INPUT['start_weight']}" />
		  <input type="hidden" name="end_weight" id="end_weight" value="{$_INPUT['end_weight']}" />
		  <div class="slider-weight-box mt10">
			  <i class=start>0</i>
			  <div id="weightSlider"></div>
			  <i class="end">100</i><br/>
		  </div>
		</div>
	</div>
</div>
<script type="tpl" id="search_weight_list_tpl">
<div class="weight-list">
<ul class="dotline">
	{{each mydata}}
	<li data-weight="${_value.begin_w},${_value.end_w}">
		<span class="weight-number" style="background: transparent;"><span>≥${_value.begin_w}</span></span><a class="weight-describe">${_value.title}</a>
	</li>
	{{/each}}
</ul>
</div>
</script>