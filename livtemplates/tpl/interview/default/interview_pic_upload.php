{template:head}
{css:ad_style}
{css:column_node}
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
				<h2>上传图片</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">图片上传：</span>
								<input type="file" name="file"/>
								
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">显示位置：</span>
								<input type="radio" name="show_pos" value="0"  {if  $formdata['show_pos']==0}checked="checked"{/if}/> 头部
								<input type="radio" name="show_pos" value="2"  {if  $formdata['show_pos']==2}checked="checked"{/if}/> 其他
								
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span><font color='red'>*</font>为必填选项</span>
							</div>
						</li>
					</ul>
				<input type="hidden" name="a" value="upload" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="图片上传" class="button_6_14"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}