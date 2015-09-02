{template:head}
{code}
$list = $formdata[0];
$client = $formdata[client];
$site = $formdata[site];
$sorts = $sort_name[0];
$list['a'] = "http://10.0.1.246/livsns/api/publishsys/template__/1.jpg";
$css_attr['style'] = 'style="width:100px"';
{/code}
<script type="text/javascript">
function isvalidatefile(obj){
    var style = $("#file_data").val().substring($("#file_data").val().lastIndexOf(".")+1);
	if(style=="jpeg"||style=="jpg"){
		document.getElementById("pic_data").style.display="block";
	}
	else
	{	
		document.getElementById("pic_data").style.display="none";
	}	
}
</script>
{css:ad_style}
{css:column_node}

<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
			{if $_INPUT['id']}
				<h2>编辑模信息</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">模板名：</span>
								<input type="text" value="{$list['title']}" name='title' style="width:440px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">所属终端: </span>
								{template:form/select,source,$list['source'],$client,$css_attr}
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">所属站点: </span>
								{template:form/select,site_id,$list['site_id'],$site,$css_attr}
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">模板类型: </span>
								{template:form/select,type,$list['type'],$_configs['template_types'],$css_attr}
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">模板分类: </span>
								{template:form/select,sort_id,$list['sort_id'],$sorts,$css_attr}
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">模板文件：</span>
								<input type="file" name="file_data" id="file_data"  onchange="isvalidatefile(this.id)">        
								<span id="pic_data" style="display:none">
								{template:form/select,pic,0,$_configs['pic'],$css_attr} 
								</span>        
							</div>
						</li>
						{if $list['material']}
						<li class="i">
							<div class="form_ul_div clear">
							<div style="float:left">
								<span class="title">预览：</span>
								{$list['material']}
							</div>
							<input type="hidden" value="{$formdata['_material']}" name="material" id="material_url">
							</div>
						</li>
						{/if}
					</ul>
					{else}
					<h2>新增模板信息</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">模板名：</span>
								<input type="text" value="{$list['title']}" name='title' style="width:440px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">所属终端: </span>
								{template:form/select,source,$list['source'],$clients[0],$css_attr}
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">所属站点: </span>
								{template:form/select,site_id,$list['site_id'],$sites[0],$css_attr}
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">模板类型: </span>
								{template:form/select,type,$list['type'],$_configs['template_types'],$css_attr}
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">模板分类: </span>
								{template:form/select,sort_id,$list['sort_id'],$sorts,$css_attr}
							</div>
						</li>
							<li class="i">
							<div class="form_ul_div">
								<span class="title">模板文件：</span>
								<input type="file" name="file_data" id="file_data"  onchange="isvalidatefile(this.id)">        
								<span id="pic_data" style="display:none">
								{template:form/select,pic,0,$_configs['pic'],$css_attr} 
								</span>        
							</div>
						</li>
						<!--
						<li class="i">
							<div class="form_ul_div">
								<span class="title">模板文件：</span>
								<input type="file" name="file_data" id="file_data"  onchange="isvalidatefile(this.id)">        
								<select id='pic_type' name="pic_type" style="display:none">
									<option value="0">-请选择-</option>
									<option value="1">模板图片</option>
									<option value="2">模板示意图</option>
								</select>
							</div>
						</li>
						--><!--   <li class="i">
							<div class="form_ul_div clear">
							<div style="float:left">
								<span class="title">预览：</span>
								{$list['material']}
							</div>
							<input type="hidden" value="{$formdata['_material']}" name="material" id="material_url">
							</div>
							id= "aid"
						</li>
						-->
					</ul>
				{/if}
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<!--<input type="hidden" name="flag" value="flag" onclick="isvalidatefile('file_data');"/>
				--><br />
				<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}