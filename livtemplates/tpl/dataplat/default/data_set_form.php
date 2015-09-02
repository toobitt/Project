{template:head}
{js:jqueryfn/jquery.tmpl.min}
{css:ad_style}
{code}
	//hg_pre($formdata);
	$allConfigs = $allConfigs[0];
{/code}
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
	function addconfig()
	{
		var div = "<div class='form_ul_div clear'> <span class='title'>前置导入：</span><select name='config_fields[]'>{if !empty($allConfigs)}{foreach $allConfigs as $kk=>$vv}{if $kk != $formdata['id']}<option  value ='{$kk}'>{$vv}</option>{/if}{/foreach}{/if}</select>&nbsp; <span>字段</span> <input type='text' name='import_fields[]' style='width:90px;' class='title'><span>默认值</span> <input type='text' name='default_fields[]' style='width:90px;' class='title'><span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' onclick='hg_delDom(this);' style='display: inline; '></span></span></div>";
		$('#addConfig').append(div);
		hg_resize_nodeFrame();
	}
	function hg_addDom()
	{
		var div = "<div class='form_ul_div clear' id='1234'> <span class='title'>映射：</span> <input type='text' name='source_fields[]' style='width:90px;' class='title'> <span>=></span> <input type='text' name='detin_fields[]' style='width:90px;' class='title'><span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' onclick='hg_delDom(this);' style='display: inline; '></span></span></div>";
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

	$(function(){
		$('.config-list').on( 'click', '.add-param-btn' ,function(){
			var info = {},
				box = $(this).closest( '.form_ul' ).find( '.param-list-area' );
			info.type = $(this).attr('_type');
			info.key = $(this).attr( '_id' );
			$('#add-param-tpl').tmpl( info ).appendTo( box );
		} );
	});
</script>
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
<h2>{$optext}配置</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div">
	<span  class="title">名称：</span><input type="text" value='{$formdata["title"]}' name='title'>
	</div>
</li>

<li class="i">
<div  class="form_ul_div clear"><span class="title">数据库：</span>
{code}
		$dbtype_css = array(
		'class' => 'down_list i',
		'show' => 'dbtype_show',
		'width' => 80,	
		'state' => 0, 
		'is_sub'=>1,
	);
	$formdata['dbtype'] = $formdata['dbtype'] ? $formdata['dbtype'] : 'mysql';
{/code}
{template:form/search_source,dbtype,$formdata['dbtype'],$_configs['dbtype'],$dbtype_css}
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title overflow">ip：</span><input type="text" name="dbhost" value="{$formdata['dbinfo']['host']}"><br>
<span class="title overflow">用户</span><input type="text" name="dbuser" value="{$formdata['dbinfo']['user']}"><br>
<span class="title overflow">密码</span><input type="text" name="dbpass" value="{$formdata['dbinfo']['pass']}"><br>
<span class="title overflow">db</span><input type="text" name="db" value="{$formdata['dbinfo']['database']}"><br>
<span class="title overflow">编码</span><input type="text" name="charset" value="{$formdata['dbinfo']['charset']}"><br>
<span class="title overflow">端口</span><input type="text" name="port" value="{$formdata['dbinfo']['port']}">
</div>
</li>
<li class="i">
<div class="form_ul_div"><span class="title overflow">主键：</span><input type="text" name="primary_key" value="{$formdata['primarykey']}" style="width:175px;">
</div>
</li>
<li class="i">
<div class="form_ul_div"><span class="title">sql：</span><textarea name="sql" style="width:300px;height:50px;">{$formdata['sql']}</textarea>
<font class="important" style="color:red">SQL结尾请勿添加分号;</font>
</div>
</li>
<li class="i">
<div class="form_ul_div"><span class="title overflow">sql主键：</span><input type="text" name="sql_primary_key" value="{$formdata['sqlprimarykey']}" style="width:175px;">
<font class="important" style="color:red">格式由sql语句决定</font>
</div>
</li>
<li class="i">
<div class="form_ul_div"><span class="title">数据处理：</span><textarea name="datadeal" style="width:300px;height:50px;">{$formdata['datadeal']}</textarea>
</div>
</li>
<li class="i">
		<div class="form_ul_div clear">
		{foreach $formdata['paras'] as $key => $val}
			<div class="form_ul_div clear" id="">	
				<span class="title">映射：</span>
					<input type="text" name="source_fields[]" style="width:90px;" class="title" value="{$key}">
				<span>＝></span>
					<input type="text" name="detin_fields[]" style="width:90px;" class="title" value="{$val}">
				 <span class='option_del_box'>
				 	<span name='option_del[]' class='option_del' title='删除' data-save="1" onclick='hg_delDom(this);' style='display: inline; '></span>
				 </span>
			</div>
		{/foreach}
			<div id="addArgument"></div>
			<div class="form_ul_div clear">
				<span id="pa_1" type="text" style="cursor:pointer;padding: 5px 20px;margin-left: 75px;
					background-color: #5B5B5B;color: white;border-radius: 2px;" onclick="hg_addDom();">添加参数</span>
			</div>
		</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title overflow">host：</span><input type="text" name="apiurlhost" value="{$formdata['apiurl']['host']}"><br>
<span class="title overflow">dir</span><input type="text" name="apiurldir" value="{$formdata['apiurl']['dir']}"><br>
<span class="title overflow">文件名</span><input type="text" name="apiurlfilename" value="{$formdata['apiurl']['filename']}"><br>
<span class="title overflow">方法名</span><input type="text" name="apiurla" value="{$formdata['apiurl']['a']}"><br>
</div>
</li>
<li class="i">
<div class="form_ul_div clear"><span class="title">频率：</span><input type="text" name="freq" value="{$formdata['count']}"/></label>
</div>
</li>
<li class="i">
<div class="form_ul_div clear"><span class="title">是否停止：</span><input type="checkbox" class="n-h" name="status" {if $formdata['status']}checked="checked"{/if} value="1"></label>
</div>
</li>

<li class="i">
		<div class="form_ul_div clear">
		{foreach $formdata['beforeimport'] as $key => $val}
			<div class="form_ul_div clear">	
					<span class="title">前置导入：</span>
						<select name='config_fields[]'>
							{if !empty($allConfigs)}
							{foreach $allConfigs as $kk=>$vv}
							{if $kk != $formdata['id']}
							<option {if $val['id']==$kk} selected='selected'{/if} value ='{$kk}'>{$vv}</option>
							{/if}
							{/foreach}
							{/if}
						</select>&nbsp;
					<span>字段</span>
					<input type="text" name="import_fields[]" style="width:90px;" class="title" value="{$val['field']}">
				<span>默认值</span>
					<input type="text" name="default_fields[]" style="width:90px;" class="title" value="{$val['default']}">
				 <span class='option_del_box'>
				 	<span name='option_del[]' class='option_del' title='删除' data-save="1" onclick='hg_delDom(this);' style='display: inline; '></span>
				 </span>
			</div>
		{/foreach}
			<div id="addConfig"></div>
			<div class="form_ul_div clear">
				<span id="addSetting" style="cursor:pointer;padding: 5px 20px;margin-left: 75px;width:55px;
					background-color: #5B5B5B;color: white;border-radius: 2px;" onclick="addconfig()" >前置导入</span>
			</div>
		</div>
</li>
</ul>

<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="html" value="true" />
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