{template:head}
{css:ad_style}
{css:column_node}
{js:iColorPicker}

<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
				<h2>访谈权限管理</h2>
					<table  border="0" cellpadding="0" cellspacing="0" width="100%"  id="channel_table" class="form_table">
						<tr>
							<td colspan="6">
							1.可发言和可回复的下拉选择表示该角色所发表的评论发表出来后的第一状态<br/>
							2.可修改表示该角色是否可以修改所有评论内容<br/>
							3.背景颜色表示该角色所发表的评论的文字的背景显示颜色，文字颜色就是评论的文字本身颜色<br/>
							</td>
						</tr>
						
						<tr id="item_th" class="h" align="left" valign="middle">
							<th class="text_indent"></th>
							<th class="text_indent">发言状态</th>
							<th class="text_indent">回复状态</th>
							<th class="text_indent">可修改</th>
							<th class="text_indent"></th>
							<th class="text_indent"></th>
						</tr>
						
				  			
				  		<tr>
				  		{foreach $_configs['roles'] as $k => $v}	  
				  			<td class="text_indent">{$v}</td>
				  			<td class="text_indent">
				  				<select  name="speak_{$k}">
				  					{foreach $_configs['record_states'] as $key=>$val}
				  			
				  					<option value="{$key}" {if $key==$formdata[$k][0]}selected="selected"{/if}>{$val}</option>
				  			
				  					{/foreach}
				  				</select>
				  			</td>
				  			<td class="text_indent">
				  				<select name="revert_{$k}">
				  					{foreach $_configs['record_states'] as $key=>$val}
				  					
				  					<option value="{$key}" {if $key==$formdata[$k][1]}selected="selected"{/if}>{$val}</option>
	
				  					{/foreach}
				  				</select>
				  			</td>
				  			<td class="text_indent">
				  				<input type="checkbox"  name="edit_{$k}" {if $formdata[$k][2]==1}checked="checked"{/if} value="1"/>
				  			</td>
				  			<td class="text_indent">背景颜色：<input type="text" class="iColorPicker"  id="bg_color_{$k}" name="bg_color_{$k}" value="{$formdata[$k][3]}"  style="background:{$formdata[$k][3]}"  size="6" maxlength="7"  ishidden=false/></td>
				  			<td class="text_indent">文字颜色：<input type="text" class="iColorPicker"  id="font_color_{$k}" name="font_color_{$k}" value="{$formdata[$k][4]}"  style="background:{$formdata[$k][4]}"  size="6" maxlength="7"  ishidden=false/></td>
						</tr>
						{/foreach}
						
					</table>					
					<input type="hidden" name="a" value="update_authority" />
					<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
					<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
					<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
					<br />
					<input type="submit" name="sub" value="确认修改" class="button_6_14"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}