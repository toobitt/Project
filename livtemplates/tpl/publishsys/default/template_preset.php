{template:head}
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
				<h2>模板单元预设</h2>
				<ul class="form_ul">
					{code}
						$cell_mode_attr = array(
							'class' => 'transcoding down_list cell_page_type',
							'show' => 'page_type_show',
							'width' => 300,	
							'state' => 0,
						);								
						$data_source_attr = array(
							'class' => 'transcoding down_list',
							'show' => 'content_type_show',
							'width' => 300,	
							'state' => 0,
						);	
																
						//样式
						$cellmode[0] = '选择样式';
						foreach($cell_mode[0] as $k => $v)
						{
							$cellmode[$v['id']] = $v['name'];
						}
						//数据源
						$datasource[0] = '选择数据源';
						foreach($data_source[0] as $k => $v)
						{
							$datasource[$v['id']] = $v['name'];
						}
					{/code}												
						<li class="i">
							<div class="form_ul_div clear">
									<span class="title">样式：</span>
									{template:form/search_source,cell_mode,$list['cell_mode'],$cellmode,$cell_mode_attr}
							</div>
						</li>	
						<li class="i">
							<div class="form_ul_div clear">
									<span class="title">数据源：</span>
									{template:form/search_source,data_source,$list['data_source'],$datasource,$data_source_attr}
							</div>
						</li>	
					</ul>	
				<input type="hidden" name="a" value="preset_update" />
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