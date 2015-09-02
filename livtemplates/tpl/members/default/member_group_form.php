<?php 
/* $Id: stream_form.php 2361 2011-10-28 09:56:50Z ayou $ */
?>
{template:head}
{css:ad_style}
{css:bigcolorpicker}
{css:member_form}
{js:jqueryfn/jquery.tmpl.min}
{js:bigcolorpicker}
{js:area}
{js:members/member_group}
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
{code}//print_r($showcredit);{/code}
<script>
$.globaldefault = {code} echo json_encode($formdata['field_default']);{/code}
</script>
<style>
.important{color:red;}
.img-box{display: -webkit-box;}
.img-box .img{position:relative;}
.img-box img{width:50px;height:50px;margin-right: 10px;}
.img-upload-btn{width:50px;height:50px;border:1px solid #ccc;text-align: center;font-size:30px;color:#ccc;cursor:pointer;}
.img-box .del-pic{display: block;width: 15px;height: 15px;text-align: center;line-height: 15px;background: #629ee7;color: #fff;border-radius: 50%;position: absolute;top: -7px;right: 4px;cursor:pointer;display:none;}
</style>
<div class="wrap clear">
<div class="ad_middle" style="width:850px">
<form name="editform" action="" method="post" enctype='multipart/form-data' class="ad_form h_l">
<h2>{$optext}会员组</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear info">
		<span class="title">组名称: </span>
		<input type="text" name="name"  value="{$name}" />
	</div>
	<div class="form_ul_div clear info">
		<span class="title">描述备注: </span><textarea name="description" id="description"  cols="45" rows="4" />{$description}</textarea>
	</div>
</li>

<li class="i option-type">
	<div class="form_ul_div clear pre-option">
		<span class="title">组类型: </span>
		<div style="width:335px;margin-left:85px;display: -webkit-box;">
			<ul class="type-choose">
			{foreach $_configs['updatetype'] as $updatetype_id=>$updatetype_name}
		         <li ><input type="radio" class="isupdate" name="isupdate" {if ($updatetype_id==$isupdate)} checked="checked"{/if}  value ="{$updatetype_id}" _val="{code} echo $updatetype_name;{/code}"><span>{$updatetype_name}</span></li>
		    {/foreach}
			</ul>
		</div>
	</div>
	<div id='credits' >
	<div class="form_ul_div clear info" >
		<span class="title">升级上限: </span>
		<input type="text" name="creditshigher"  value="{$creditshigher}" placeholder="升级此组需要的上限值" style="width:150px;"/>
	</div>
	{if $formdata['id']&&empty($isupdate)}
	<div class="form_ul_div clear info">
		<span class="title">升级下限: </span>
		<input type="text" name="creditslower"  value="{$creditslower}" placeholder="升级此组需要的下限值" style="width:150px;" disabled/>
	</div>
	{/if}
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear pre-option">
		<span class="title">积分规则: </span>
				<div style="width:335px;margin-left:85px;display: -webkit-box;">
				<ul class="type-choose">
		<li><input type="radio" name="showcredit" class="showcredit" {if  $showcreditdiy} checked="checked"{/if} value="1" /><span>是</span></li>
		<li><input type="radio" name="showcredit" class="showcredit" {if  empty($showcreditdiy) } checked="checked"{/if} value="0" /><span>否</span></li>
		</ul><span class="error" id="title_tips" style="display:block;">*是否自定义积分规则,此处自定义是在原有全局策略基础上增加积分(+10),或者减少积分(-10)</span>
	</div>
	<div class="form_ul_div clear pre-option">
		<span class="title">前台启用: </span>
				<div style="width:335px;margin-left:85px;display: -webkit-box;">
				<ul class="type-choose">
		<li><input type="radio" name="enable" class="enable" {if  $enable} checked="checked"{/if} value="1" /><span>是</span></li>
		<li><input type="radio" name="enable" class="enable" {if  empty($enable) } checked="checked"{/if} value="0" /><span>否</span></li>
	</div>
	</div>
		<div id='credits_rule_diy' >
		<div class="form_ul_div clear sort">		
		<span class="title" style="width: 80px;">积分规则选择:</span>
		 <select id="credits_rules_option">
		 <option value>请选择需要自定义项</option>
			{foreach $showcredit as $k=>$v}
			 {code}
		       $rname = $v['rname'];
		       $operation = $v['operation'];
		      {/code}
			{foreach $get_credits_type as $k=>$v}
			{code}
			 if($showcreditdiy[$operation]&&array_key_exists($v['db_field'],$showcreditdiy[$operation]['credits']))
		       {
		       	$rules_credit[$operation]=$rname;
		       	continue;
		       }
			$credits_type_title=$v['title'];
			$credits_type_db_field=$v['db_field'];
			{/code}
             <option  id="{$operation}_{$credits_type_db_field}" _value="{$operation}_{$credits_type_db_field}" value ="credits_rules_diy[{$operation}][{$credits_type_db_field}]">{code} echo $rname.':'.$credits_type_title;{/code}</option>
		     {/foreach}
		     {/foreach}
		   </select>
		</div>
			{foreach $showcreditdiy as $key => $val}
			{if $val['credits']&&is_array($val['credits'])}
			{foreach $val['credits'] as $keys => $vals}
			{foreach $get_credits_type as $k=>$v}
			{if $keys==$v['db_field']}
		<div id="{$key}_{$v['db_field']}_rules" _data="{$key}_{$v['db_field']}" data="credits_rules_diy[{$key}][{$v['db_field']}]" class="form_ul_div clear info rules_del" title="{$rules_credit[$key]}:{$v['title']}">	
		<span class="title">{$rules_credit[$key]}:{$v['title']}: </span>
		<input type="text" name="credits_rules_diy[{$key}][{$v['db_field']}]"  value="{$val['credits'][$v['db_field']]}" style="width:100px;"/>
		<em class="del-creditrules" title="删除"></em>
		</div>
		{/if}
		{/foreach}
		{/foreach}
		{/if}
		{/foreach}
		</div>
</li>
<li class="i">
	<div class="form_ul_div clear info">
		<span class="title">星星数: </span>
		<input type="text" name="starnum"  value="{$showstar['starnum']}" style="width:100px;"/>
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear info">
		<span class="title">会员名颜色: </span>
		<input type="text" name="usernamecolor"  value="{$usernamecolor}" style="width:60px;"/>
	</div>
</li>
<li class="i icon">
	<div class="form_ul_div clear">
				
		<span class="title" >组图标：</span>
			<div class="img-box">
					<div class="img">
							{if $icon}
								{code}$icon=hg_fetchimgurl($icon,'25','30');{/code}
								<img id="icon"  src="{$icon}">
							{/if}
							<span class="del-pic">x</span>
					</div>
						<input type="file" name="icon"   value="" style="display: none;" />
						<div class="img-upload-btn">+</div>
			</div>
			</div>
		</li>
{if $icon}<input type="hidden" class="icondel" name="icondel" value>{/if}
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">权限: </span>
		<div style="display: -webkit-box;">
			<ul class="type-choose">
			{foreach $member_purview as $key => $value}
		         {code}
		            $purviewid = $value['id'];
			        $purviewname = $value['pname'];
			        $flag = 0;
			        if(is_array($pid))
			        {
			            if (in_array($purviewid,$pid)) $flag=1;
			        }
			        else 
			        {
			            if($purviewid == $pid) $flag=1;
			        }
		         {/code}
		         <li><input type="checkbox" {if $flag} checked="checked"{/if} value="{$purviewid}" size="50" name="purview[]"/><span>{code}echo $purviewname;{/code}</span></li>
	        {/foreach}		
			</ul>
		</div>
	</div>
</li>
</ul>

<input type="hidden" name="a" value="{$action}" />
<input type="hidden" name="is_del" id="is_del" value="0" />
<input type="hidden" name="{$primary_key}" value="{$formdata['id']}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<div class="temp-edit-buttons">
<input type="submit" name="sub" value="{$optext}" class="edit-button submit"/>
<input type="button" value="取消" class="edit-button cancel" onclick="javascript:history.go(-1);"/>
</div>
</form>
</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}