{template:head}
{css:ad_style}
{css:column_node}
<script type="text/javascript">
function mod_hidden_show(id)
{
	if(id == 'basic_info')
	{
		$('#basic_info').css('display','');
		$('#pub_set').css('display','none');
		$('#recommend_block').css('display','none');
		$('#display_set').css('display','none');
	}
	else if(id == 'pub_set')
	{
		$('#basic_info').css('display','none');
		$('#pub_set').css('display','');
		$('#recommend_block').css('display','none');
		$('#display_set').css('display','none');
	}
	else if(id == 'recommend_block')
	{
		$('#basic_info').css('display','none');
		$('#pub_set').css('display','none');
		$('#recommend_block').css('display','');
		$('#display_set').css('display','none');
	}
	else if(id == 'display_set')
	{
		$('#basic_info').css('display','none');
		$('#pub_set').css('display','none');
		$('#recommend_block').css('display','none');
		$('#display_set').css('display','');
	}
}

</script>
{code}

	$column_form[0] = $formdata['column'];
{/code}
<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']} style="display:none"{/if}>
	<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6" style="font-weight:bold;">添加栏目</a>
</div>
<div id="channel_form" style="margin-left:60%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post"  class="ad_form h_l" enctype ="multipart/form-data">
				<h2>{if $column_form[0]['id']}更新栏目{else}添加栏目{/if}</h2>
				<a href="javascript:void(0)" onclick="mod_hidden_show('basic_info')">基本设置 </a>
				<a href="javascript:void(0)" onclick="mod_hidden_show('pub_set')">辅助设置   </a>
				<a href="javascript:void(0)" onclick="mod_hidden_show('recommend_block')">发布设置 </a>
				<a href="javascript:void(0)" onclick="mod_hidden_show('display_set')">栏目示意图</a>
				<div id="basic_info" >
					<ul class="form_ul">
					<li class="i">
							<div class="form_ul_div">
								<span class="column_title">支持的模块：</span>
								{if $formdata['module']}
								{foreach $formdata['module'] as $k=>$v}
								<input type=checkbox name="support_module[]" value="{$v['id']}" {code}if(in_array($v['id'],explode(',',$column_form[0]['support_module']))) echo "checked";{/code} />{$v['name']}
								{/foreach}
								{else}
								无
								{/if}
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
					<li class="i">
							<div class="form_ul_div">
								<span class="column_title">支持的内容类型：</span>
								{if $formdata['content_type']}
								{foreach $formdata['content_type'] as $k=>$v}
								<input type=checkbox name="support_content_type[]" value="{$v['id']}" {code}if(in_array($v['id'],explode(',',$column_form[0]['support_content_type']))) echo "checked";{/code} />{$v['content_type']}
								{/foreach}
								{else}
								无
								{/if}
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">支持的客户端：</span>
								{foreach $_configs['client'] as $k=>$v}
								<input type=checkbox name="support_client[]" value="{$k}" {code}if(in_array($k,explode(',',$column_form[0]['support_client']))) echo "checked";{/code} />{$v}
								{/foreach}
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">栏目名称：</span>
								<input type="text" value="{$column_form[0]['name']}" name='column_name' style="width:300px;">
								<span class="site_fill_tip">
								名称/简称最多30/4个汉字
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">栏目简称：</span>
								<input type="text" value="{$column_form[0]['shortname']}" name='shortname' style="width:100px;">

<!--								栏目排序：
								<input type="text" value="{$column_form[0]['column_sort']}" name='column_sort' style="width:60px;">
-->								
								<span class="site_fill_tip">
									一般用于导航链接
								</span>
							</div>
						</li>
						{if $column_form[0]['site_weburl']}
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">栏目子域名：</span>
								<input type="text" value="{$column_form[0]['childdomain']}" name='childdomain' style="width:100px;">
								{code}echo ltrim($column_form[0]['site_weburl'],'http://www');{/code}
								<input type="hidden" name="childdomain_suffix" value="{code}echo ltrim($column_form[0]['site_weburl'],'http://www');{/code}" />
								<span class="site_fill_tip">
									如未绑定请留空
								</span>
							</div>
						</li>
						{/if}
						<!--
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">主内容模型：</span>
								<select name='primarymode' value="{$site_form[0]['primarymode']}">
									{foreach $_configs['site_suffix'] as $k=>$v}
									<option value="{$k}" {code}if($site_form[0]['primarymode']==$k) echo "selected";{/code}>
										{$v}
									</option>
									{/foreach}
								</select>
								<span class="site_fill_tip">
									如未绑定请留空
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">辅助模型：</span>
								<input type=checkbox name="assistantmode[]">资讯模型
								<span class="site_fill_tip">
									辅助模型建议不要超过3个
								</span>
							</div>
						</li>
						-->
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">栏目链接地址：</span>
								<input type="text" value="{$column_form[0]['linkurl']}" name='linkurl' style="width:450px;">
								<span class="site_fill_tip">
									注意：若该栏目是外部链接，将不会显示在左栏栏目树中。
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">页面关键字：</span>
								<input type="text" value="{$column_form[0]['keywords']}" name='keywords' style="width:450px;">
								<span class="site_fill_tip">
									用,号分隔每个关键字
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">栏目简要：</span>
								<textarea  name='content' style="width:450px;">
								{$column_form[0]['content']}
								</textarea>
								<span class="site_fill_tip">
									最多128个字页面描述
								</span>
							</div>
						</li>
					
					
					</ul>
					</div>
					<div id="pub_set" style="display:none">
						<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">栏目首页文件名称：</span>
								<input type="text" value="{$column_form[0]['colindex']}" name='colindex' style="width:100px;">.php
								<span class="site_fill_tip">
									不需要填写文件扩展名，少于16个字母
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">本栏目生成方式：</span>
								<select name='maketype' value="{$column_form[0]['maketype']}">
									{foreach $_configs['column_produce_format'] as $k=>$v}
									<option value="{$k}" {code}if($column_form[0]['maketype']==$k) echo "selected";{/code}>
										{$v}
									</option>
									{/foreach}
								</select>
								<select name='suffix' value="{$column_form[0]['suffix']}">
									{foreach $_configs['column_suffix'] as $k=>$v}
									<option value="{$k}" {code}if($column_form[0]['suffix']==$k) echo "selected";{/code}>
										{$v}
									</option>
									{/foreach}
								</select>
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">本栏目下内容生成方式：</span>
								<select name='col_con_maketype' >
									{foreach $_configs['column_produce_format'] as $k=>$v}
									<option value="{$k}" {code}if($column_form[0]['col_con_maketype']==$k) echo "selected";{/code}>
										{$v}
									</option>
									{/foreach}
								</select>
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">栏目主目录：</span>
								<input type="text" value="{$column_form[0]['column_dir']}" name='column_dir' style="width:100px;">
								<span class="site_fill_tip">
										不填写为默认自动，少于60个字母
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">动态生成时该栏目下内容页的文件名：</span>
								<input type="text" value="{$column_form[0]['contentfilename']}" name='contentfilename' style="width:100px;">
								<span class="site_fill_tip">
									不填写为默认自动，少于20个字母,无需扩展名
								</span>
							</div>
						</li>
<!--						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">栏目下内容缩略图宽：</span>
								<input type="text" value="{$column_form[0]['thumbwidth']}" name='thumbwidth' style="width:30px;">
								<span class="site_fill_tip">
									不填写则继承上级栏目，数字
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">栏目下内容缩略图高：</span>
								<input type="text" value="{$column_form[0]['thumbheight']}" name='thumbheight' style="width:30px;">
								<span class="site_fill_tip">
										不填写则继承上级栏目，数字
								</span>
							</div>
						</li>

						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">选择水印图片：</span>
								<select name='watermark' value="{$site_form[0]['watermark']}">
									{foreach $_configs['site_suffix'] as $k=>$v}
									<option value="{$k}" {code}if($site_form[0]['watermark']==$k) echo "selected";{/code}>
										{$v}
									</option>
									{/foreach}
								</select>
								<span class="site_fill_tip">
									默认继承栏目设置或站点设置
								</span>
							</div>
						</li>
-->
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">栏目下的内容目录格式：</span>
								<select name='folderformat' value="{$site_form[0]['folderformat']}">
									{foreach $_configs['column_folderformat'] as $k=>$v}
									<option value="{$k}" {code}if($column_form[0]['folderformat']==$k) echo "selected";{/code}>
										{$v}
									</option>
									{/foreach}
								</select>
								<span class="site_fill_tip">
										当内容多时，对内容进行分目录管理
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">栏目下的内容命名格式：</span>
								<select name='fileformat' value="{$column_form[0]['fileformat']}">
									{foreach $_configs['column_fileformat'] as $k=>$v}
									<option value="{$k}" {code}if($column_form[0]['fileformat']==$k) echo "selected";{/code}>
										{$v}
									</option>
									{/foreach}
								</select><br>
								<span class="site_fill_tip">
									选择内容命名格式
								</span>
							</div>
						</li>
						</ul>
					</div>
					<div id="recommend_block" style="display:none">
						<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">浏览器栏显示：</span>
								<input type="text" value="{$column_form[0]['titleformat']}" name='titleformat' style="width:300px;">
								<span class="site_fill_tip">
									显示在浏览器顶部的标题格式，可以使用提供的变量
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">是否需要内容统计：</span>
								{foreach $_configs['is_not'] as $k=>$v}
								<input type=radio name="needartstat" value="{$k}"  {code}if($column_form[0]['needartstat']==$k) echo "checked";{/code}/>{$v}
								{/foreach}
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">是否需要栏目统计：</span>
								{foreach $_configs['is_not'] as $k=>$v}
								<input type=radio name="needcolstat" value="{$k}" {code}if($column_form[0]['needcolstat']==$k) echo "checked";{/code} />{$v}
								{/foreach}
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">是否接收投稿：</span>
								{foreach $_configs['is_not'] as $k=>$v}
								<input type=radio name="needartadv" value="{$k}" {code}if($column_form[0]['needartadv']==$k) echo "checked";{/code} />{$v}
								{/foreach}
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">引用文章生成方式：</span>
								{foreach $_configs['article_maketype'] as $k=>$v}
								<input type=radio name="article_maketype" value="{$k}" {code}if($column_form[0]['article_maketype']==$k) echo "checked";{/code} />{$v}
								{/foreach}
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						
						
<!--						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">请选择文章的默认状态：</span>
								<select name='fileformat' value="{$site_form[0]['fileformat']}">
									{foreach $_configs['site_suffix'] as $k=>$v}
									<option value="{$k}" {code}if($site_form[0]['fileformat']==$k) echo "selected";{/code}>
										{$v}
									</option>
									{/foreach}
								</select>
								<span class="site_fill_tip">
									如不填写，则继承上级栏目
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">为该栏目选择一个流程：</span>
								<select name='fileformat' value="{$site_form[0]['fileformat']}">
									{foreach $_configs['site_suffix'] as $k=>$v}
									<option value="{$k}" {code}if($site_form[0]['fileformat']==$k) echo "selected";{/code}>
										{$v}
									</option>
									{/foreach}
								</select>
								<span class="site_fill_tip">
									选择内容命名格式
								</span>
							</div>
						</li>

						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">是否生成RSS：</span>
								<input type=radio name="needrss" />是
								<input type=radio name="needrss" />否
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">生成RSS条数：</span>
								<input type="text" value="{$column_form[0]['rssnum']}" name='rssnum' style="width:70px;">
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">是否加入首页生成：</span>
								<input type=radio name="toindex" />是
								<input type=radio name="toindex" />否
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">加入首页条数：</span>
								<input type="text" value="{$column_form[0]['toindexnum']}" name='toindexnum' style="width:70px;">
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
-->
						</ul>
					</div>
					
					<div id="display_set" style="display:none">
						<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">栏目示意图(默认状态)：</span>
								
								<img src="{if $column_form[0]['icon_default']}{$column_form[0]['icon_default']}{else}{$image_resource}hill.png{/if}" width="90" height="90"   id="img_{$column_form[0]['id']}"  />
								<input type="file" name="default">
								<span class="site_fill_tip">
									如未绑定请留空
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">栏目示意图(激活状态)：</span>
								
								<img src="{if $column_form[0]['activation']}{$column_form[0]['activation']}{else}{$image_resource}hill.png{/if}"   width="90" height="90"   id="img_{$column_form[0]['id']}"  />
								<input type="file" name="activation">
								<span class="site_fill_tip">
									如未绑定请留空
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">栏目示意图(未激活状态)：</span>
								
								<img src="{if $column_form[0]['no_activation']}{$column_form[0]['no_activation']}{else}{$image_resource}hill.png{/if}"   width="90" height="90"  id="img_{$column_form[0]['id']}"  />
								<input type="file" name="no_activation">
								<span class="site_fill_tip">
									如未绑定请留空
								</span>
							</div>
						</li>
						</ul>
					</div>
					
				<input type="hidden" name="a" value="{if $column_form[0]['id']}update{else}create{/if}" />
				<input type="hidden" name="site_id" value="{$formdata['site_id']}" />
				<input type="hidden" name="column_id" value="{$formdata['column_id']}" />
				<input type="hidden" name="column_fid" value="{$formdata['column_fid']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="{if $column_form[0]['id']}更新{else}添加{/if}" class="button_6_14"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}