{template:head}
{css:ad_style}
<style>
.form_ul_div.l input{float:left}
.form_ul .n-h{float:none}
.ad_middle table textarea{min-width:0;min-height: 0;}
.option_del_box{display:inline-block;width:16px;height:16px;cursor:pointer;float:right;top: 4px;}
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
	function hg_addDom()
	{
		var div = "<div class='form_ul_div clear' id='1234'> <span class='title'>参数名称：</span> <input type='text' name='argument[]' style='width:90px;' class='title'> <span>标识:</span> <input type='text' name='mark[]' style='width:90px;' class='title'> <span>字典:</span> <select name='dict[]'> <option value='0'>请选择</option> {if $_configs['gather_dict']}{foreach $_configs['gather_dict'] as $key=>$val}<option value='{$key}'>{$val}</option>{/foreach}{/if}</select> <span>值: </span> <input type='text' name='value[]' ><span> 添加方式: </span><select name='way[]'><option value='1'>字典匹配</option>	<option value='2'>用户自定义</option></select><span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' onclick='hg_delDom(this);' style='display: inline; '></span></span></div>";
		$('#addArgument').append(div);
		hg_resize_nodeFrame();
	}
	function hg_delDom(obj)
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
</script>

<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle" style="width: 800px">
<form class="ad_form h_l" action="" method="post" enctype="multipart/form-data"  id="content_form">
<h2>{$optext}{$formdata['app_name']}采集配置</h2>
<ul class="form_ul">
	
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">配置名称：</span>
			<input type="text" name="app_name" value="{$formdata['app_name']}"/>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">所属分类：</span>
			{code}
				$item_css = array(
					'class' => 'transcoding down_list',
					'show' => 'sort_item',
					'width' => 120,
					'state' => 0,
					'is_sub' => 1
				);
				$name = array();
				foreach ($sorts[0] as $k=>$v)
				{
					$name[$k] = $v;
				}
			{/code}
			{template:form/search_source,sort_id,$formdata['sort_id'],$name,$item_css}
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">请求方式：</span>
			{code}
				$type_css = array(
					'class' => 'transcoding down_list',
					'show' => 'type_item',
					'width' => 120,
					'state' => 0,
					'is_sub' => 1
				);
				$type = array(
					'1'=>'get',
					'2'=>'post',
				);
				$formdata['request_type'] = $formdata['request_type'] ? $formdata['request_type'] : 2;
			{/code}
			{template:form/search_source,request_type,$formdata['request_type'],$type,$type_css}
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">URL路径：</span>
			<div id="app_change" {if !$formdata['bundle']} style="display:none"{/if}>
				{code}
					$attr_app = array(
						'class' => 'transcoding down_list',
						'show'  => 'select_ap',
						'width' => 120,/*列表宽度*/
						'state' => 0,/*0--正常数据选择列表，1--日期选择*/
						'onclick' => 'change_module();' 
					);
					$apps = $apps[0];
					$apps['-1'] = "-请选择-";
					
					$bundle = $formdata['bundle'];
					$bundle = $bundle ? $bundle : -1;
				{/code}
				{template:form/search_source,bundle,$bundle,$apps,$attr_app}
				<font class="important">读取应用下文件</font>
			</div>
			<div id="default_change" {if $formdata['bundle']} style="display:none"{/if}>
				<div style="float: left">
					<input type="text" name="host" value="{$formdata['host']}" />/
					<input type="text" name="dir" value="{$formdata['dir']}" />
				</div>
				<font class="important">例如：localhost/public/api 127.0.0.1/public/api</font>
			</div>
			<div style="float: left;margin-left: 10px;margin-top: 3px;">
				<select name="type" onchange="change_type(this.value);">
					<option {if !$formdata['bundle']} selected='selected'{/if} value="default">默认</option>
					<option {if $formdata['bundle']} selected='selected'{/if} value="app">应用</option>
				</select>	
			</div>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">请求文件：</span>
			<input type="text" name="filename" value="{$formdata['filename']}" />
			方法名:
			<input type="text" name="funcname" size="20" value="{$formdata['funcname']}" />
			<font class="important" style="color:red">*方法名不填写默认为create</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">删除方法：</span>
			<input type="text" name="delete_funcname" value="{$formdata['delete_funcname']}" />
			<font class="important" style="color:red">*方法名不填写默认为delete</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">直接转发：</span>
			<input type="radio" name="is_relay" {if	$formdata['is_relay']} checked="checked" {/if} value='1' />&nbsp;是&nbsp; 
			<input type="radio" name="is_relay" {if	!$formdata['is_relay']} checked="checked" {/if} value='0' />&nbsp;否
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
		{foreach $formdata['parameter']['argument'] as $key => $val}
			{if($formdata['parameter']['argument'][$key]!=null)}
			<div class="form_ul_div clear" id="">	
				<span class="title">参数名称：</span>
					<input type="text" name="argument[]" style="width:90px;" class="title" value="{$val}">
				<span>标识:</span>
					<input type="text" name="mark[]" style="width:90px;" class="title" value="{$formdata['parameter']['mark'][$key]}">	
				<span>字典:</span>	
					<select name="dict[]" >	
						<option value="0">请选择</option>
						{if $_configs['gather_dict']}
						{foreach $_configs['gather_dict'] as $kk=>$vv}
						<option {if($formdata['parameter']['dict'][$key]==$kk)} selected="selected" {/if} value="{$kk}">{$vv}</option>	
						{/foreach}
						{/if}
					</select>
				<span>值: </span>
					<input type="text" name="value[]" value="{$formdata['parameter']['value'][$key]}">
				<span>添加方式: </span>		
					<select name="way[]" >	 		
						<option {if($formdata['parameter']['way'][$key]=='1')} selected="selected" {/if} value="1">字典匹配</option>			
						<option {if($formdata['parameter']['way'][$key]=='2')} selected="selected" {/if} value="2">用户自定义</option>		
					</select>	
				 <span class='option_del_box'>
				 	<span name='option_del[]' class='option_del' title='删除' data-save="1" onclick='hg_delDom(this);' style='display: inline; '></span>
				 </span>
			</div>
			{/if}
		{/foreach}
			<div id="addArgument"></div>
			<div class="form_ul_div clear">
				<span id="pa_1" type="text" style="cursor:pointer;padding: 5px 20px;margin-left: 75px;
					background-color: #5B5B5B;color: white;border-radius: 2px;" onclick="hg_addDom();">添加参数</span>
			</div>
		</div>
	</li>
</ul>

<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}配置" class="button_6_14"/>
<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>
{template:foot}