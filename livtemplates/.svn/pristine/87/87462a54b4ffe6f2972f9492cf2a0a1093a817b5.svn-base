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
<ul class="form_ul" style="margin-bottom:50px;">
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">标注视频文件：</span>
			<input type="text" value="{$settings['define']['MANIFEST']}" name='define[MANIFEST]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">是否开启悬赏：</span>
			<input type="text" value="{$settings['define']['BOUNTY']}" name='define[BOUNTY]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	
	<!--  
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">地图默认地点：</span>
			<input type="text" value="{$settings['define']['DEFAULT_POSITION']}" name='define[DEFAULT_POSITION]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	-->
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">是否开启认领：</span>
			<input type="text" value="{$settings['define']['CLAIM']}" name='define[CLAIM]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">报料默认状态：</span>
			<input type="radio" name="define[CONTRIBUTE_AUDIT]" value="1"{if $settings['define']['CONTRIBUTE_AUDIT'] == 1} checked="checked"{/if} />待审核</label><label style="margin-left: 20px;"><input type="radio" name="define[CONTRIBUTE_AUDIT]" value="2"{if $settings['define']['CONTRIBUTE_AUDIT'] == 2} checked="checked"{/if} />已审核</label>
		</div>
	</li>
	{if $settings['base']['App_suobei']['is_open']}
	<li class="i" style="margin-top:30px;margin-bottom:120px;">
		<div class="form_ul_div">
			<span  class="title">&nbsp;&nbsp;&nbsp;索贝签发：</span>
			<!--  <label>是否打开</label>
			<input type="text" value="{$settings['base']['App_suobei']['is_open']}" name='base[video_type][is_open]' style="width:50px;" /><br/>
			-->
			<label style="margin-left:85px;">ftp配置</label>
			<input type="text" value="{$settings['base']['App_suobei']['ftp']['host']}" name='base[video_type][ftp][host]' style="width:100px;" />
			<input type="text" value="{$settings['base']['App_suobei']['ftp']['username']}" name='base[video_type][ftp][username]' style="width:100px;" />
			<input type="text" value="{$settings['base']['App_suobei']['ftp']['password']}" name='base[video_type][ftp][password]' style="width:100px;" /><br/>
			<label style="margin-left:85px;">display_name</label>
			<input type="text" value="{$settings['base']['App_suobei']['display_name']}" name='base[video_type][display_name]' style="width:100px;" /><br/>
			<label style="margin-left:85px;">xmldir</label>
			<input type="text" value="{$settings['base']['App_suobei']['xmldir']}" name='base[video_type][xmldir]' style="width:100px;" /><br/>
			<label style="margin-left:85px;">xmlpath</label>
			<input type="text" value="{$settings['base']['App_suobei']['xmlpath']}" name='base[video_type][xmlpath]' style="width:100px;" /><br/>
			<font class="important" style="color:red"></font>
		</div>
	</li>
	{/if}
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">会员：</span>
			<input type="radio" name="define[CONTRIBUTE_NEW_MEMBER]" value="1"{if $settings['define']['CONTRIBUTE_NEW_MEMBER'] == 1} checked="checked"{/if} />新会员</label><label><input type="radio" name="define[CONTRIBUTE_NEW_MEMBER]" value="0"{if $settings['define']['CONTRIBUTE_NEW_MEMBER'] == 0} checked="checked"{/if} />老会员</label>
		</div>
	</li>
		<li class="i">
		<div class="form_ul_div">
			<span  class="title">未审核增加积分：</span>
			<input type="radio" name="define[IS_CREDITS]" value="1"{if $settings['define']['IS_CREDITS'] == 1} checked="checked"{/if} />是</label><label><input type="radio" name="define[IS_CREDITS]" value="0"{if $settings['define']['IS_CREDITS'] == 0} checked="checked"{/if} />否</label>
		</div>
	</li>
		<li class="i">
		<div class="form_ul_div">
			<span  class="title">审核增加积分：</span>
			<input type="radio" name="define[IS_EXTRA_CREDITS]" value="1"{if $settings['define']['IS_EXTRA_CREDITS'] == 1} checked="checked"{/if} />是</label><label><input type="radio" name="define[IS_EXTRA_CREDITS]" value="0"{if $settings['define']['IS_EXTRA_CREDITS'] == 0} checked="checked"{/if} />否</label>
		</div>
	</li>
	{if $settings['base']['App_verifycode']}
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">是否开启验证码：</span>
			<input type="radio" name="define[IS_VERIFYCODE]" value="1"{if $settings['define']['IS_VERIFYCODE'] == 1} checked="checked"{/if} />是</label><label> <input type="radio" name="define[IS_VERIFYCODE]" value="0"{if $settings['define']['IS_VERIFYCODE'] == 0} checked="checked"{/if} />否</label>
		</div>
	</li>
	{/if}
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
			<span  class="title">地图默认地点：</span>
			<input type="text" value="{$settings['base']['areaname']}" name='base[areaname]' style="width:200px;">
			<font class="important" style="color:red">百度地图默认地点</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">地图坐标转换：</span>
			<input type="text" value="{$settings['define']['BAIDU_CONVERT_DOMAIN']}" name='define[BAIDU_CONVERT_DOMAIN]' style="width:200px;">
			<font class="important" style="color:red">百度地图坐标转换地址</font>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">上传图片数目：</span>
			<input type="text" value="{$settings['define']['UPLOAD_IMG_NUM']}" name='define[UPLOAD_IMG_NUM]' style="width:200px;">
			<font class="important" style="color:red">限制图片上传最大数目，不填默认不限制</font>
		</div>
	</li>
</ul>