{template:head}
{code}
$page_data = $formdata['data'];
unset($formdata['data']);
$default = array(
	'field'				=>	'id',
	'count_field'		=>	'count',
	'offset_field'		=>	'offset',
	'name_field'		=>	'name',
	'father_field'		=>	'fid',
	'last_field'		=>	'is_last',
	'colindex'			=>	'index',
	'list_name'			=>	'list',
	
);
foreach($default as $k=>$v)
{
	if(!$formdata[$k])
	{
		$formdata[$k] = $v;
	}
}

{/code}
{css:ad_style}
{css:column_node}
{css:2013/list}
<style>
form{height:100%;padding-bottom:50px;}
.form_ul_div.l input{float:left}
.form_ul .n-h{float:none}
.ad_middle table textarea{min-width:0;min-height: 0;}
.option_del_box{display:inline-block;width:16px;height:16px;cursor:pointer;float:right;position: relative;right: 50px;top: 4px;}
.option_del{display:none;width:16px;height:16px;cursor:pointer;float:right;background: url('{$RESOURCE_URL}close_plan.png') 0 0 no-repeat;}
.option_del_b{width:16px;height:16px;cursor:pointer;float:right;position: relative;right: 140px;top: 4px;background: url('{$RESOURCE_URL}close_plan.png') 0 0 no-repeat;}
</style>
<script type="text/javascript">
	function hg_addArgumentDom(str)
	{
		var div = "<div class='form-each m2o-flex m2o-flex-center'><div class='form-item m2o-flex-one form-para'><input type='text' name='"+str+"argument_name[]' class='title' /></div><div class='form-item m2o-flex-one form-mark'><input type='text' name='"+str+"ident[]' style='width:50px;' class='title' value=''/></div><div class='form-item form-value'><input type='text' name='"+str+"value[]' value=''/></div><div class='form-item form-add'><select name=''+str+'add_status[]'><option value='0'>系统生成</option><option value ='1'>用户添加</option><option value ='2'>文件上传</option></select></div><div class='form-item form-delete'><span name='"+str+"option_del[]' class='option_delete' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></div>";
		//var div = "<div class='form_ul_div clear'><span class='title'>参数名称: </span><input type='text' name='"+str+"argument_name[]' style='width:90px;' class='title'>&nbsp;&nbsp;标识: <input type='text' name='"+str+"ident[]' style='width:90px;' class='title'>&nbsp;值: <input type='text' name='"+str+"value[]' size='40'/>&nbsp;&nbsp;<span>添加方式: </span><select name='"+str+"add_status[]'><option value='0'>系统生成</option><option value ='1'>用户添加</option><option value ='2'>文件上传</option></select><span class='option_del_box'><span name='"+str+"option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span></div>";
		if(str=='')
		{
			$('.page-para').append(div);
		}
		else
		{
			$('#out_extend').append(div);
		}
		adjustHeight();
	}
	function hg_optionTitleDel(obj)
	{
		if(confirm('确定删除该参数配置吗？'))
		{
			$(obj).parent().parent().remove();
		}
	}
	$(document).ready(function(){
		var t1 = $("form select[name=sort_id]").find('option:selected').val();
		var c1 = $("input[name=referto]").val() + '&sortid=' + t1;
		$("input[name=referto]").val(c1);

		$("form select[name=sort_id]").change(function(){
			var t2 = $("form select[name=sort_id]").find('option:selected').val();
			var c2 = $("input[name=referto]").val() + '&sortid=' + t2;
			$("input[name=referto]").val(c2);
		});	
	});

	function adjustHeight(){
		parent.$('#column-iframe-box').height( $('html').height() );
	}
	
	$(function(){
		if($('input[name="is_linkapp"]:checked').val()==0)
		{
			 $('.para').addClass('hide');
			 $('.ap').addClass('hide');
			 $('.ho').removeClass('hide');
	     	 $('.dir').removeClass('hide');
	         $('input[name="column_dir"]').removeClass('hide');
	     	 $('input[name="host"]').removeClass('hide');
		}
		else
		{
			$('.nd').addClass('hide');
			$('.pf').addClass('hide');
		}
		$('input[name="is_linkapp"]').on('click',function(){
			if($('input[name="is_linkapp"]:checked').val()==0)
			{
				 $('.para').addClass('hide');
				 $('.ap').addClass('hide');
				 $('.ho').removeClass('hide');
		     	 $('.dir').removeClass('hide');
		         $('input[name="column_dir"]').removeClass('hide');
		     	 $('input[name="host"]').removeClass('hide');
			}
			else
			{
				$('.para').removeClass('hide');
				$('.ap').removeClass('hide');
				$('.ho').addClass('hide');
		     	$('.dir').addClass('hide');
		     	$('input[name="column_dir"]').addClass('hide');
		     	$('input[name="host"]').addClass('hide');
			}
			adjustHeight();

		});
		$('input[name="is_next_domain"]').on('click',function(){
			if($('input[name="is_next_domain"]:checked').val()==1)
			{
				 $('.nd').removeClass('hide');
			}
			else
			{
				$('.nd').addClass('hide');
			}
		});
	});
	
</script>
<style>
.hide{
	display:none!important;
}
.ad_form .form_ul .domain span.title{float:none;display:inline-block;}
</style>
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear" style="padding-bottom: 30px;">
		<div class="ad_middle"  style="width:900px">
			<form name="editform" action="run.php?mid={$_INPUT['mid']}" method="post" class="ad_form h_l">
			<h2>{if $_INPUT['id']}{$formdata['name']}页面编辑{else}新增页面{/if}</h2>
				<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">页面名称:</span>
								<input type="text" value="{$formdata['name']}" name='name' style="width:440px;">
								<font class="important">必填</font>
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="site_title">是否有子级:</span>
								<span class="site_radio">
								  <input type="radio" name="has_child" value="1"  {if  $formdata['has_child']==1}checked="checked"{/if}/> 是
								  <input type="radio" name="has_child" value="0"  {if  $formdata['has_child']==0}checked="checked"{/if}/> 否
							    </span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="site_title">是否有内容:</span>
								<span class="site_radio">
								  <input type="radio" name="has_content" value="1"  {if  $formdata['has_content']==1}checked="checked"{/if}/> 是
								  <input type="radio" name="has_content" value="0"  {if  $formdata['has_content']==0}checked="checked"{/if}/> 否
							    </span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="site_title">是否是分类:</span>
								<span class="site_radio">
								  <input type="radio" name="is_sort" value="1"  {if  $formdata['is_sort']==1}checked="checked"{/if}/> 是
								  <input type="radio" name="is_sort" value="0"  {if  $formdata['is_sort']==0}checked="checked"{/if}/> 否
							    </span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="site_title">是否关联应用:</span>
								<span class="site_radio">
							    	<input type="radio" name="is_linkapp" value="1"  {if  $formdata['is_linkapp']==1}checked="checked"{/if}/> 是
							    	<input type="radio" name="is_linkapp" value="0"  {if  $formdata['is_linkapp']==0}checked="checked"{/if}/> 否
							    </span>
							</div>
						</li>
						<li class="i ap" >
							<div class="form_ul_div clear ">
								<span class="title">关联应用:</span>
								{code}
									$attr_pro = array(
										'class' => 'transcoding down_list',
										'show'  => 'select_app',
										'width' => 180,/*列表宽度*/
										'state' => 0,/*0--正常数据选择列表，1--日期选择*/
									);
									$formdata['app'] =  $formdata['app']? $formdata['app'] : 'cp_channel';
								{/code}
								
								{template:form/search_source,app,$formdata['app'],$apps[0],$attr_pro}
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear domain">
								<span class="title ho">域名:</span>
								<input type="text" name="host"  value="{$formdata['host']}" />
								<span class="title dir">目录:</span>
								<input type="text" name="dir" value="{$formdata['dir']}" />
								<span  class="title">文件名:</span>
								<input type="text" name="file_name" size="30" value="{$formdata['file_name']}" />
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
						<li class="i ho pf">
							<div class="form_ul_div clear">
								<span class="title">是否启用二级域名:</span>
								<span class="site_radio">
								<input type="radio" name="is_next_domain" value="1"  {if  $formdata['is_next_domain']==1}checked="checked"{/if}/> 是
								<input type="radio" name="is_next_domain" value="0"  {if  $formdata['is_next_domain']==0}checked="checked"{/if}/> 否
							   </span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">页面标识:</span>
								<input type="text" value="{$formdata['sign']}" name='sign' style="width:440px;">
								<font class="important">必填</font>
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i para">
							<div class="form_ul_div clear">
								<span  class="title">域名:</span>
								<input type="text" value="{$formdata['domain']}" name='domain' style="width:440px;">
							</div>
						</li>
						<li class="i para nd">
							<div class="form_ul_div clear">
								<span  class="title">二级域名:</span>
								<input type="text" value="{$formdata['column_domain']}" name='column_domain' style="width:440px;">
							</div>
						</li>
						<li class="i para">
							<div class="form_ul_div clear">
								<span  class="title">生成目录:</span>
								<input type="text" value="{$formdata['column_dir']}" name='column_dir' style="width:440px;">
							</div>
						</li>
						<li class="i para">
							<div class="form_ul_div clear">
								<span  class="title">字段:</span>
								<input type="text" value="{$formdata['field']}" name='field' style="width:440px;">
							</div>
						</li>
						<li class="i para">
							<div class="form_ul_div clear">
								<span  class="title">数量字段:</span>
								<input type="text" value="{$formdata['count_field']}" name='count_field' style="width:440px;">
							</div>
						</li>
						<li class="i para">
							<div class="form_ul_div clear">
								<span  class="title">偏移量字段:</span>
								<input type="text" value="{$formdata['offset_field']}" name='offset_field' style="width:440px;">
							</div>
						</li>
						<li class="i para">
							<div class="form_ul_div clear">
								<span  class="title">名称字段:</span>
								<input type="text" value="{$formdata['name_field']}" name='name_field' style="width:440px;">
							</div>
						</li>
						<li class="i para">
							<div class="form_ul_div clear">
								<span  class="title">父级字段:</span>
								<input type="text" value="{$formdata['father_field']}" name='father_field' style="width:440px;">
							</div>
						</li>
						<li class="i para">
							<div class="form_ul_div clear">
								<span  class="title">最后一级字段:</span>
								<input type="text" value="{$formdata['last_field']}" name='last_field' style="width:440px;">
							</div>
						</li>
						{if $page_data['page_data']}
						<!--<li class="i" id="page_data_li" style="display:none">
							<div class="form_ul_div clear">
									<span class="title">页面数据：</span>
									<div id="page_data_content">
									</div>
							</div>
						</li>-->
						{else}
						{/if}
						<li class="i para">
							<div class="form_ul_div">
								<span class="column_title">首页：</span>
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
						<li class="i para">
							<div class="form_ul_div clear">
								<span  class="title">列表名称:</span>
								<input type="text" value="{$formdata['list_name']}" name='list_name' style="width:440px;">
							</div>
						</li>
						<li class="i out-form-div-i"> 
                            <div class="form_ul_div page-para form-div clear">
                               <div class="form-title">
                                  <span> 页面参数</span>
                               </div>
                               <div class="form-list m2o-flex m2o-flex-center">
                                  <div class="form-item m2o-flex-one form-para">参数名称</div>
                                  <div class="form-item m2o-flex-one form-mark">标识</div>
                                  <div class="form-item form-value">值</div>
                                  <div class="form-item form-add">添加方式</div>
                                  <div class="form-item form-delete">&nbsp;</div> 
                               </div>
	                         {if($formdata['argument'])}
						     {foreach $formdata['argument']['argument_name'] as $k=>$v}
	                           <div class="form-each m2o-flex m2o-flex-center items">
	                               <div class="form-item m2o-flex-one form-para form-border"><input type='text' name='argument_name[]' value='{$formdata["argument"]["argument_name"][$k]}'  class='title'></div>
	                               <div class="form-item m2o-flex-one form-mark form-border"><input type='text' name='ident[]' value='{$formdata["argument"]["ident"][$k]}' class='title bs'></div>
	                               <div class="form-item form-value form-border"><input type='text' name='value[]' value='{$formdata["argument"]["value"][$k]}' class='title va' /></div>
	                               <div class="form-item form-add">
			                      	<select name='add_status[]'>
										<option {if !$formdata['argument']['add_status'][$k]}selected='selected'{/if} value='0'>系统添加</option>
										<option {if $formdata['argument']['add_status'][$k] == 1}selected='selected'{/if} value ='1'>用户添加</option>
										<option {if $formdata['argument']['add_status'][$k] == 2}selected='selected'{/if} value ='2'>文件上传</option>
									</select>
						           </div>
						          <div class="form-item form-delete">
						            <span name='option_del[]' class='option_delete' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span>
						          </div>
					          </div>
							  {/foreach}
						      {/if}
						 </div>
						 <br />
						 	<div id="extend">
							</div>
							<div class="form_ul_div clear">
								<span type="text" style="cursor:pointer;padding: 5px 20px;margin-left: 15px;background-color: #5B5B5B;color: white;border-radius: 2px;" onclick="hg_addArgumentDom('');">添加参数</span>
							 </div> 
						</li>

					</ul>
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="site_id" value="{$_INPUT['site_id']}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br/>
					<div class="temp-edit-buttons">
					   <input type="submit" name="sub" value="确定" class="edit-button submit"/>
					   <input type="button" value="取消" class="edit-button cancel" onclick="hg_refresh()"/>
				    </div>
			</form>
		</div>
	</div>
<script>
function hg_refresh()
{
	window.location.href = './run.php?mid=' + gMid + '&infrm=1&nav=1';
}

function hg_select_page(obj)
{
	if(!obj)
	{
		if(gPageid != 0)
		{
			var url = './run.php?mid='+gMid+'&a=get_page_data_form&page_id='+gPageid;
			hg_request_to(url);
		}
	}
	else
	{
		var page_id = $(obj).attr('attrid');
		if(page_id==0)
		{
			hg_select_page_back();
			return;
		}
		var fid = $(obj).attr('fid');
		var url = './run.php?mid='+gMid+'&a=get_page_data_form&page_id='+page_id;
		if(fid)
		{
			url += '&fid='+fid;
		}
		hg_request_to(url);		
	}
}
function hg_select_page_back(obj, noparse)
{
	if(!obj)
	{
		$('#page_data_li').hide();
		$('#page_data_content').html();
	}
	else
	{
		if (!noparse) {
			obj = $.parseJSON(obj);
		}
		var page_info = obj.page_info;
		var page_data = obj.page_data;
		var html = '<ul style="float:left;">';
		$.each(page_data,function(i,n){
			if(n[page_info.last_field] == 1)
			{
				html += '<li><input type="radio" name="page_data_id" value="'+n[page_info.field]+'"/>'+ n[page_info.name_field]+'</li>';
			}
			else
			{
				html += '<li><input type="radio" name="page_data_id" value="'+n[page_info.field]+'"/>' +n[page_info.name_field] +'<span style="pointer:cursor;" attrid="'+page_info.id+'" fid="'+n[page_info.field]+'" onclick="hg_select_page(this);"> > </span></li>';
			}
		});
		html += '</ul>';
		$('#page_data_li').show();
		$('#page_data_content').html(html);
	}
}
$(function ($) {
	hg_select_page_back({code}echo json_encode($page_data);{/code}, true);
});
</script>
{template:foot}