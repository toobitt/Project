{template:head}
{css:column_node}
{js:column_node}
{code}
$list = $formdata[0];
{/code}
{css:ad_style}
{css:column_node}

<script type="text/javascript">
function change_type()
{
	if('1' == $("#page_type").val())
	{
		$('#column').css('display','block');		
	}
	else
	{
		$('#column').css('display','none');		
	}
	hg_resize_nodeFrame();
}
window.onload = function(){
 //alert($("#page_type").val());
	if('1' == $("#page_type").val())
	{
		$('#column').css('display','block');		
	}
};
</script>
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
			{if $_INPUT['id']}
				<h2>编辑页面信息</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">页面标题：</span>
								<input type="text" value="{$list['name']}" name='name' style="width:200px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">页面描述：</span>
								<textarea rows="3" cols="80" name='brief'>{$list['brief']}</textarea>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="site_title">所属类型: </span>
								{code}
									$arr_type = array(
										'class' => 'transcoding down_list',
										'show'  => 'select_type',
										'width' => 180,/*列表宽度*/
										'state' => 0,/*0--正常数据选择列表，1--日期选择*/
										'onclick' => 'change_type();'
									);
									$arr_client = array(
										'class' => 'transcoding down_list',
										'show'  => 'select_client',
										'width' => 180,/*列表宽度*/
										'state' => 0,/*0--正常数据选择列表，1--日期选择*/
									);
									$list['client'] = $list['client']?$list['client']:'2';
								{/code}
								{template:form/search_source,page_type,$list['page_type'],$page_types[0],$arr_type}
							</div>
						</li>
						<li class="i" id="column" style="display:none">
							<div class="form_ul_div clear">
								<span class="site_title">所属栏目：</span>
								{code}
									$hg_attr['node_en'] = 'publishconfig_column';
									$site_id = $column_form[0]['site_id'];
									$hg_attr['expand'] = array('site_id'=>$site_id);
								{/code}
								{template:unit/class,column_id,$list['column_id'],$node_data}
							</div>
						</li>	
						<li class="i">
							<div class="form_ul_div clear">
								<span class="site_title">客户端: </span>
								{template:form/search_source,client,$list['client'],$clients[0],$arr_client}
							</div>
						</li>
						<li class="i" id="spe_2" style="display:none">
							<div class="form_ul_div" id='special'>
								<span  class="site_title">专题：</span>
								{if $info['column']}
								<select name='special'><option>{$info['column']}</option></select>
								{else}
								<select name='special'><option>-请选择-</option></select>
								{/if}
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">生成目录：</span>
								<input type="text" value="{$list['dir']}" name='dir' style="width:200px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">生成文件名：</span>
								<input type="text" value="{$list['file_name']}" name='file_name' style="width:200px;">
								<input type="text" value="{$list['file_type']}" name='file_type' style="width:50px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">域名：</span>
								<input type="text" value="{$list['domain_name']}" name='domain_name' style="width:200px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">SEO：</span>
								<input type="text" value="{$list['seo']}" name='seo' style="width:200px;">
							</div>
						</li>
						<!--  <li class="i">
							<div class="form_ul_div">
								<span  class="site_title">是否有推送区块：</span>
								<input type="radio" name="is_push" value="1" {if  $list['is_push']==1}checked="checked"{/if}/> 是
								<input type="radio" name="is_push" value="0" {if  $list['is_push']==0}checked="checked"{/if}/> 否
							</div>
						</li>-->
					</ul>
				{else}
				{/if}
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	<!--  <div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>-->
	</div>
{template:foot}