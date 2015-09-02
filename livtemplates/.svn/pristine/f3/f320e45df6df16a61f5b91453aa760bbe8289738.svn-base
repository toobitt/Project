<?php 
?>
{template:head}
{css:column_form}
{css:ad_style}
{js:publishsys/datasource_out_variable}
{css:common/common_publish}
{code}
$info = $formdata[0];
$app = $formdata[1];
$id = $_INPUT['id'];
$type = $_INPUT['type'];
$file_name = $_INPUT['file_name'];
if($type)
{
	$file_name = explode('.', $file_name);
	$file_name = $file_name[0].'_copy'.'.php';
}
$css_attr['style'] = 'style="width:100px"';
$out_arment  = $info['out_arment'] ? $info['out_arment'] : $_configs['data_source_out_argument'];
{/code}

{css:template_list}
{css:2013/form}
{css:common/common}
{css:common}
<style>
.form_ul_div.l input{float:left}
.form_ul .n-h{float:none}
.ad_middle table textarea{min-width:0;min-height: 0;}
.option_del_box{display:inline-block;width:16px;height:16px;cursor:pointer;float:right;position: relative;right: 50px;top: 4px;}
.option_del{display:none;width:16px;height:16px;cursor:pointer;float:right;background: url('{$RESOURCE_URL}close_plan.png') 0 0 no-repeat;}
.option_del_b{width:16px;height:16px;cursor:pointer;float:right;position: relative;right: 140px;top: 4px;background: url('{$RESOURCE_URL}close_plan.png') 0 0 no-repeat;}
.temp-edit-buttons {margin:0 0 20px;}
#extend input{border:1px solid #DEDEDE!important;}
</style>
<style>
body{padding-bottom:0;}
.m2o-main .m2o-l{background:#fff;}
.m2o-item:last-child{border:0;}
.submit{right:150px;}
.column-delete-button {
    color: #115BA4;
    cursor: pointer;
    margin-left: 10px;
    text-decoration: underline;
    float:none;
    background:none;
}
.hide{
	display:none!important;
}
.ad_form .form_ul .domain span.title{float:none;display:inline-block;}
.ad_form .form_ul .form_ul_div span.title{width:90px;}
.m2o-l .m2o-item .down_list{vertical-align:middle;}
.print_view{cursor:pointer;padding: 5px 20px;margin-left:15px;background-color: #5B5B5B;color: white;border-radius: 2px;}
</style>
{code}
if(!class_exists('column'))
{
    include_once(ROOT_DIR . 'lib/class/column.class.php');
    $publish = new column();
}
//获取所有站点
$hg_sites = $publish->getallsites();

{/code}
<script>
var updata_id = function (name, id) {
	$('.column-ul li[_id="'+ id +'"]').find('.column-name').text(name);
}

function change_app()
{
	if($('#app_id').val() == 0)
	{
    	 $('.ho').removeClass('hide');
     	 $('.dir').removeClass('hide');
         $('input[name="dir"]').removeClass('hide');
     	 $('input[name="host"]').removeClass('hide');
	}
	else
	{
		 $('.ho').addClass('hide');
     	 $('.dir').addClass('hide');
     	 $('input[name="dir"]').addClass('hide');
     	 $('input[name="host"]').addClass('hide');
	}
	
}

$(function(){
	$(".datepicker").datepicker(); 
	
	if($('#app_id').val() != 0)
	{
    	 $('.ho').addClass('hide');
     	 $('.dir').addClass('hide');
     	 $('input[name="dir"]').addClass('hide');
     	 $('input[name="host"]').addClass('hide');
	}
	if(+$('#cache_update').val()=='2')
	{
     	$('input[name="cache_update_time"]').removeClass('hide');
     	$('.s').removeClass('hide');
	}
})

function change_ca()
{
	if(+$('#cache_update').val()=='2')
	{
     	$('input[name="cache_update_time"]').removeClass('hide');
     	$('.s').removeClass('hide');
	}
	else
	{
		$('input[name="cache_update_time"]').addClass('hide');
		$('.s').addClass('hide');
	}
}

</script>
<script type="text/javascript">
	function hg_addArgumentDom(str)
	{
		var div = "<div class='form-each m2o-flex m2o-flex-center'><div class='form-item m2o-flex-two form-para'><input type='text' name='"+str+"argument_name[]' class='title' value=''/></div><div class='form-item m2o-flex-one form-mark'><input type='text' name='"+str+"ident[]' style='width:50px;' class='title' value=''/></div><div class='form-item form-value'><input type='text' name='"+str+"value[]' value=''/></div><div class='form-item form-flex-two form-drop'><input type='text' name='"+str+"other_value[]' value='' /></div><div class='form-item form-type'><select name='"+str+"type[]'><option  value ='text'>输入框</option><option  value ='select'>下拉框</option></select></div><div class='form-item form-add'><select name='"+str+"add_status[]'><option value='0'>系统生成</option><option value ='1'>用户添加</option><option value ='2'>文件上传</option></select></div><div class='form-item form-request'><select name='"+str+"add_request[]'><option value ='post'>POST</option><option value ='get'>GET</option></select></div><div class='form-item form-delete'><span name='"+str+"option_del[]' class='option_delete' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></div>";
		if(str=='')
		{
			$('.condition-form-div').append(div);
		}
		else
		{
			$('#out_extend').append(div);
		}
		hg_resize_nodeFrame();
	}
	function hg_addoutArgumentDom(str)
	{
		var div = "<div class='form-each m2o-flex m2o-flex-center'><div class='form-item m2o-flex-two form-para'><input type='text' name='"+str+"new_out_name[]' class='title' value=''/></div><div class='form-item m2o-flex-one form-mark'><input type='text' name='"+str+"new_out_title[]' style='width:50px;' class='title' value=''/></div><div class='form-item form-value'><input type='text' name='"+str+"new_out_value[]' value=''/></div><div class='form-item form-delete'><span name=''+str+'option_del[]' class='option_delete' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></div></div>";
		if(str=='')
		{
			$('.out-form-div').append(div);
		}
		get_out_ids();
		hg_resize_nodeFrame();
	}
	function hg_optionTitleDel(obj)
	{
		if(confirm('确定删除该参数配置吗？'))
		{
			$(obj).parent().parent().remove();
		}
		
		get_out_ids();
		hg_resize_nodeFrame();
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
		get_out_ids();
	});
	
	function get_out_ids()
	{
		var ids = $('.out_arment .form-each').map(function(){
		     return $(this).data('id');
		}).get().join(',');
		$('#out_ids').val(ids);
		//alert($('#out_ids').val());
	}
</script>
<script>
$(function(){
	$('.print_view').on('click',function(event){
		var id = $(this).data('id'),
		    url = "./run.php?mid={$_INPUT['mid']}&a=get_datasource_data",
		 	bs=[],value=[];
		$('.items').each(function(){
		      var str1 = $(this).find('.bs').val();
		      bs.push(str1);
		      var str2 = $(this).find('.va').val();
		      value.push(str2);
		});
		$.get(url, {id:id,bs:bs,value:value,flag:1}, function(data){
			da = JSON.parse(data)[0];
			if(da.error)
			{
				jAlert(da.error,'数据预览提醒');
			}
			else
			{
				$('.common-list-ajax-pub').css({'top':0});
				$('.view-content').val(print_r(da, 1));
			}
		});
	});
})

function check_sign()
{
	var sign = $('#sign').val();
	var id = $('#id').val();
	if(sign)
	{
		var url= "./run.php?mid=" + gMid + "&a=check_sign";
    	$.ajax({
		type:'get',
		url:url,
		data:{sign:sign,id:id},
		dataType:'Json',
		success:function(msg){
			if(msg!=1)
			{
				alert('该标识已存在');
				$('input[name="sign"]').val('');
			}
		},
		error:function(){
		
		}
		})
	}
}
</script>
<div>
<form name="editform"  id="editform" action="run.php?mid={$_INPUT['mid']}" method="post" class="ad_form h_l class="m2o-form"">

<!--head 开始-->
<header class="m2o-header">
  <div class="m2o-inner">
    <div class="m2o-title m2o-flex m2o-flex-center">
        <h1 class="m2o-l">{if $_INPUT['id']}编辑数据源{else}新增数据源{/if}</h1>
        <div class="m2o-m m2o-flex-one">
            <input placeholder="数据源名称" name="name"  class="m2o-m-title" value="{$info['name']}" />
        </div>
        <div class="m2o-btn m2o-r">
			<input type="submit" name="sub" value="{$optext}" class="m2o-save"/>
			{if $a=='update'}
			<input type="button" name="lcw" value="另存为" class="m2o-savae-as submit" onclick="$('#a').val('create');$('#editform').submit()"/>
			{/if}
			<span class="m2o-close option-iframe-back"></span>
		</div>
    </div>
   </div>
</header>
<!--head 结束-->	
<div class="m2o-inner">
   <div class="m2o-main m2o-flex">
   	 <aside class="m2o-l">
	   <div class="m2o-item">
	        <span class="title">标识:</span>
			<input type="text" name="sign" onblur="check_sign()"  id ="sign" value="{$info['sign']}" />
	   </div>
	   <div class="m2o-item">
	        <span class="title">所属模块:</span>
			{code}
				$attr_pro = array(
					'class' => 'down_list',
					'show'  => 'select_app',
					'state' => 0,/*0--正常数据选择列表，1--日期选择*/
					'onclick' => 'change_app();'
				);
				$info['app_id'] =  $info['app_id']	? $info['app_id'] : '0';
			{/code}
			
			{template:form/search_source,app_id,$info['app_id'],$apps[0],$attr_pro}<font class="important"></font>
	   </div>
	   <div class="m2o-item">
	        <span class="title">描述:</span>
			<textarea name="brif" cols="60" rows="5" style="width:190px;" placeholder="这里添加描述">{$info['brif']}</textarea>
	   </div>
	  </aside>
	   <section class="m2o-m m2o-flex-one">
	   		<ul class="form_ul">
			<!--<li class="i">
			<div class="form_ul_div clear">
			<span  class="title">请求文件名: </span><input type="text" name="request_file" size="50" value="{$info['request_file']}" />
			</div>
			</li>-->
			<li class="i">
			<div class="form_ul_div clear domain">
			<span class="title ho">域名: </span>
			<input type="text" name="host"  value="{$info['host']}" />
			<span class="title dir">目录：</span>
			<input type="text" name="dir" value="{$info['dir']}" />
			<span  class="title">文件名: </span>
			<input type="text" name="request_file" size="50" value="{$info['request_file']}" style='width:180px;'/>&nbsp;&nbsp;
			{if $_INPUT['id']}<span class="print_view" data-id="{$info['id']}">数据预览</span>{/if}
			<!--<font class="important">例如：localhost/public/api 127.0.0.1/public/api</font>-->
			</div>
			</li>
			<li class="i">
			<div class="form_ul_div clear">
			<span class="title">接口协议: </span>{template:form/select,protocol,$info['protocol'],$_configs['api_protocol'], $css_attr}<font class="important"></font>
			</div>
			</li>
			<!--<li class="i">
			<div class="form_ul_div clear">
			<span class="title">请求方式: </span>{template:form/select,request_type,$info['request_type'],$_configs['request_type'], $css_attr}<font class="important"></font>
			</div>
			</li>-->
			
			<li class="i">
			<div class="form_ul_div clear">
			<span  class="title">数据格式: </span>{template:form/select,data_format,$info['data_format'],$_configs['data_format'], $css_attr}<font class="important">接口返回的数据格式</font>
			</div>
			</li>
			
			<li class="i">
			<div class="form_ul_div clear">
			<span  class="title">缓存更新: </span>
			{code}
				$attr_ca = array(
					'class' => 'down_list',
					'show'  => 'select_ca',
					'state' => 0,/*0--正常数据选择列表，1--日期选择*/
					'onclick' => 'change_ca();'
				);
				$info['cache_update'] = $info['cache_update']?$info['cache_update']:'1';
			{/code}
			{template:form/search_source,cache_update,$info['cache_update'],$_configs['cache_update'],$attr_ca}
			<!--<input type="text" value="{$info['cache_update_time']}" name='cache_update_time' onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm'})"  class="hide" style="float:left;margin-left:10px;">-->
			<input type="text" value="{$info['cache_update_time']}" name='cache_update_time' class="hide" style="float:left;margin-left:10px;"><span class="hide s" style="float:left;margin-left:5px;">s</span>
			<font class="important">缓存更新设置</font>
			</div>
			</li>
			<li class="i">
			<div class="form_ul_div clear">
			<span  class="title">数据节点: </span><input type="text" name="data_node" size="11" value="{$info['data_node']}" /><font class="important">默认根节点</font>
			</div>
			</li>
			<li class="i">
			<div class="form_ul_div clear">
			<span  class="title">全局返回参数: </span><input type="text" name="out_param" size="11" value="{$info['out_param']}" />
			</div>
			</li>
			<li class="i">
			<div class="form_ul_div clear">
			<span  class="title">不传递系统参数: </span><input type="checkbox" name="is_parameter" {code}if($info['is_parameter']==1)echo 'checked';{/code} value="1" />
			</div>
			</li>
			<!--<li class="i">
			<div class="form_ul_div clear">
			<span  class="title">直接返回: </span><input type="text" name="direct_return" size="50" value="{$info['direct_return']}" /><font class="important">可选，如果不需要留空即可</font>
			</div>
			</li>-->
			<li class="i form-div-i"> 
			  <div class="form_ul_div condition-form-div form-div clear">
			    <div class="form-title">
			     <span> 设置数据条件参数</span>
			     <font color=red>*下拉框值填写格式:（1=>新闻\n2=>图集）1为值 2为显示名称，多个空格隔开*</font>
			    </div>
			  <div class="form-list m2o-flex m2o-flex-center">
			     <div class="form-item m2o-flex-two form-para">参数名称</div>
			     <div class="form-item m2o-flex-one form-mark">标识</div>
			     <div class="form-item form-value">默认值</div>
			     <div class="form-item form-flex-two form-drop">下拉框值</div>
			     <div class="form-item form-type">类型</div>
			     <div class="form-item form-add">添加方式</div>
			     <div class="form-item form-request">请求方式</div>
			     <div class="form-item form-delete">&nbsp;</div> 
			  </div>
				{if($info['argument'])}
				{foreach $info['argument']['argument_name'] as $k=>$v}
				  <div class="form-each m2o-flex m2o-flex-center items">
			          <div class="form-item m2o-flex-two form-para form-border"><input type='text' name='argument_name[]' value='{$info["argument"]["argument_name"][$k]}'  class='title'></div>
			          <div class="form-item m2o-flex-one form-mark form-border"><input type='text' name='ident[]' value='{$info["argument"]["ident"][$k]}' class='title bs'></div>
			          <div class="form-item form-value form-border"><input type='text' name='value[]' value='{$info["argument"]["value"][$k]}' class='title va' /></div>
			          <div class="form-item form-flex-two form-drop form-border"><input type='text' name='other_value[]' value='{$info["argument"]["other_value"][$k]}' /></div>
			          <div class="form-item form-type">
					     <select name='type[]'>
							<option {if $info['argument']['type'][$k] == 'text'}selected='selected'{/if} value ='text'>输入框</option>
							<option {if $info['argument']['type'][$k] == 'select'}selected='selected'{/if} value ='select'>下拉框</option>
							<option {if $info['argument']['type'][$k] == 'column'}selected='selected'{/if} value ='column'>栏目</option>
							<option {if $info['argument']['type'][$k] == 'special_column'}selected='selected'{/if} value ='special_column'>专题栏目</option>
							<option {if $info['argument']['type'][$k] == 'auto'}selected='selected'{/if} value ='auto'>自动获取</option>
						</select>
			          </div>
			          <div class="form-item form-add">
						<select name='add_status[]'>
							<option {if !$info['argument']['add_status'][$k]}selected='selected'{/if} value='0'>系统添加</option>
							<option {if $info['argument']['add_status'][$k] == 1}selected='selected'{/if} value ='1'>用户添加</option>
							<option {if $info['argument']['add_status'][$k] == 2}selected='selected'{/if} value ='2'>文件上传</option>
						</select>
			          </div>
			          <div class="form-item form-request">
						<select name='add_request[]'>
							<option {if $info['argument']['add_request'][$k] == 'post'}selected='selected'{/if} value ='post'>POST</option>
							<option {if $info['argument']['add_request'][$k] == 'get'}selected='selected'{/if} value ='get'>GET</option>
						</select>
			          </div>
			          <div class="form-item form-delete">
			          <!-- <span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span>  -->  
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
					<span type="text" style="cursor:pointer;padding: 5px 20px;margin-left:15px;background-color: #5B5B5B;color: white;border-radius: 2px;" onclick="hg_addArgumentDom('');">添加参数</span>
				</div> 
			</li>
			<li class="i out-form-div-i"> 
			  <div class="form_ul_div out-form-div form-div  clear">
			    <div class="form-title">
			     <span> 设置数据返回参数</span>
			    </div>
			  <div class="form-list m2o-flex m2o-flex-center">
			     <div class="form-item m2o-flex-two form-para">参数名</div>
			     <div class="form-item m2o-flex-one form-mark">标题</div>
			     <div class="form-item form-value">对应参数标识</div>
			     <div class="form-item form-delete">&nbsp;</div> 
			  </div>
			  {code}
			 	//print_r($out_arment);
			  {/code}
				{if $out_arment}
				<div class="out_arment">
					{foreach $out_arment['name'] as $k=>$v}
					  <div class="form-each m2o-flex m2o-flex-center items" data-id="{$k}">
				          <div class="form-item m2o-flex-two form-para form-border"><input type='text' name='out_arname[{$k}]' value='{$v}'  class='title'></div>
				          <div class="form-item m2o-flex-one form-mark form-border"><input type='text' name='out_artitle[{$k}]' value='{$out_arment["title"][$k]}' class='title bs'></div>
				          <div class="form-item form-value form-border"><input type='text' name='out_arvalue[{$k}]' value='{$out_arment["value"][$k]}' class='title va' /></div>
				          <div class="form-item form-delete">
				          <!-- <span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span>  -->  
				            <span name='option_del[]' class='option_delete' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span>
				          </div>
				        </div>
				        <input type="hidden"  name="out_ar[]" value="{$k}" />
					{/foreach}
				</div>
				{/if}
				<input type="hidden" name="new_out_ar[]"  id="out_ids" />
			 </div>
			 <br />
			 	<div id="out_extend">
				</div>
				<div class="form_ul_div clear">
					<span type="text" style="cursor:pointer;padding: 5px 20px;margin-left:15px;background-color: #5B5B5B;color: white;border-radius: 2px;" onclick="hg_addoutArgumentDom('');">添加数据返回参数</span>
				</div> 
			</li>
		</ul>
	   </section>
</div>
</div>


<input type="hidden" name="a" value="{$a}" id="a" />
<input type="hidden" name="id" id="id" value="{$id}" />
<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
<input type="hidden" name="fid" value="{$info['fid']}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />

</form>
</div>
<div id="vodpub" class="common-list-ajax-pub"  style="margin-left:-350px;">
	<div class="common-list-pub-title">
		<p>预览</p>
	</div>
	<div id="vodpub_body" class="common-list-pub-body">
	    <div class="publish-box">
	         <textarea class="view-content" style="height:300px;width:98%;"></textarea>
	    </div>
	</div>
	<span onclick="hg_vodpub_hide();"></span>
</div>
{template:foot}