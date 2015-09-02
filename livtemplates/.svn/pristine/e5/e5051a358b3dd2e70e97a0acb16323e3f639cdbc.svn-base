{template:head}
{css:ad_style}
{css:column_node}
<script type="text/javascript">
function delete_set(id)
{
	window.location.href = "?mid={$_INPUT['mid']}&a=delete&infrm={$_INPUT['infrm']}&id="+id;
}
</script>
{code}
if($formdata)
{
	$fid = empty($formdata['id'])?0:$formdata['id'];
}
else
{
	$fid = empty($set_form[0]['id'])?0:$set_form[0]['id'];
}
$set_form[0] = $formdata;
{/code}
<div id="hg_page_menu" class="head_op_program">
	<a href="?mid={$_INPUT['mid']}&a=set_form&infrm={$_INPUT['infrm']}&id={$fid}" class="button_6" style="font-weight:bold;">添加配置</a>
</div>
<div id="channel_form" style="margin-left:60%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post"  class="ad_form h_l">
				<h2>{if $set_form[0]['id']}更新配置{else}新增配置{/if}</h2>
				<div id="basic_info" >
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">配置id：</span>
								<input type="text" value="{code}if(!$formdata || $formdata['name']) echo $set_form[0]['id'];{/code}" readonly name='id' style="width:80px;">
								<span class="site_fill_tip">
								内容关联配置id，自动生成。
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">上级配置：</span>
								<select name="set_fid">
									<option value='0' {if $set_form[0]['fid']==0}selected{/if}>无</option>
									{foreach $set_form[0]['allset'] as $k=>$v}
									<option value="{$v['id']}" {if $set_form[0]['fid']==$v['id']}selected{/if}>{$v['name']}</option>
									{/foreach}
								</select>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">配置名称：</span>
								<input type="text" value="{$set_form[0]['name']}" name='name' style="width:200px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">应用标识：</span>
								<input type="text" value="{$set_form[0]['bundle_id']}" name='bundle_id' style="width:200px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div"><span class="site_title">模块标识：</span>
							<input type="text" value="{$set_form[0]['module_id']}" name='module_id' style="width:200px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">主内容标识:</span>
								<input type="text" value="{$set_form[0]['struct_id']}" name='struct_id' style="width:200px;">
								
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">子内容标识:</span>
								<input type="text" value="{$set_form[0]['struct_ast_id']}" name='struct_ast_id' style="width:200px;">
								<span class="site_fill_tip">
									如果没有子内容，可以不填写
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">每次取内容数:</span>
								<input type="text" value="{$set_form[0]['num']}" name='num' style="width:200px;">
								<span class="site_fill_tip">
									证书
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">host:</span>
								<input type="text" value="{$set_form[0]['host']}" name='host' style="width:200px;">
								<span class="site_fill_tip">
									如：127.0.0.1 或 localhost 等域名ip
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">文件目录:</span>
								<input type="text" value="{$set_form[0]['path']}" name='path' style="width:200px;">
								<span class="site_fill_tip">
								文件的目录
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">文件名:</span>
								<input type="text" value="{$set_form[0]['filename']}" name='filename' style="width:200px;">
								<span class="site_fill_tip">
								如：test.php
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">获取内容的方法名:</span>
								<input type="text" value="{$set_form[0]['action_get_content']}" name='action_get_content' style="width:200px;">
								<span class="site_fill_tip">
								如：get_content
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">发布内容后返回插入内容id的方法名:</span>
								<input type="text" value="{$set_form[0]['action_insert_contentid']}" name='action_insert_contentid' style="width:200px;">
								<span class="site_fill_tip">
								如：insert_content_id
								</span>
							</div>
						</li>
						
					</ul>
					</div>
				<input type="hidden" name="a" value="{if $set_form[0]['id']}update{else}create{/if}" />
				<input type="hidden" name="id" value="{$fid}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="{if $set_form[0]['id']}更新{else}添加{/if}" class="button_6_14"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}