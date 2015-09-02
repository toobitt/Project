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
<h2>签到配置</h2>
<ul class="form_ul">
<li class="i">
	<span class="basic-configuration">基本奖励:</span>
	<div class="continuous-sign">
		<div class="item">
			<div class="configuration">
				{if is_array($get_credit_type)}
				{foreach $get_credit_type as $key => $value}
				<div class="type">
					<span class="title configuration-title">{$value['title']}: </span>
					<input type="text" name="credits_base[{$value['db_field']}]"  value="{$credits['base'][$value['db_field']]}" style="width:50px;" />
				</div>
					{/foreach}
				{/if}
			</div>
		</div>
	</div>
</li>
<li class="i">
	<span class="basic-configuration">额外奖励:</span>
	<div class="continuous-sign">
		<div class="item">
			<span class="lastedop">是否开启连续签到奖励:</span>
			<div class="configuration mar20">
				<div class="type">
					<input type="radio" name="is_lastedop" class="on" value="1" id="yes" {if $credits['lastedop']}checked{/if}/>
					<label for="yes">是</label>
				</div>
				<div class="type">
					<input type="radio" name="is_lastedop" class="no" value="0" id="no" {if !$credits['lastedop']}checked{/if}/>
					<label for="no">否</label>
				</div>
			</div>
		</div>
	<div class="configuration-info" style="{if $credits['lastedop']}display:block{/if}">
		<!--<div class="item">
				<span class="lastedop">最高奖励天数:</span>
				<div class="configuration mar20 ">
					{code}
						$length = count($credits['lastedop'])
					{/code}
					<input type="text" name="lastedop" value="{if $length}{$length}{/if}"/>
					<span class="most-day">天</span>
				</div>
		</div>-->
		<div class="lastedop-info">
		{if $credits['lastedop']}
		{foreach $credits['lastedop'] as $k => $v}
		<div class="item">
			<span class="lastedop">第<a class="index">{$k}</a>天:</span>
			<div class="configuration mar20">
				{if is_array($get_credit_type)}
				{foreach $get_credit_type as $key => $value}
				<div class="type">
					<span class="title configuration-title">{$value['title']}: </span>
					<input type="text" name="credits_lastedop[{$value['db_field']}][]"  value="{$v[$value['db_field']]}" style="width:50px;" />
				</div>
					{/foreach}
				{/if}
			</div>
			<p class="btn add">+</p>
			<p class="btn delete">x</p>
		</div>
		{/foreach}
		{else}
		<div class="item">
			<span class="lastedop">第<a class="index">1</a>天:</span>
			<div class="configuration mar20">
				{if is_array($get_credit_type)}
				{foreach $get_credit_type as $key => $value}
				<div class="type">
					<span class="title configuration-title">{$value['title']}: </span>
					<input type="text" name="credits_lastedop[{$value['db_field']}][]"  value="{$v[$value['db_field']]}" style="width:50px;" />
				</div>
					{/foreach}
				{/if}
			</div>
			<p class="btn add">+</p>
			<p class="btn delete">x</p>
		</div>
		{/if}
		</div>
		<div class="item">
			<span class="lastedop">最终奖励:</span>
			<div class="configuration mar20">
				{if is_array($get_credit_type)}
				{foreach $get_credit_type as $key => $value}
				<div class="type">
					<span class="title configuration-title">{$value['title']}: </span>
					<input type="text" name="credits_final[{$value['db_field']}][min]"  placeholder="最少" value="{$credits['final'][$value['db_field']]['min']}" style="width:50px;" />
					<span>--</span>
					<input type="text" name="credits_final[{$value['db_field']}][max]"  placeholder="最多" value="{$credits['final'][$value['db_field']]['max']}" style="width:50px;" />
				</div>
					{/foreach}
				{/if}
			</div>
		</div>
	</div>
		
</div>
</li>
<li class="i">
	<span class="basic-configuration">功能配置:</span>
	<div class="continuous-sign">
					<div class="item">
			<span class="clsmsg">签到关闭[提示]:</span>
			<div class="configuration mar20">
				<textarea name="clsmsg" id="clsmsg"  cols="45" rows="4" />{$clsmsg}</textarea>
			</div>
		</div><br/>
				<div class="item">
			<span class="notice">公告[支持html]:</span>
			<div class="configuration mar20">
				<textarea name="notice" id="notice"  cols="45" rows="4" />{$notice}</textarea>
			</div>
		</div>
						<div class="item">
			<span class="timeopen">是否开启签到时间限制:</span>
			<div class="configuration mar20">
				<div class="type">
					<input type="radio" name="is_timeopen" class="on" value="1" id="yes" {if $is_timeopen}checked{/if}/>
					<label for="yes">是</label>
				</div>
				<div class="type">
					<input type="radio" name="is_timeopen" class="no" value="0" id="no" {if !$is_timeopen}checked{/if}/>
					<label for="no">否</label>
				</div>
			</div>
		</div>
		<div id="limit_time">
			<div class="item">
			<span class="limit_time">开始时间:</span>
			<div class="configuration mar20">
	<input type="text" name="limit_time[start]"  value="{$limit_time['start']}" style="width:80px;"/>
			</div>
			</div>
			<div class="item">
			<span class="limit_time">结束时间:</span>
			<div class="configuration mar20">
			<input type="text" name="limit_time[end]"  value="{$limit_time['end']}" style="width:80px;"/>
			</div>
		</div>
		</div>
			<div class="item">
			<span class="qdxq">是否关闭签到心情:</span>
			<div class="configuration mar20">
				<div class="type">
					<input type="radio" name="is_qdxq" class="on" value="1" id="yes" {if $is_qdxq}checked{/if}/>
					<label for="yes">是</label>
				</div>
				<div class="type">
					<input type="radio" name="is_qdxq" class="no" value="0" id="no" {if !$is_qdxq}checked{/if}/>
					<label for="no">否</label>
				</div>
			</div>
		</div>
		<div class="item">
			<span class="todaysay">是否关闭今日最想说:</span>
			<div class="configuration mar20">
				<div class="type">
					<input type="radio" name="is_todaysay" class="on" value="1" id="yes" {if $is_todaysay}checked{/if}/>
					<label for="yes">是</label>
				</div>
				<div class="type">
					<input type="radio" name="is_todaysay" class="no" value="0" id="no" {if !$is_todaysay}checked{/if}/>
					<label for="no">否</label>
				</div>
			</div>
		</div>
				<div id="is_todaysayxt" class="item">
			<span class="is_todaysayxt">是否开启“今日最想说”选填:</span>
			<div class="configuration mar20">
				<div class="type">
					<input type="radio" name="is_todaysayxt" class="on" value="1" id="yes" {if $is_todaysayxt}checked{/if}/>
					<label for="yes">是</label>
				</div>
				<div class="type">
					<input type="radio" name="is_todaysayxt" class="no" value="0" id="no" {if !$is_todaysayxt}checked{/if}/>
					<label for="no">否</label>
				</div>
			</div>
		</div>
	</div>
</li>
</ul>
<input type="hidden" name="a" value="{$action}" />
<input type="hidden" name="is_del" id="is_del" value="0" />
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
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
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
	var is_todaysay = Number($('input[name="is_todaysay"]:checked').val());
	var is_timeopen = Number($('input[name="is_timeopen"]:checked').val());
	if(is_todaysay==1)
	$('#is_todaysayxt').toggle();
	if(is_timeopen==0)
		$('#limit_time').toggle();
		$('input[name="is_timeopen"]').on('click' , function(){
			var checked = $('input[name="is_timeopen"]:checked').val(),
				obj = $('#limit_time');
			checked==0 ? obj.hide() :  obj.show();
		});
	$('input[name="is_lastedop"]').on('click' , function(){
		var checked = $('.on').prop('checked'),
			obj = $('.configuration-info');
		checked ? obj.show() :  obj.hide();
	});
	
	$('.lastedop-info').on('click' , '.add' ,function(){
		var obj = $('.lastedop-info');
		$('#option-tpl').tmpl({index : 1}).appendTo(obj);
		index();
	});
	
	$('.lastedop-info').on('click' , '.delete' ,function(){
		var length = $('.lastedop-info').find('.item').length;
		if(length>1){
			var obj = $(this).closest('.item');
			obj.remove();
			index();
		}else{
			$(this).myTip({
				string : '已删至最后',
				delay: 1000,
				dtop : 0,
				dleft : 80,
			});
			return false;
		}
	});

	function index(){
		var obj = $('.lastedop-info').find('.item');
		$.each(obj , function(key , value){
			$(this).find('.index').text(key+1);
		})
	}
	
});
</script>

