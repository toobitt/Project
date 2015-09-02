{template:head}
{code}
//$list = $formdata;
{/code}
{css:ad_style}
{css:column_node}

<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" class="ad_form h_l">
				<h2>编辑专题栏目信息</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span class="title">栏目名称：</span>
								<input type="text" value="{$formdata['column_name']}" name='column_name' style="width:200px;"/>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">栏目外链：</span>
								<input type="text" value="{$formdata['outlink']}" name='outlink' style="width:200px;"/>
							</div>
						</li>
						<li class="i ho pf">
							<div class="form_ul_div clear">
								<span class="title">生成方式:</span>
								<select name='maketype'  value="{$formdata['maketype']}">
									{foreach $_configs['maketype'] as $k=>$v}
									<option value="{$k}" {code}if($formdata['maketype']==$k) echo "selected";{/code}>
										{$v}
									</option>
									{/foreach}
								</select>
							</div>
						</li>
						<li class="i para">
							<div class="form_ul_div">
								<span class="title">首页：</span>
								<select name='column_file' >
									{foreach $_configs['column_file'] as $k=>$v}
									<option value="{$k}" {code}if($formdata['column_file']==$k) echo "selected";{/code}>
										{$v}
									</option>
									{/foreach}
								</select>
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i para">
							<div class="form_ul_div clear">
								<span  class="title">首页名称:</span>
								<input type="text" value="{$formdata['colindex']}" name='colindex' style="width:440px;">
							</div>
						</li>
						<!--<li class="i para">
							<div class="form_ul_div clear">
								<span  class="title">目录:</span>
								<input type="text" value="{$formdata['column_dir']}" name='column_dir' style="width:440px;">
							</div>
						</li>
						<li class="i para nd">
							<div class="form_ul_div clear">
								<span  class="title">二级域名:</span>
								<input type="text" value="{$formdata['column_domain']}" name='column_domain' style="width:440px;">
							</div>
						</li>-->
					</ul>
					</ul>
				<input type="hidden" name="a" value="update_special_column" />
				<input type="hidden" name="id" value="{$formdata['id']}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="更新" class="button_6_14"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	</div>
