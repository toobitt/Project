{template:head}
{css:ad_style}
{css:column_node}
{js:jquery-ui-1.8.16.custom.min}
{css:jquery.lightbox-0.5}
{js:jquery.lightbox-0.5}
<style type="text/css">
.source_item {cursor:pointer; border:1px solid #CCC; display:inline-block; padding:3px 5px; margin:5px;}
.option_del_box{display:inline-block;width:16px;height:16px;cursor:pointer;float:right;position: relative;right: 270px;top: 4px;}
.option_del {
    background: url("../../.././../livtemplates/tpl/lib/images/close_plan.png") no-repeat scroll 0 0 transparent;
    cursor: pointer;
    display: none;
    float: right;
    height: 16px;
    width: 16px;
}
.source_item {cursor:pointer; border:1px solid #CCC; display:inline-block; padding:3px 5px; margin:5px;}
.staff-img-a{float:right;position:relative;margin-right:10px;}
.staff-img-a span{position:absolute;top:-18px;right:-18px;font-size:18px;}
</style>
<script type="text/javascript">
	function hg_addConnectDom()
	{
		var div = "<div class='form_ul_div clear'><span class='title'>时间: </span><input type='text' name='connect_start_time[]' style='width:90px;' class='title'  onfocus=\"WdatePicker({skin:'whyGreen',dateFmt:'HH:mm:ss'})\">--<input type='text' name='connect_end_time[]' style='width:90px;' class='title'  onfocus=\"WdatePicker({skin:'whyGreen',dateFmt:'HH:mm:ss'})\">电话：&nbsp;<input type='text' name='connect_tel[]' size='17'/>&nbsp;&nbsp;<span class='option_del_box' style='float:right'><span name='option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span></div>";
		$('#extend').append(div);
		hg_resize_nodeFrame();
	}
	function hg_optionTitleDel(obj)
	{
		if(confirm('确定删除该联系方式吗？'))
		{
			$(obj).parent().parent().remove();
		}
		hg_resize_nodeFrame();
	}
	function preview_avatar(id)
	{
		$('#avatar_' +id+ ' a').lightBox();
	}
	function delete_avatar(id, btn)
	{
		$(btn).parent().remove();
		$('#delete_avatar_'+id).val(1);
	}
</script>
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
				<h2>{$optext}资料</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">头像：</span>
								{code}
									$avatar = '';
									if(!empty($formdata['avatar']))
									{	
										$org = $formdata['avatar']['host'] . $formdata['avatar']['dir'] . $formdata['avatar']['filepath'] . $formdata['avatar']['filename'];
										$avatar = $formdata['avatar']['host'] . $formdata['avatar']['dir'] .'40x30/'. $formdata['avatar']['filepath'] . $formdata['avatar']['filename'];
									}
								{/code}
								
								<input type="file" value='' name='Filedata'/>
								{if $avatar}
								<div class="staff-img-a" id = "avatar_{$formdata['id']}" >
									<a  href="{$org}" >
										<img src="{$avatar}" alt="索引图" style="float: right" onclick="preview_avatar({$formdata['id']})" />
									</a>
									<span onclick="delete_avatar({$formdata['id']},this)" style="cursor: pointer">x</span>
								</div>
								{/if}
								<input type="hidden" name="delete_avatar" id = "delete_avatar_{$formdata['id']}" value="0" />
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">姓：</span>
								<input type="text" value="{$formdata['surname']}" name='surname' />
								<span  style="margin-left: 105px;color:#7D7D7D">名：</span>
								<input type="text" value="{$formdata['name']}" name='name' />
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">英文名：</span>
								<input type="text" value="{$formdata['english_name']}" name='english_name'/>
								<span  style="margin-left: 94px;color:#7D7D7D">编号：</span>
								<input type="text" value="{$formdata['number']}" name='number'/>
							</div>
						</li>
						{if $departments}
						{code}
							$departments_css = array(
							'class' =>'transcoding down_list',
							'show' => 'departments_item',
							'width' => 150,
							'state' => 0,
							);
							$formdata['department_id'] = isset($formdata['department_id']) ? $formdata['department_id']: 0;
						{/code}
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">部门：</span>
								{template:form/search_source,department_id,$formdata['department_id'],$departments[0],$departments_css}
							</div>
						</li>
						{/if}
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">职位：</span>
								<input type="text" value="{$formdata['position']}" name='position' />
								<span  style="margin-left: 68px;color:#7D7D7D">英文简称 ：</span>
								<input type="text" value="{$formdata['en_position']}" name='en_position' />
							</div>
						</li>
						{if $_configs['staff_sex']}
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">性别：</span>
								{foreach $_configs['staff_sex'] as $key=>$val}
								<input type="radio" name="sex" value="{$key}"  {if $key==$formdata['sex']}checked="checked"{/if}/>{$val}
								{/foreach}
							</div>
						</li>
						{/if}
						{if $_configs['staff_married']}
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">婚姻：</span>
								{foreach $_configs['staff_married'] as $key=>$val}
								<input type="radio" name="married" value="{$key}"  {if $key==$formdata['is_married']}checked="checked"{/if}/>{$val}
								{/foreach}
							</div>
						</li>
						{/if}
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">籍贯：</span>
								<input type="text" value="{$formdata['native_place']}" name='native_place' style="width:440px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">民族：</span>
								<input type="text" value="{$formdata['nation']}" name='nation' style="width:440px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">政治面貌：</span>
								<input type="text" value="{$formdata['political_status']}" name='political_status' style="width:440px;">
							</div>
						</li>
						{if $_configs['staff_degree']}
						{code}
							$degree_css = array(
							'class' =>'transcoding down_list',
							'show' => 'degree_item',
							'width' => 150,
							'state' => 0,
							);
							$formdata['degree'] = isset($formdata['degree']) ? $formdata['degree']: 3;
						{/code}
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">学历：</span>
								{template:form/search_source,degree,$formdata['degree'],$_configs['staff_degree'],$degree_css}
								<span style="margin-left: 83px;color:#7D7D7D">英语水平：</span>
								<input type="text" value="{$formdata['english_level']}" name='english_level'/>						
							</div>
						</li>
						{/if}
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">电话：</span>
								<input type="text" value="{$_configs['staff_infor']['tel']}" name='tel'  disabled="disabled"/>
								<span  style="margin-left: 80px;color:#7D7D7D">分机号：</span>
								<input type="text" value="{$formdata['ext_num']}" name='ext_num' />
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">手机：</span>
								<input type="text" value="{$formdata['mobile']}" name='mobile' style="width:440px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">邮件：</span>
								<input type="text" value="{$formdata['email']}" name='email' style="width:440px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">家庭住址：</span>
								<input type="text" value="{$formdata['address']}" name='address' style="width:440px;">
							</div>
						</li>	
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">公司：</span>
								<input type="text" value="{$_configs['staff_infor']['company']}" name='company' style="width:440px;" disabled="disabled">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">公司地址：</span>
								<input type="text" value="{$_configs['staff_infor']['company_addr']}" name='company_addr' style="width:440px;" disabled="disabled">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">英文地址：</span>
								<input type="text" value="{$_configs['staff_infor']['en_company_addr']}" name='en_company_addr' style="width:440px;" disabled="disabled">
							</div>
						</li>	
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">网址：</span>
								<input type="text" value="{$_configs['staff_infor']['web']}" name='web' style="width:440px;" disabled="disabled">
							</div>
						</li>			
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">教育情况：</span>
								<textarea rows="3" cols="80" name="education">{$formdata['education']}</textarea>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">工作经历：</span>
								<textarea rows="3" cols="80" name="experience">{$formdata['experience']}</textarea>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">技能：</span>
								<textarea rows="3" cols="80" name="skills">{$formdata['skills']}</textarea>
							</div>
						</li>
						<!-- 
						<li class="i">
							{if $formdata['tel']}
							{foreach $formdata['tel'] as $k=>$v}
							<div class='form_ul_div clear'>
								<span class='title'>时间: </span>
								<input type='text' name='connect_start_time[]' style='width:90px;' class='title' value="{$v['start_time']}"  onfocus=\"WdatePicker({skin:'whyGreen',dateFmt:'HH:mm:ss'})\">--<input type='text' name='connect_end_time[]' style='width:90px;' class='title' value="{$v['end_time']}" onfocus=\"WdatePicker({skin:'whyGreen',dateFmt:'HH:mm:ss'})\">
								电话：&nbsp;<input type='text' name='connect_tel[]' size='17'  value="{$v['tel']}" />&nbsp;&nbsp;<span class='option_del_box' style='float:right'><span name='option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span></div>
							{/foreach}
							{/if}
							<div id="extend"></div>
							<div class="form_ul_div clear">
								<span type="text" style="cursor:pointer;padding: 5px 20px;margin-left: 75px;background-color: #5B5B5B;color: white;border-radius: 2px;" onclick="hg_addConnectDom();">添加联系方式</span>
							</div>
					
						</li>
						 -->
						<li class="i">
							<div class="form_ul_div clear">
								<span><font color='red'>*</font>为必填选项</span>
							</div>
						</li>
					</ul>
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}