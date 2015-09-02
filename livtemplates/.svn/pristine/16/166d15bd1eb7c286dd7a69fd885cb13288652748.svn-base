<?php 
/* $Id: stream_form.php 2361 2011-10-28 09:56:50Z ayou $ */
?>
{template:head}
{css:ad_style}
{css:bigcolorpicker}
{css:member_form}
{css:member_configuration}
{js:jqueryfn/jquery.tmpl.min}
{js:bigcolorpicker}
{js:area}

{if $a}
	{code}
		$action = $a;
	{/code}
{/if}
{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;
						
		{/code}
	{/foreach}
{/if}
{code}//print_r($credits);{/code}
<script>
$.globaldefault = {code} echo json_encode($formdata['field_default']);{/code}
</script>
<div class="wrap clear">
<div class="ad_middle" style="width:850px">
{if $formdata}
<form name="editform" action="" method="post" enctype='multipart/form-data' class="ad_form h_l">
<h2>{$optext}自定义积分规则</h2>
<ul class="form_ul">
<li class="i">
	<span class="basic-configuration">选择应用及规则:</span>
	<div class="continuous-sign">
		<div class="item">
			<div class="configuration">
			<select id="application" name="app_uniqueid" {if $id}disabled="disabled"{/if} style="width: 100px">
			<option value=''>-请选择应用-</option>
			{if $setApp&&is_array($setApp)&&!$id}
			{foreach $setApp as $k => $v}
			<option _id="{$k}" {if $appid == $k}selected="selected"{/if} value={$k}>-{$v['appname']}-</option>
			{/foreach}
			{elseif $id}
			<option _id="{$appid}" selected="selected" value={$appid}>-{$appname}-</option>
			{/if}
			</select>
			<select id="rules" name="operation" {if $id}disabled="disabled"{/if} style="width: 100px">
			<option value=''>-请选择规则-</option>
			{if $id}
			<option value={$operation} selected="selected">-{$rulename}-</option>
			{/if}
			</select>
			<input type="text" id="rulestatus" name="rulestatus" value="{if $id}积分规则:{$rulename}{if $opened}已开启{else}已关闭,开启此积分规则自定义才有效{/if}{else}这里显示规则状态{/if}" size="30" style="width:300px;" disabled='disabled'>
			</div>
		</div>
	</div>
</li>
<ul id="rulesdiyconfig">
<li class="i">
	<span class="basic-configuration">规则名称:</span>
	<div class="continuous-sign">
						<div class="item">
			<span class="isdiyname">是否配置名称:</span>
			<div class="configuration mar20">
				<div class="type">
					<input type="radio" name="isdiyname" class="no" value="0" id="nodiyname" {if !$isdiyname}checked{/if}/>
					<label for="nodiyname">继承</label>
				</div>
				<div class="type">
					<input type="radio" name="isdiyname" class="on" value="1" id="yesdiyname" {if $isdiyname}checked{/if}/>
					<label for="yesdiyname">配置</label>
				</div>
			</div>
		</div>
		<div id="diyname">
			<div class="item">
			<span class="diyname">自定义名称:</span>
			<div class="configuration mar20">
			<input type="text" name="credits_rules_diy[rname]"  value="{$rules['rname']}" style="width:120px;"/>
			</div>
			</div>
		</div>
		
	</div>
</li>
<li class="i">
	<span class="basic-configuration">关闭功能:</span>
	<div class="continuous-sign">
			<div class="item">
			<span class="isopened">是否配置关闭:</span>
			<div class="configuration mar20">
				<div class="type">
					<input type="radio" name="isopened" class="on" value="0" id="yesisopened" {if !$isopened}checked{/if}/>
					<label for="yesisopened">继承</label>
				</div>
				<div class="type">
					<input type="radio" name="isopened" class="no" value="1" id="noisopened" {if $isopened}checked{/if}/>
					<label for="noisopened">配置</label>
				</div>
			</div>
		</div>
		<div id="diyopened">
			<div class="item">
			<span class="diyname">自定义开关:</span>
			<div class="configuration mar20">
				<div class="type">
					<input type="radio" name="credits_rules_diy[opened]" class="no" value="0" id="noopened" {if isset($rules['opened'])&&!$rules['opened']}checked{/if}/>
					<label for="noopened">关闭</label>
				</div>
				<span class="error" id="title_tips" style="display:block;">*选择继承则使用原规则设置，关闭只针对此应用关闭积分规则</span>
			</div>
		</div>
		</div>
	</div>
</li>
<li class="i">
	<span class="basic-configuration">周期级别:</span>
	<div class="continuous-sign">
				<div class="item">
			<span class="isdiylevel">是否配置级别:</span>
			<div class="configuration mar20">
				<div class="type">
					<input type="radio" name="isdiylevel" class="no" value="0" id="nodiylevel" {if !$isdiylevel}checked{/if}/>
					<label for="nodiylevel">继承</label>
				</div>
				<div class="type">
					<input type="radio" name="isdiylevel" class="on" value="1" id="yesdiylevel" {if $isdiylevel}checked{/if}/>
					<label for="yesdiylevel">配置</label>
				</div>
			</div>
		</div>
			<div id="diylevel">
			<div class="item">
			<span class="diylevel">自定义级别:</span>
			<div class="configuration mar20">
				{foreach $_configs['RulesCycleLevel'] as $k=>$v}
				<div class="type">
					<input type="radio" name="credits_rules_diy[cyclelevel]" class="no" value="{$k}" id="nocyclelevel{$k}" {if ($k==$rules['cyclelevel'])}checked{/if}/>
					<label for="nocyclelevel{$k}">{$v['name']}</label>
				</div>
				{/foreach}
				<span class="error" id="title_tips" style="display:block;">*选择继承则使用原规则设置,其它选项含义请参照积分规则配置页说明</span>
			</div>
		</div>
		</div>
	</div>
</li>

<li class="i">
	<span class="basic-configuration">周期类型:</span>
	<div class="continuous-sign">
					<div class="item">
			<span class="isdiyname">是否配置周期:</span>
			<div class="configuration mar20">
				<div class="type">
					<input type="radio" name="isdiycycletype" class="no" value="0" id="nodiycycletype" {if !$isdiycycletype}checked{/if}/>
					<label for="nodiycycletype">继承</label>
				</div>
				<div class="type">
					<input type="radio" name="isdiycycletype" class="on" value="1" id="yesdiycycletype" {if $isdiycycletype}checked{/if}/>
					<label for="yesdiycycletype">配置</label>
				</div>
			</div>
		</div>
		<div id="diycycletype">
			<div class="item">
			<span class="diycycletype">自定义周期:</span>
			<div class="configuration mar20">
				{foreach $_configs['cycletype'] as $cycletype_id=>$cycletype_name}
				<div class="type">
					<input type="radio" name="credits_rules_diy[cycletype]" class="no" value="{$cycletype_id}" id="nocycletype{$cycletype_id}" {if ($cycletype_id==$rules[cycletype])}checked{/if}/>
					<label for="nocycletype{$cycletype_id}">{$cycletype_name}</label>
				</div>
				{/foreach}
				<span class="error" id="title_tips" style="display:block;">*选择继承则使用原规则设置</span>
			</div>
		</div>
			<div id="diycycletime">
			<div class="item">
			<span class="cycletime">自定义间隔时间:</span>
			<div class="configuration mar20">
			<input type="text" name="credits_rules_diy[cycletime]"  value="{$rules[cycletime]}" style="width:80px;"/>
			<span class="error" id="title_tips" style="display:block;">*此配置解释请参照积分规则配置页</span>
			</div>
			</div>
			</div>
			<div id="diyrewardnum">
			<div class="item">
			<span class="rewardnum">自定义奖励次数:</span>
			<div class="configuration mar20">
			<input type="text" name="credits_rules_diy[rewardnum]"  value="{$rules[rewardnum]}" placeholder="周期内最多奖励次数，0为不限制" style="width:80px;"/>
			<span class="error" id="title_tips" style="display:block;">*此配置解释请参照积分规则配置页</span>
			</div>
			</div>
			</div>
		</div>
		
	</div>
</li>

<li class="i">
	<span class="basic-configuration">规则奖励:</span>
	<div class="continuous-sign">
				{if is_array($get_credit_type)}
				{foreach $get_credit_type as $key => $value}
							<div class="item">
			<span class="timeopen">是否配置{$value['title']}:</span>
			<div class="configuration mar20">
				<div class="type">
				{code} $valname = 'isdiy'.$value['db_field'];{/code}
					<input type="radio" name="{$valname}" class="no" value="0" id="nodiy{$value['db_field']}" {if !$$valname}checked{/if}/>
					<label for="nodiy{$value['db_field']}">继承</label>
				</div>
				<div class="type">
					<input type="radio" name="{$valname}" class="on" value="1" id="yesdiy{$value['db_field']}" {if $$valname}checked{/if}/>
					<label for="yesdiy{$value['db_field']}">配置</label>
				</div>
			</div>
		</div>
		<div id="diy{$value['db_field']}">
		<div class="item">
			<div class="configuration">
				<div class="type">
					<span class="title configuration-title">自定义{$value['title']}: </span>
					<input type="text" name="credits_rules_diy[{$value['db_field']}]"  value="{$rules[$value['db_field']]}" style="width:50px;" />
				</div>
				
			</div>
		</div>
		</div>
			{/foreach}
			{/if}
	</div>
</li>
</ul>
</ul>
<input type="hidden" name="a" value="{$action}" />
<input type="hidden" name="is_del" id="is_del" value="0" />
<input type="hidden" name="isdiyrules" id="isdiyrules" value="0" />
<input type="hidden" name="{$primary_key}" value="{$formdata['id']}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<div class="temp-edit-buttons">
<input type="submit" name="sub" value="{$optext}" class="edit-button submit"/>
<input type="button" value="取消" class="edit-button cancel" onclick="javascript:history.go(-1);"/>
</div>
</form>
{else}
<div style="font-size:20px;color:red;padding: 30px;"> 没有数据 </div>
{/if}

</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>注释:<br/>1.选择继承则使用原规则属性<br/>2.配置则使用新配置的规则属性</div>
</div>
{template:foot}
<script type="text/x-jquery-tmpl" id="option-tpl">
<div class="item">
	<span class="lastedop">第<a class="index">{{= index}}</a>天:</span>
	<div class="configuration mar20">
	{if is_array($get_credit_type)}
	{foreach $get_credit_type as $key => $value}
	<div class="type">
		<span class="title configuration-title">{$value['title']}: </span>
		<input type="text" name="credits_lastedop[{$value['db_field']}][]"  value="" style="width:50px;" />
	</div>
	{/foreach}
	{/if}
	</div>
	<p class="btn add">+</p>
	<p class="btn delete">x</p>
</div>
</script>
<script type="text/javascript">
$(function(){
	var isdiyname = Number($('input[name="isdiyname"]:checked').val());
	if(!isdiyname)
	{
		$('#diyname').toggle();
		$('input[name="credits_rules_diy[rname]"]').attr("disabled","disabled");
	}

	var isopened = Number($('input[name="isopened"]:checked').val());
	if(!isopened)
	{
		$('#diyopened').toggle();
		$('input[name="credits_rules_diy[opened]"]').attr("disabled","disabled");
	}
	
	var isdiylevel = Number($('input[name="isdiylevel"]:checked').val());
	if(!isdiylevel)
	{
		$('#diylevel').toggle();
		$('input[name="credits_rules_diy[cyclelevel]"]').attr("disabled","disabled");
	}
	
	var isdiycycletype = Number($('input[name="isdiycycletype"]:checked').val());
	if(!isdiycycletype)
	{
		$('#diycycletype').toggle();
		$('input[name="credits_rules_diy[cycletype]"]').attr("disabled","disabled");
	}

	var isdiycredit1 = Number($('input[name="isdiycredit1"]:checked').val());
	if(!isdiycredit1)
	{
		$('#diycredit1').toggle();
		$('input[name="credits_rules_diy[credit1]"]').attr("disabled","disabled");
	}

	var isdiycredit2 = Number($('input[name="isdiycredit2"]:checked').val());
	if(!isdiycredit2)
	{
		$('#diycredit2').toggle();
		$('input[name="credits_rules_diy[credit2]"]').attr("disabled","disabled");
	}

	var value = $('input[name="credits_rules_diy[cycletype]"]:checked').val();
	if(value!="2"&&value!="3") {
		$('#diycycletime').toggle();
		$('input[name="credits_rules_diy[cycletime]"]').attr("disabled","disabled");
	}
	if(value=="0"){
		$('#diyrewardnum').toggle();
		$('input[name="credits_rules_diy[rewardnum]"]').attr("disabled","disabled");
	}
	
	var appselect = $('#application').find("option:selected").val(),rulesselect = $('#rules').find("option:selected").val();
	if(!appselect || !rulesselect)/**判断应用或者规则下拉是否被选中，如果没选中隐藏配置表单*/
	{
		$('#rulesdiyconfig').hide();
	}
		$('input[name="isdiyname"]').on('click' , function(){
			var checked = $('input[name="isdiyname"]:checked').val(),
				obj = $('#diyname');
			if(checked==0){
				obj.hide();
				$('input[name="credits_rules_diy[rname]"]').attr("disabled","disabled");
				}
			else{ 
				obj.show();
				 $('input[name="credits_rules_diy[rname]"]').removeAttr("disabled");
			}
		});
		
		$('input[name="isopened"]').on('click' , function(){
			var checked = $('input[name="isopened"]:checked').val(),
				obj = $('#diyopened');
			if(checked==0){
				obj.hide();
				$('input[name="credits_rules_diy[opened]"]').removeAttr("checked");
				$('input[name="credits_rules_diy[opened]"]').attr("disabled","disabled");
				}
			else{ 
				obj.show();
				 $('input[name="credits_rules_diy[opened]"]').removeAttr("disabled");
			}
		});
		
		$('input[name="isdiylevel"]').on('click' , function(){
			var checked = $('input[name="isdiylevel"]:checked').val(),
				obj = $('#diylevel');
			if(checked==0){
				obj.hide();
				$('input[name="credits_rules_diy[cyclelevel]"]').attr("disabled","disabled");
				}
			else{ 
				obj.show();
				 $('input[name="credits_rules_diy[cyclelevel]"]').removeAttr("disabled");
			}
		});
	$('input[name="isdiycycletype"]').on('click' , function(){
		var checked = $('input[name="isdiycycletype"]:checked').val(),
			obj = $('#diycycletype');
			if(checked==0){
				obj.hide();
				$('input[name="credits_rules_diy[cycletype]"]').attr("disabled","disabled");
				var cycletypecheck = $('input[name="credits_rules_diy[cycletype]"]:checked').attr("value");
				  if(cycletypecheck=="1"||cycletypecheck=="4")
				  {
				   	 $('input[name="credits_rules_diy[rewardnum]"]').attr("disabled","disabled");
				  }
				  else if(cycletypecheck !="0") 
				  {
					 $('input[name="credits_rules_diy[cycletime]"]').attr("disabled","disabled");
					 $('input[name="credits_rules_diy[rewardnum]"]').attr("disabled","disabled");
				  }
				}
			else{ 
				obj.show();
				$('input[name="credits_rules_diy[cycletype]"]').removeAttr("disabled"); 
				var cycletypecheck = $('input[name="credits_rules_diy[cycletype]"]:checked').attr("value");
				  if(cycletypecheck=="1"||cycletypecheck=="4")
				  {
				   $('input[name="credits_rules_diy[rewardnum]"]').removeAttr("disabled");
				  }
				  else if(cycletypecheck !="0") 
					  {
					  $('input[name="credits_rules_diy[cycletime]"]').removeAttr("disabled");
					  $('input[name="credits_rules_diy[rewardnum]"]').removeAttr("disabled");
					  }
			}
	});

	$('input[name="isdiycredit1"]').on('click' , function(){
		var checked = $('input[name="isdiycredit1"]:checked').val(),
			obj = $('#diycredit1');
		if(checked==0){
			obj.hide();
			$('input[name="credits_rules_diy[credit1]"]').attr("disabled","disabled");
			}
		else{ 
			obj.show();
			 $('input[name="credits_rules_diy[credit1]"]').removeAttr("disabled");
		}
	});
	$('input[name="isdiycredit2"]').on('click' , function(){
		var checked = $('input[name="isdiycredit2"]:checked').val(),
			obj = $('#diycredit2');
		if(checked==0){
			obj.hide()
			$('input[name="credits_rules_diy[credit2]"]').attr("disabled","disabled");
			}
		else{ 
			obj.show();
			 $('input[name="credits_rules_diy[credit2]"]').removeAttr("disabled");
		}
	});

	  $('input[name="credits_rules_diy[cycletype]"]').click(function(){
			 var val = $(this).attr("value");
		  if(val=="1"||val=="4")
		  {
		   $("#diycycletime").hide();
		   $('input[name="credits_rules_diy[cycletime]"]').attr("disabled","disabled");
		   $("#diyrewardnum").show();
		   $('input[name="credits_rules_diy[rewardnum]"]').removeAttr("disabled");
		  }
		  else 
			  {
			  $('#diycycletime').show();
			  $('input[name="credits_rules_diy[cycletime]"]').removeAttr("disabled");
			  $('#diyrewardnum').show();
			  $('input[name="credits_rules_diy[rewardnum]"]').removeAttr("disabled");
			  }
			if(val=="0")
			{
			$('#diycycletime').hide();
			$('input[name="credits_rules_diy[cycletime]"]').attr("disabled","disabled");
			$('#diyrewardnum').hide();
			$('input[name="credits_rules_diy[rewardnum]"]').attr("disabled","disabled");
			}
		   });
	
});

	$('#application').change(function() {  
    $.ajax({
             type: "post",
             url: './run.php?mid=' + gMid + '&a=getNoSetDiyRules&ajax=1',
             data: {
            	 app_uniqueid:$(this).find("option:selected").attr('_id'),
             	},
             dataType: "json",
             success: function(data){
					 var citys = '<option value>' + '-请选择规则-' + '</option>'; 
            		 if(data['callback']){
						eval( data['callback'] );
						$('#rules').empty();   //清空resText里面的所有内容
						$('#rules').html(citys);
						$("#rulestatus").val("这里显示规则状态");
						return;
						}
                         $('#rules').empty();   //清空resText里面的所有内容
                         $.each(data, function(Index, rule){
                               citys += '<option _id="'+ Index +'" opened="'+ rule.opened +'" value="'+ Index +'">' + rule.rname + '</option>';
                         });
                         $('#rules').html(citys);
                         $("#rulestatus").val("这里显示规则状态");
                         $('#rulesdiyconfig').hide();
                         $("#isdiyrules").val(0);
                      }
         });
          });
	$('#rules').change(function() {  	
		var opened = $(this).find("option:selected").attr('opened'),
		rulename = $(this).find("option:selected").text(),
		ruleop = $(this).find("option:selected").val();
		if(opened == 1){
			$("#rulestatus").val("积分规则:"+rulename+"已开启");
			}else if(opened == 0){
				$("#rulestatus").val("积分规则:"+rulename+"已关闭,开启此积分规则自定义才有效");
    			}
			else {
				$("#rulestatus").val("这里显示规则状态");
				}
				if(ruleop){
					$("#isdiyrules").val(1);
					$('#rulesdiyconfig').show();
				}
				else{
					$("#isdiyrules").val(0);
					$('#rulesdiyconfig').hide();
					}
	          });
</script>

