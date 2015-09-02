<?php 
/* $Id: stream_form.php 2361 2011-10-28 09:56:50Z lijiaying $ */
?>

{template:head}
{css:ad_style}
{js:area}

	<div class="ad_middle">
	<form name="editform" action="" enctype="multipart/form-data" method="post" class="ad_form h_l" onsubmit="return hg_form_check();">
		<h2>导入船次</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">选择文件：</span>
						<div class="input " style="width:210px; float:left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="file" name="excel" id="excel" style="width:200px; height:18px; line-height:20px; font-size:12px; padding-left:5px; float:left; border:none;" /></span>
						</div>
						<span class="error" id="title_tips" >*仅支持EXCEL格式</span>
					</div>
				</div>
			</li>
			
		</ul>
	<input type="hidden" name="a" value="excel_update" />
	<input type="hidden" name="is_del" id="is_del" value="0" />
	<input type="hidden" name="{$primary_key}" value="{$formdata['id']}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</br>
	<input type="submit" name="sub" value="导入" id="sub" class="button_6_14" />
	</form>
	</div>
	<div class="right_version" style="width:290px;">
		<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
	</div>
{template:foot}
