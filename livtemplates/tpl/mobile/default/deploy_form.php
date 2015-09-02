<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{js:common/common_form}
{js:common}
{css:2013/form}
{css:common/common}
{css:mobile_form}
{css:ad_style}
{code}
$id = $_INPUT['id'];
$type = $_INPUT['type'];
if($id)
{	
	$a = 'update';	
	$create_type = '更新';
}
else
{
	$a = 'create';
	$create_type = '添加';
}

$file_name = $_INPUT['file_name'];
if($type)
{
	$a = 'create';
	$create_type = '添加配置';
	
	$file_name = explode('.', $file_name);
	$file_name = $file_name[0].'_copy'.'.php';
}

$css_attr['style'] 	= 'style="width:100px"';
$appendSort 		= $appendSort[0];
$map_val 			= $formdata['map_val'];
$extend_api			= $formdata['extend_api'];
unset($formdata['map_val'],$formdata['extend_api']);
{/code}
<style>
.form_ul_div.l input{float:left}
.form_ul .n-h{float:none}
.ad_middle table textarea{min-width:0;min-height: 0;}
.option_del_box{display:inline-block;width:16px;height:16px;cursor:pointer;float:right;position: relative;right: 10px;top: 4px;}
.option_del {
display: none;
width: 16px;
height: 16px;
cursor: pointer;
float: right;
background: url('{$RESOURCE_URL}close_plan.png') 0 0 no-repeat;
}
</style>
<script type="text/javascript">
	function hg_addArgumentDom()
	{
		var div = "<div class='form_ul_div clear'><span class='title'>参数名称: </span><input type='text' name='argument_name[]' style='width:70px;' class='title'>&nbsp;&nbsp;接口标识: <input type='text' name='ident[]' style='width:70px;' class='title'>&nbsp;&nbsp;接收标识: <input type='text' name='ident_input[]' style='width:70px;' class='title'>&nbsp;&nbsp;<span>值类型: </span><select name='val_type[]'><option value='0'>手动输入</option><option value ='1'>栏目</option></select>&nbsp;&nbsp;值: <input type='text' name='value[]' size='10'/>&nbsp;&nbsp;<span>添加方式: </span><select name='add_status[]'><option value ='1'>用户添加</option><option value='0'>系统生成</option><option value ='2'>文件上传</option></select><span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span></div>";
		$('#extend').append(div);
		hg_resize_nodeFrame();
	}

	function hg_addMapVal()
	{
		var div = "<div class='form_ul_div clear'><span class='title title-name'>把接口中: </span><input type='text' name='map_val_key[]' size='50' class='title'>&nbsp;&nbsp;<span class='more-index'>替换成: </span><input type='text' name='map_val[]' size='50'/>&nbsp;&nbsp;<span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span></div>";
		$('#map_val').append(div);
		hg_resize_nodeFrame();
	}

	function hg_addApi()
	{
		var div = "<div class='form_ul_div clear'><span class='title title-name'>接口标识: </span><input type='text' name='api_key[]' style='width:90px;' class='title'>&nbsp;&nbsp;<span class='more-index'>接口名称: </span><input type='text' name='api_name[]' size='50'/>&nbsp;&nbsp;<span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span></div>";
		$('#add_api').append(div);
		hg_resize_nodeFrame();
	}
	function hg_optionTitleDel(obj)
	{
		if($(obj).data("save"))
		{
			if(confirm('确定删除该参数配置吗？'))
			{
				$(obj).closest(".form_ul_div").remove();
			}
		}
		else
		{
			$(obj).closest(".form_ul_div").remove();
		}
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
	});
	function change_type(val)
	{
		if(val)
		{
			$('#app_change').toggle();
			$('#default_change').toggle();
			if(val == 'default')
			{
				$('#bundle').attr('disabled',true);
			}
			else
			{
				$('#bundle').attr('disabled',false);
			}
		}
	}

	function hg_extendApi()
	{
		$("#extend_api").toggle();
		$("#curl_main").toggle();
	}
</script>
<form name="editform" action="run.php?mid={$_INPUT['mid']}" method="post" class="m2o-form ad_form h_l">
    <header class="m2o-header">
      <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1 class="m2o-l">{$create_type}文件配置</h1>
            <div class="m2o-l m2o-flex-one">
                <input placeholder="填写接口文件名" name="file_name" id="titles" class="m2o-m-title" value="{$file_name}" />
            </div>
            <div class="m2o-btn m2o-r">
                <input type="submit" value="保存" class="m2o-save" name="sub" id="sub" />
                <span class="m2o-close option-iframe-back"></span>
            </div>
        </div>
      </div>
    </header>
    <div class="m2o-inner">
		<div class="m2o-main m2o-flex">
			 <section class="m2o-m m2o-flex-one">
			 	<font class="important" style="color: red;font-size:14px;float:right;">保存后，自动生成文件</font>
			 	{if $message}
					<div class="error">{$message}</div>
				{/if}
			 	<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">所属分组: </span>{template:form/select,sort_id,$formdata['sort_id'],$appendSort, $css_attr}<font class="important"></font>
			<span class="important"><font color="red">*</font>必选</span>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">备注说明: </span><textarea name="brief" cols="60" rows="5">{$formdata['brief']}</textarea>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span  class="title">请求多接口: </span><input type="checkbox" name="extend_api_switch" onclick="hg_extendApi()"; value="1" {if $formdata['extend_api_switch']}checked="checked"{/if}/><font class="important"><font color="red">*</font>可选，映射不对多接口返回操作</font>
		</div>
	</li>
	
	
	<div id="curl_main" {if $formdata['extend_api_switch']}style="display: none"{/if}>
		<li class="i">
			<div class="form_ul_div clear">
				<span class="title">URL路径：</span>
				<div id="app_change" {if !$formdata['bundle']} style="display:none"{/if}>
					{code}
							$attr_app = array(
								'class' => 'transcoding down_list',
								'show'  => 'select_ap',
								'width' => 180,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
								'onclick' => 'change_module();'
							);
							$apps = $apps[0];
							$apps['-1'] = "-请选择-";
							
							$bundle = $formdata['bundle'];
							$bundle = $bundle ? $bundle : -1;
					{/code}
						
					{template:form/search_source,bundle,$bundle,$apps,$attr_app}
					<font class="important" style="float:right;margin-right:700px;"><font color="red">*</font>读取应用下文件</font>
				</div>
				<div id="default_change" {if $formdata['bundle']} style="display:none"{/if}>
					<div style="float: left">
						<input type="text" name="host" value="{$formdata['host']}" />/<input type="text" name="dir" style="width: 200px" value="{$formdata['dir']}" />
					</div>
					<font class="important" style="float:right;margin-right:300px;"><font color="red">*</font>例如：localhost/public/api 127.0.0.1/public/api</font>
				</div>
				<div  style="float: left;margin-left: 10px;margin-top: 3px;">
					<select name='type' onchange="change_type(this.value);">
						<option {if !$formdata['bundle']}selected='selected'{/if} value='default'>默认</option>
						<option {if $formdata['bundle']}selected='selected'{/if} value='app'>应用</option>
					</select>	
				</div>
			</div>
		</li>
		<li class="i">
			<div class="form_ul_div clear">
				<span  class="title">请求文件名: </span>
				<input type="text" name="request_file" size="50" value="{$formdata['request_file']}" />
				<span class="important"><font color="red">*</font>必填</span>
			</div>
		</li>
		<li class="i">
			<div class="form_ul_div clear">
				<span class="title">返回值处理: </span><textarea name="ret_code" cols="60" rows="5">{$formdata['ret_code']}</textarea>
				<span class="important">返回值处理代码</span>
			</div>
		</li>
		<li class="i">
			<div class="form_ul_div clear">
				<span class="title">接口协议: </span>{template:form/select,protocol,$formdata['protocol'],$_configs['api_protocol'], $css_attr}<font class="important"></font>
			</div>
		</li>
		<li class="i">
			<div class="form_ul_div clear">
				<span class="title">请求方式: </span>{template:form/select,request_type,$formdata['request_type'],$_configs['request_type'], $css_attr}<font class="important"></font>
			</div>
		</li>
		
		<li class="i">
			<div class="form_ul_div clear">
				<span  class="title">数据格式: </span>{template:form/select,data_format,$formdata['data_format'],$_configs['data_format'], $css_attr}<font class="important">接口返回的数据格式</font>
			</div>
		</li>
		
		<li class="i">
			<div class="form_ul_div clear">
				<span  class="title">数据节点: </span><input type="text" name="data_node" size="50" value="{$formdata['data_node']}" /><font class="important">默认根节点</font>
			</div>
		</li>
		
		<li class="i">
			<div class="form_ul_div clear">
				<span class="title">参数处理: </span><textarea name="param_code" cols="60" rows="5">{$formdata['param_code']}</textarea>
				<span class="important">参数处理代码</span>
			</div>
		</li>
		<li class="i">
			
			{if($formdata['argument'])}
			
			{foreach $formdata['argument']['argument_name'] as $k=>$v}
			<div class='form_ul_div clear'>
				<span class='title'>参数名称: </span>
				<input type='text' name='argument_name[]' value='{$formdata["argument"]["argument_name"][$k]}' style='width:70px;' class='title'>&nbsp;
				接口标识: <input type='text' name='ident[]' value='{$formdata["argument"]["ident"][$k]}' style='width:70px;' class='title'>&nbsp;
				接收标识: <input type='text' name='ident_input[]' value='{$formdata["argument"]["ident_input"][$k]}' style='width:70px;' class='title'>&nbsp;&nbsp;
				<span>值类型: </span>
				<select name='val_type[]'>
					<option {if !$formdata['argument']['val_type'][$k]}selected='selected'{/if} value='0'>手动输入</option>
					<option {if $formdata['argument']['val_type'][$k]}selected='selected'{/if} value ='1'>栏目</option>
				</select>&nbsp;
				值: <input type='text' name='value[]' value='{$formdata["argument"]["value"][$k]}' size='10'/>&nbsp;
				<span>添加方式: </span>
				<select name='add_status[]'>
					<option {if !$formdata['argument']['add_status'][$k]}selected='selected'{/if} value='0'>系统添加</option>
					<option {if $formdata['argument']['add_status'][$k] == 1}selected='selected'{/if} value ='1'>用户添加</option>
					<option {if $formdata['argument']['add_status'][$k] == 2}selected='selected'{/if} value ='2'>文件上传</option>
				</select>
				<span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' data-save="1" onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span>
			</div>
			{/foreach}
			{/if}
			<div id="extend"></div>
			<div class="form_ul_div clear">
				<span type="text" style="cursor:pointer;padding: 5px 20px;margin-left: 75px;background-color: #5B5B5B;color: white;border-radius: 2px;" onclick="hg_addArgumentDom();">添加参数</span>
			</div>
			
		</li>
		
		<li class="i">
			{if($map_val)}
				{foreach $map_val as $k=>$v}
				<div class='form_ul_div clear'>
					<span class='title title-name'>把接口中: </span>
					<input type='text' name='map_val_key[]' value='{$k}' size='50' class='title'>&nbsp;
					
					<span class="more-index">替换成: </span>
					<input type='text' name='map_val[]' value='{$v}' size='50'/>&nbsp;
					
					<span class='option_del_box map'><span name='option_del[]' class='option_del' title='删除' data-save="1" onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span>
				</div>
				{/foreach}
			{/if}
			<div id="map_val"></div>
			<div class="form_ul_div clear">
				<span type="text" style="cursor:pointer;padding: 5px 20px;margin-left: 75px;background-color: #5B5B5B;color: white;border-radius: 2px;" onclick="hg_addMapVal();">替换返回值</span>
			</div>
		</li>
		
		<li class="i">
			<div class="form_ul_div clear">
				<span  class="title">直接返回: </span><input type="checkbox" name="direct_return" value="1" {if $formdata['direct_return']}checked="checked"{/if}/><font class="important">可选，选中不再对结果进行映射</font>
			</div>
		</li>
	</div>
	
	
	
	<div id="extend_api" {if $extend_api && $formdata['extend_api_switch']}style="display:block;"{else}style="display:none;"{/if}>
		<li class="i">
			{if($extend_api)}
				{foreach $extend_api as $k=>$v}
				<div class='form_ul_div clear'>
					<span class='title title-name'>接口标识: </span>
					<input type='text' name='api_key[]' value='{$k}' style="width: 90px;" class='title'>&nbsp;
					
					<span class="more-index">接口名称: </span>
					<input type='text' name='api_name[]' value='{$v}' size='50'/>&nbsp;
					
					<span class='option_del_box map'><span name='option_del[]' class='option_del' title='删除' data-save="1" onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span>
				</div>
				{/foreach}
			{/if}
			<div id="add_api"></div>
			<div class="form_ul_div clear">
				<span type="text" style="cursor:pointer;padding: 5px 20px;margin-left: 75px;background-color: #5B5B5B;color: white;border-radius: 2px;" onclick="hg_addApi();">添加接口</span>
			</div>
		</li>
	</div>
	
	
	<li class="i">
		<div class="form_ul_div clear">
			<span  class="title">静态缓存: </span><input type="checkbox" name="static_cache" value="1" {if $formdata['static_cache']}checked="checked"{/if}/><font class="important">可选</font>
		</div>
		<div class="form_ul_div clear">
			<span  class="title">缓存更新: </span><input type="text" name="cache_update" size="14" value="{$formdata['cache_update']}" />分钟<font class="important">不填默认每次更新</font>
		</div>
		
	</li>
	
	
	<li class="i">
		<div class="form_ul_div clear">
			<span  class="title">字符编码: </span><input type="text" name="codefmt" size="50" value="{$formdata['codefmt']}" /><font class="important">可选，如果不需要留空即可</font>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div clear">
			<span  class="title">Token令牌: </span><input type="text" name="token1" size="50" value="{$formdata['token']}" /><font class="important">可选，如果不需要留空即可</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span  class="title">用户名: </span><input type="text" name="uname" value="{$formdata['uname']}" style="width:200px;"/><span style="margin-left:10px;">密码: </span><input type="text" name="pwd" value="{$formdata['pwd']}" /><font class="important">可选，如果不需要留空即可</font>
		</div>
	</li>
	<!-- 
	<li class="i">
		<div class="form_ul_div clear">
			<span  class="title">启用: </span><input type="checkbox" name="status" size="4" value="1" {if $formdata['status']}checked="checked"{/if}/>
		</div>
	</li> 
	-->
</ul>
			 </section>
		</div>
	</div>
<input type="hidden" name="a" value="{$a}" />
{if $a== 'update'}
<input type="hidden" name="id" value="{$id}" />
{/if}
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />

</form>

{template:unit/publish_for_form}
<script>
	$(function() {
		var pub = $("#form_publish").css('position', 'absolute');
		var cur = null;
	
	    $('body').on('change', 'select[name="val_type[]"]', function(event){
	        if ( $(this).val() != 1 ) {
	       		close();
	        } else {
	        	openFor($(this));
	        }
	    });
	    
	    function openFor(el) {
	    	pub.css({top: el.offset().top - 100});	
	        cur = el;
	        var ids = cur.next().val();
	        if (ids) {
	        	ids = ids.split(',');
	        	var data = [];
	        	ids.forEach(function(id) {
	        		data.push({
	        			id: id,
	        			name: id,
	        			showName: id
	        		});
	        	});
	        	pub.find('.publish-box').data('publish').addResult(data, {reset: true});
	        }
	    }
	    
	    function close() {
	    	pub.css({top: -450});
	       	cur = null;
	    }
	    $('body').on('focus', 'input[name="value[]"]', function(event){
	        if ( $(this).prev().val() == 1 ) {
	        	openFor( $(this).prev() );
	        }
	    });
	    
	    pub.on('click', '.publish-box-close', close);
	    
	    pub.find('.publish-box').hg_publish({
	    	change: function () {
        		var names = $('.publish-name-hidden', pub).val();
        		var ids = $('.publish-hidden', pub).val();
        		cur && cur.next().val(ids);
	    	},
	    	maxColumn: 3
	    });
	});
</script>
{template:foot}