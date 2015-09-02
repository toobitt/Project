{template:head}
{code}
$page_data = $formdata['data'];
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
			<h2>{if $_INPUT['id']}{$formdata['name']}更新计划{else}新增计划{/if}</h2>
				<ul class="form_ul">
                                    <li class="i">
							<div class="form_ul_div clear">
								<span  class="title">标题:</span>
								<input type="text" value="{$formdata['title']}" name='title' style="width:100px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">站点id:</span>
								<input type="text" value="{$formdata['site_id']}" name='site_id' style="width:100px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">页面类型id:</span>
								  <input type="text" value="{$formdata['page_id']}" name='page_id' style="width:100px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">页面数据id:</span>
								  <input type="text" value="{$formdata['page_data_id']}" name='page_data_id' style="width:100px;">
							</div>
						</li>
                                                <li class="i">
							<div class="form_ul_div clear">
								<span class="title">内容类型id:</span>
								<input type="text" value="{$formdata['content_type']}" name='content_type' style="width:100px;"> 
							</div>
						</li>
                                                <li class="i">
							<div class="form_ul_div clear">
								<span class="title">客户端类型id:</span>
								<input type="text" value="{$formdata['client_type']}" name='client_type' style="width:100px;"> 
							</div>
						</li>
                                                <li class="i">
							<div class="form_ul_div clear">
								<span class="title">执行间隔:</span>
								<input type="text" value="{$formdata['mk_time']}" name='mk_time' style="width:100px;"> s
							</div>
						</li>
                                                <li class="i">
							<div class="form_ul_div clear">
								<span class="title">状态:</span>
								<input type="checkbox" name="is_open" value="1" {code}if($formdata['is_open']) echo "checked";{/code}>开启
							</div>
						</li>

					</ul>
				<input type="hidden" name="a" value="{if $formdata['id']}update{else}create{/if}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="id" value="{$formdata['id']}" />
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