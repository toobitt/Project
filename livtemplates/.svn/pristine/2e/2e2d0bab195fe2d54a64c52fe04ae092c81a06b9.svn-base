{template:head}
{css:hg_sort_box}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{css:common/common_form}
{code}
$list = $formdata;
if($id)
{
	$optext="更新";
	$a="update";
}
else
{
	$optext="添加";
	$a="create";
}

{/code}
{css:ad_style}
{js:public_bicycle/station}

<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
					<h2>{$optext}场馆</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">场馆名称：</span>
								<input type="text" value="{$list['venue_name']}" name='name' style="width:257px;">
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">详细描述：</span>
								<textarea rows="3" cols="80" name='content'>{$list['content']}</textarea>
							</div>
						</li>
						
						<li class="i" id='map'>
							<div class="form_ul_div clear">
								<span class="title">坐标地址：</span>
								<input type="text" value="{$list['venue_address']}" name='address' style="width:400px;" id="detailed_address"/>
							</div>
							<div class="form_ul_div clear">
								<span class="title"></span>
								{code}
									$hg_bmap = array(
										'height' => 480,
										'width'  => 600,
										'longitude' => isset($list['baidu_longitude']) ? $list['baidu_longitude'] : '0', 
										'latitude'  => isset($list['baidu_latitude']) ? $list['baidu_latitude'] : '0',
										'zoomsize'  => 13,
										'areaname'  => $city_name[0],
										'is_drag'   => 1,
									);
								{/code}
								{template:map/baidu_map,baidu_longitude,baidu_latitude,$hg_bmap}
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