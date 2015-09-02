			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
				<h2>自定义常量</h2>
					<ul class="form_ul">
                       {if $settings['gdefine']}
                       		{foreach  $settings['gdefine'] as $k=>$v}
                       	<li class="i">
							<div class="form_ul_div">
								<span  class="title">{$v['var_name']}</span>
								<input type="text" value="{$v['value']}" name='city_zh_name' style="width: 400px">
								<font class="important" style="color:red">*</font>
							</div>
						</li>
                       		{/foreach}
                       {/if}           
					</ul>
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
			</form>
