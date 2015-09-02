<style type="text/css">
.form_ul_div input{vertical-align:middle;}
</style>
<script type="text/javascript">
function check_colation(type)
{
	if(type == 1)
	{
		$('#contribute_colation').show();
	}
	else
	{
		$('#contribute_colation').hide();
	}
}
</script>
<ul class="form_ul" style="margin-bottom:50px;text-align:left;" >
	<!--  
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">创建求助默认审核状态：</span>
			<input type="text" value="{$settings['define']['SEEKHELP_STATUS']}" name='define[SEEKHELP_STATUS]' style="width:200px;">
			<font class="important" style="color:red">0－未审核，1－已审核</font>
		</div>
	</li>
	-->
	<li class="i">
		<div class="form_ul_div">
		<span  class="title" style="width: 140px !important;margin-right:30px">求助默认是否通过审核:</span>
			含图和视频 : <label><input type="radio" name="define[SEEKHELP_MATERIAL_STATUS]" value="1"{if $settings['define']['SEEKHELP_MATERIAL_STATUS'] == 1} checked="checked"{/if} />是</label><label><input type="radio" name="define[SEEKHELP_MATERIAL_STATUS]" value="0"{if $settings['define']['SEEKHELP_MATERIAL_STATUS'] == 0} checked="checked"{/if} />否</label>
			不含图片和视频 : <label><input type="radio" name="define[SEEKHELP_STATUS]" value="1"{if $settings['define']['SEEKHELP_STATUS'] == 1} checked="checked"{/if} />是</label><label><input type="radio" name="define[SEEKHELP_STATUS]" value="0"{if $settings['define']['SEEKHELP_STATUS'] == 0} checked="checked"{/if} />否</label>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title" style="width: 150px !important;margin-right:30px;">创建评论默认审核状态：</span>
			<!--  <input type="text" value="{$settings['define']['SEEKHELP_COMMENT_STATUS']}" name='define[SEEKHELP_COMMENT_STATUS]' style="width:200px;"> -->
			<input type="radio" name="define[SEEKHELP_COMMENT_STATUS]" value="1"{if $settings['define']['SEEKHELP_COMMENT_STATUS'] == 1} checked="checked"{/if} />审核</label><label><input type="radio" name="define[SEEKHELP_COMMENT_STATUS]" value="0"{if $settings['define']['SEEKHELP_COMMENT_STATUS'] == 0} checked="checked"{/if} />未审</label>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title" style="width: 150px !important;margin-right:30px">是否查看其他机构数据：</span>
			<!--  <input type="text" value="{$settings['define']['SHOW_OTHER_DATA']}" name='define[SHOW_OTHER_DATA]' style="width:200px;"> -->
			<input type="radio" name="define[SHOW_OTHER_DATA]" value="1"{if $settings['define']['SHOW_OTHER_DATA'] == 1} checked="checked"{/if} />是</label><label><input type="radio" name="define[SHOW_OTHER_DATA]" value="0"{if $settings['define']['SHOW_OTHER_DATA'] == 0} checked="checked"{/if} />否</label>
			
			<font class="important" style="color:red"></font>
		</div>
	</li>
		<li class="i">
		<div class="form_ul_div">
			<span  class="title" style="width: 150px !important;margin-right:30px">是否隐藏部分手机号：</span>
			<!--  <input type="text" value="{$settings['define']['IS_HIDE_MOBILE']}" name='define[IS_HIDE_MOBILE]' style="width:200px;"> -->
			<input type="radio" name="define[IS_HIDE_MOBILE]" value="1"{if $settings['define']['IS_HIDE_MOBILE'] == 1} checked="checked"{/if} />是</label><label><input type="radio" name="define[IS_HIDE_MOBILE]" value="0"{if $settings['define']['IS_HIDE_MOBILE'] == 0} checked="checked"{/if} />否</label>
			
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title" style="width: 150px !important">创建机构时默认角色：</span>
			<input type="text" value="{$settings['define']['SEEKHELP_ROLE']}" name='define[SEEKHELP_ROLE]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title" style="width: 150px !important">创建机构时默认组织：</span>
			<input type="text" value="{$settings['define']['SEEKHELP_ORG']}" name='define[SEEKHELP_ORG]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
		<li class="i">
		<div class="form_ul_div">
			<span  class="title">会员：</span>
			<input type="radio" name="define[SEEKHELP_NEW_MEMBER]" value="1"{if $settings['define']['SEEKHELP_NEW_MEMBER'] == 1} checked="checked"{/if} />新会员</label><label><input type="radio" name="define[SEEKHELP_NEW_MEMBER]" value="0"{if $settings['define']['SEEKHELP_NEW_MEMBER'] == 0} checked="checked"{/if} />老会员</label>
		</div>
	</li>
	{if $settings['base']['App_banword']}
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">是否开启屏蔽字：</span>
			<input type="radio" name="define[IS_BANWORD]" value="1"{if $settings['define']['IS_BANWORD'] == 1} checked="checked"{/if} onclick="check_colation(1);"/>开启</label><label> <input type="radio" name="define[IS_BANWORD]" value="0"{if $settings['define']['IS_BANWORD'] == 0} checked="checked"{/if} onclick="check_colation(2);"/>关闭</label>
			
			<span {if !$settings['define']['IS_BANWORD']}style="display:none"{/if} id="contribute_colation" style="margin-left:20px">
			<span>处理方式: </span>
			<select name="define[COLATION_TYPE]">
			{foreach $settings['base']['contribute_colation'] as $key=>$val}
				<option value="{$key}" {if $settings['define']['COLATION_TYPE'] == $key}selected="selected"{/if}>{$val}</option>
			{/foreach}
			</select>
			</span>
			<font class="important" style="color:red">关闭默认不处理</font>
		</div>
	</li>
	{/if}
	<li class="i">
		<div class="form_ul_div">
			<span  class="title" style="width: 150px !important">节点返回数量：</span>
			<input type="text" value="{$settings['define']['NODE_COUNT']}" name='define[NODE_COUNT]' style="width:100px;">
			<font class="important" style="color:red">节点默认返回数量</font>
		</div>
	</li>
	
</ul>