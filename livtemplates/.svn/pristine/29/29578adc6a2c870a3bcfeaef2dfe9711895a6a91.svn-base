{template:head}
{css:hg_sort_box}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{css:common/common_form}
{css:ad_style}
{if is_array($formdata) && $a == 'update'}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
					<h2>{$optext}停车场类型</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">类型名称：</span>
								<input type="text"  required="true" value="{$name}" name='name' style="width:257px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">地图标识：</span>
								<input type="file" name='map_marker' />
							</div>
							<div class="form_ul_div" style="margin-left:75px;">
								<img src="{$map_marker}" />
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">需要更新: </span><input type="checkbox" name="need_update" value="1" {if $formdata['need_update']}checked="checked"{/if}/><font class="important">可选，选中会计划任务更新分类下数据信息</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">备注描述：</span>
								<textarea name='description'>{$description}</textarea>
							</div>
						</li>
					</ul>
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}