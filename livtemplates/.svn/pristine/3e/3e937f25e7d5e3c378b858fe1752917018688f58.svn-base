{template:head}
{css:ad_style}
{js:column_node}
{css:column_node}
<div class="ad_middle" style="position:relative;">
<div style="position:absolute;width:200px;height:50px;border:1px solid #5B5B5B;left:270px;text-align:center;font-size:16px;color:#FAB742;border-radius:5px;background:#FDFDE3;line-height:50px;display:none;" id="tip_show">添加时移成功</div>
<form name="editform" id="editform" action="run.php?mid={$_INPUT['mid']}" method="post" class="ad_form h_l" style="min-height:1200px;" onsubmit="return hg_ajax_submit('editform');">
<h2>{$op_text}餐馆</h2>
<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div">
			<div class="col_choose clear">
				<div style="height:26px;padding-top:10px;padding-bottom:10px;">
					<span style="float:left;margin-left:5px;margin-top:4px;">餐馆名称：</span>
					<input type="text" name="name" style="width:200px;height:26px;float:left;margin-left:13px;" class="info-title info-input-left t_c_b" value="{$formdata['name']}" />
				</div>
			</div>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<div class="col_choose clear">
				<div style="height:26px;padding-top:10px;padding-bottom:10px;">
					<span style="float:left;margin-left:5px;margin-top:4px;">餐馆电话：</span>
					<input type="text" name="tel" style="width:200px;height:26px;float:left;margin-left:13px;" class="info-title info-input-left t_c_b" value="{$formdata['tel']}" />
				</div>
			</div>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<div class="col_choose clear">
				<div style="height:26px;padding-top:10px;padding-bottom:10px;">
					<span style="float:left;margin-left:5px;margin-top:4px;">餐馆联系人：</span>
					<input type="text" name="linkman" style="width:200px;height:26px;float:left;" class="info-title info-input-left t_c_b" value="{$formdata['linkman']}"  />
				</div>
			</div>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<div class="col_choose clear">
				<div style="height:26px;padding-top:10px;padding-bottom:10px;">
					<span style="float:left;margin-left:5px;margin-top:4px;">餐馆地址：</span>
					<input type="text" name="address" style="width:622px;height:26px;float:left;margin-left:13px;" class="info-title info-input-left t_c_b" value="{$formdata['address']}" />
				</div>
			</div>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<div class="col_choose clear">
				<div style="height:26px;padding-top:10px;padding-bottom:10px;">
					<span style="float:left;margin-left:5px;margin-top:4px;">餐馆logo：</span>
					<input type="file" name="upload_logo" style="margin-left:9px;float:left;"/>
					<img src="{$formdata['logo']}" width="100" height="100" style="float:left;margin-left:262px;"/>
				</div>
			</div>
		</div>
	</li>
</ul>
<input type="hidden" name="a" value="create" />
<input type="hidden" name="channel_id" value="{$formdata['channel_id']}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
</br>
<input type="submit" value="确定"  class="button_6_14"  style="margin-left:0px;" />
</form>
</div>
<div class="right_version" style="width:290px;">
	<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
</div>
{template:foot}