{template:head}
{css:hg_sort_box}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{css:common/common_form}
{code}
$list = $formdata;
$price_info = $list['price_info'];
unset($list['price_info']);

if($id)
{
	$optext="更新";
	$a="update";
}
else
{
	$optext="添加";
	$a="create";
	$list['show_id'] = $_INPUT['show_id'];
}

{/code}
{css:ad_style}
<style>
.form_ul_div.l input{float:left}
.form_ul .n-h{float:none}
.ad_middle table textarea{min-width:0;min-height: 0;}
.option_del_box{display:inline-block;width:16px;height:16px;cursor:pointer;float:right;position: relative;right: 50px;top: 4px;}
.option_del {
display: none;
width: 16px;
height: 16px;
cursor: pointer;
float: right;
background: url('{$RESOURCE_URL}close_plan.png') 0 0 no-repeat;
}
</style>
<script type="text/javascript">
jQuery(function ($) {
	/*时间选择*/
	$( '#send_time' ).each(function () {
		var oldDate;
		$(this).datetimepicker({
			showSecond: false,
			timeFormat: 'hh:mm',
			beforeShow: function ( input, ui ) {
				oldDate = input.value;
			},
		});
	});
});

function hg_addArgumentDom()
{
	var div = "<div class='form_ul_div clear'><span class='title'>票价: </span><input type='text' name='price[]' style='width:90px;' class='title'>&nbsp;&nbsp;票说明: <input type='text' name='price_notes[]' style='width:90px;' class='title'>&nbsp;&nbsp;总票数: <input type='text' name='goods_total[]' style='width:90px;' class='title'>&nbsp;&nbsp;剩余票数: <input type='text' name='goods_total_left[]' style='width:90px;' class='title'>&nbsp;&nbsp;<span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span><input type='hidden' name='price_id[]' value='add'></div>";
	$('#extend').append(div);
	hg_resize_nodeFrame();
}


function hg_optionTitleDel(obj)
{
	if($(obj).data("save"))
	{
		if(confirm('确定删除该票吗？'))
		{
			$(obj).closest(".form_ul_div").remove();
		}
	}
	else
	{
		$(obj).closest(".form_ul_div").remove();
	}
	hg_resize_nodeFrame();
}
</script>
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
					<h2>{$optext}场次</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">场次时间：</span>
								<input type="text" readonly="readonly" style="width:233px;"  _time="true" name='show_time' value="{$list['show_time']}" id="send_time" />
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">场次描述：</span>
								<textarea rows="3" cols="80" name='brief'>{$list['brief']}</textarea>
							</div>
						</li>
						
						<li class="i">
		
							{if($price_info)}
							
							{foreach $price_info as $k=>$v}
							<div class='form_ul_div clear'>
								<span class='title'>票价: </span>
								<input type='text' name='price[]' value='{$v["price"]}' style='width:90px;' class='title'>&nbsp;
								票说明: <input type='text' name='price_notes[]' value='{$v["price_notes"]}' style='width:90px;' class='title'>&nbsp;
								总票数: <input type='text' name='goods_total[]' value='{$v["goods_total"]}' style='width:90px;' class='title'>&nbsp;
								剩余票数: <input type='text' name='goods_total_left[]' value='{$v["goods_total_left"]}' style='width:90px;' class='title'>&nbsp;
								<!-- 
								<span>票种类: </span>
								<select name='price_type[]'>
									<option {if $list['price_type'][$k] == 1}selected='selected'{/if} value='1'>普通</option>
									<option {if $list['price_type'][$k] == 2}selected='selected'{/if} value ='2'>VIP</option>
									<option {if $list['price_type'][$k] == 3}selected='selected'{/if} value ='3'>贵宾</option>
								</select>
								 -->
								<span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' data-save="1" onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span>
								<input type="hidden" name="price_id[]" value="{$v['id']}">
							</div>
							{/foreach}
							{/if}
							<div id="extend"></div>
							<div class="form_ul_div clear">
								<span type="text" style="cursor:pointer;padding: 5px 20px;margin-left: 75px;background-color: #5B5B5B;color: white;border-radius: 2px;" onclick="hg_addArgumentDom();">添加票</span>
							</div>
		
	</li>
				</ul>
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="show_id" value="{$list['show_id']}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}&show_id={$list['show_id']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}&show_id={$list['show_id']}">返回前一页</a></h2></div>
	</div>
{template:foot}