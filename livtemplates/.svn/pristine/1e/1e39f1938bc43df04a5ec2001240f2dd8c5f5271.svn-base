<?php 
?>
{template:head}
{css:teditor}
{js:teditor}
	<div class="wrap ">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
				<h2>编辑模板内容</h2>
					<ul class="form_ul">	
						<li class="i">					
							<div class="form_ul_div clear">
								<span  class="title">模板内容：</span>
								{$formdata['html']}
							</div>			
						</li>								
						{code}
							$types_arr = array(
								'class' => 'transcoding down_list',
								'show' => 'types_show',
								'width' => 120,	
								'state' => 0,
							);
							$types_default = $type ? $type : 1;	
						{/code}
					</ul>		
							
				<input type="hidden" name="a" value="edit_c" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<input type="hidden" name="html" value="true" />
				<br />
				<input type="submit" name="sub" value="确定" class="button_6_14"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	</div>
{template:foot}