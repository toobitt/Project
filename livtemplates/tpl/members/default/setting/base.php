<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">多用户系统：</span>
			<label><input type="radio" name="base[identifierUserSystem]" value="1" {if $settings['base']['identifierUserSystem'] == 1} checked="checked"{/if} />启用</label><label><input type="radio" name="base[identifierUserSystem]" value="0"{if $settings['base']['identifierUserSystem'] == 0} checked="checked"{/if} />关闭</label>
					<font class="important" style="color:red">是否启用多用户系统，启用后将强制检测用户系统是否存在</font>
		
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">会员注册状态：</span>
			<label><input type="radio" name="base[member_status]" value="1" {if $settings['base']['member_status'] == 1} checked="checked"{/if} />已审核</label><label><input type="radio" name="base[member_status]" value="0"{if $settings['base']['member_status'] == 0} checked="checked"{/if} />未审核</label>
					<font class="important" style="color:red">会员注册默认状态</font>
		
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">注册类型更正：</span>
			<label><input type="radio" name="base[autoRegReviseType]" value="1" {if $settings['base']['autoRegReviseType'] == 1} checked="checked"{/if} />开启</label><label><input type="radio" name="base[autoRegReviseType]" value="0"{if $settings['base']['autoRegReviseType'] == 0} checked="checked"{/if} />关闭</label>
					<font class="important" style="color:red">用户名类型注册自动更正，开启后，可根据用户传用户名来判断属于何种注册类型，除非客户端或者网页支持此功能，否则建议关闭</font>
		
		</div>
	</li>
		<li class="i">
		<div class="form_ul_div">
			<span  class="title">登陆类型更正：</span>
			<label><input type="radio" name="base[autoLoginReviseType]" value="1" {if $settings['base']['autoLoginReviseType'] == 1} checked="checked"{/if} />开启</label><label><input type="radio" name="base[autoLoginReviseType]" value="0"{if $settings['base']['autoLoginReviseType'] == 0} checked="checked"{/if} />关闭</label>
					<font class="important" style="color:red">用户名类型登陆自动更正，开启后，可根据用户传用户名来判断属于何种登陆类型，除非客户端或者网页支持此功能，否则建议关闭</font>
		
		</div>
	</li>
			<li class="i">
		<div class="form_ul_div">
			<span  class="title">登陆类型检测：</span>
			<label><input type="radio" name="base[checkLoginType]" value="0" {if $settings['base']['checkLoginType'] == 0} checked="checked"{/if} />开启</label><label><input type="radio" name="base[checkLoginType]" value="1"{if $settings['base']['checkLoginType'] == 1} checked="checked"{/if} />关闭</label>
					<font class="important" style="color:red">例如：手机号使用非手机类型标识登陆，邮箱使用非邮箱类型标识登陆等不合法用户名检测报错;</font>
		
		</div>
	</li>
</ul>
<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">注册按钮名称：</span>
			<input type="text" value="{$settings['base']['regConfig']['title']}" name='base[regConfig][title]' style="width:200px;">
			<font class="important" style="color:red">自定义注册按钮名称,留空则使用网页端或手机端默认名称</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">登陆按钮名称：</span>
			<input type="text" value="{$settings['base']['loginConfig']['title']}" name='base[loginConfig][title]' style="width:200px;">
			<font class="important" style="color:red">自定义登陆按钮名称,留空则使用网页端或手机端默认名称</font>
		</div>
	</li>
</ul>
<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">普通注册功能：</span>
			<label>
				<input type="radio" name="base[regConfig][close]" class="closeGeneral close" value="1" {if $settings['base']['regConfig']['close'] == 1} checked="checked"{/if} />
				关闭
			</label>
			<label>
				<input type="radio" name="base[regConfig][close]" class="closeGeneral open" value="0"{if $settings['base']['regConfig']['close'] == 0} checked="checked"{/if} />
				启用
			</label>
			<font class="important" style="color:red">关闭普通注册按钮后,用户将不能以M2O类型注册</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">普通注册链接：</span>
			<input type="text" {if $settings['base']['regConfig']['close'] == 0}readonly="readonly"{/if} value="{$settings['base']['regConfig']['url']}" name='base[regConfig][url]' style="width:200px;">
			<font class="important" style="color:red">关闭注册按钮后填写生效，需要跳转的链接</font>
		</div>
	</li>
	<li class="i item_i">
		<div class="form_ul_div">
			<span  class="title">M2O注册类型：</span>
			<label>
				<input type="radio" name="base[closeRegTypeSwitch][m2o]" class="radio closeType" value="1" {if $settings['base']['regConfig']['close'] == 0}disabled="disabled"{/if} {if $settings['base']['closeRegTypeSwitch']['m2o'] == 1} checked="checked"{/if} />
				关闭
			</label>
			<label>
				<input type="radio" name="base[closeRegTypeSwitch][m2o]" class="radio openType" value="0" {if $settings['base']['closeRegTypeSwitch']['m2o'] == 0} checked="checked"{/if} />
				启用
			</label>
			<font class="important" style="color:red">关闭M2O注册功能，开启后用户将不能以M2O类型进行注册</font>
		</div>
	</li>
	<li class="i item_i">
		<div class="form_ul_div">
			<span  class="title">手机注册类型：</span>
			<label>
				<input type="radio" name="base[closeRegTypeSwitch][shouji]" class="radio closeType" value="1" {if $settings['base']['regConfig']['close'] == 0}disabled="disabled"{/if} {if $settings['base']['closeRegTypeSwitch']['shouji'] == 1} checked="checked"{/if} />
				关闭
			</label>
			<label>
				<input type="radio" name="base[closeRegTypeSwitch][shouji]" class="radio openType" value="0" {if $settings['base']['closeRegTypeSwitch']['shouji'] == 0} checked="checked"{/if} />
				启用
			</label>
			<font class="important" style="color:red">关闭手机注册功能，开启后用户将不能以手机类型进行注册</font>
		
		</div>
	</li>
	<li class="i item_i">
		<div class="form_ul_div">
			<span  class="title">邮箱注册类型：</span>
			<label>
				<input type="radio" name="base[closeRegTypeSwitch][email]" class="radio closeType" value="1" {if $settings['base']['regConfig']['close'] == 0}disabled="disabled"{/if} {if $settings['base']['closeRegTypeSwitch']['email'] == 1} checked="checked"{/if} />
				关闭
			</label>
			<label>
				<input type="radio" name="base[closeRegTypeSwitch][email]" class="radio openType" value="0" {if $settings['base']['closeRegTypeSwitch']['email'] == 0} checked="checked"{/if} />
				启用
			</label>
			<font class="important" style="color:red">关闭邮箱注册功能，开启后用户将不能以邮箱类型进行注册</font>
		
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">限制Appid：</span>
			<input type="text" {if $settings['base']['regConfig']['close'] == 0}readonly="readonly"{/if} value="{$settings['base']['closeRegTypeSwitchAppid']}" name='base[closeRegTypeSwitchAppid]' style="width:200px;">
			<font class="important" style="color:red">此项控制关闭注册功能(含M2O,手机,邮箱)生效范围,留空则不限制,例如：只限制手机端，填写手机端APPID，多个以英文逗号分割</font>
		</div>
	</li>
</ul>
<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">普通登陆功能：</span>
			<label>
				<input type="radio" name="base[loginConfig][close]" class="closeGeneral close" value="1" {if $settings['base']['loginConfig']['close'] == 1} checked="checked"{/if} />
				关闭
			</label>
			<label>
				<input type="radio" name="base[loginConfig][close]" class="closeGeneral open" value="0" {if $settings['base']['loginConfig']['close'] == 0} checked="checked"{/if} />
				启用
			</label>
			<font class="important" style="color:red">关闭登录后则不能以M2O\UC\手机\邮箱类型登陆，需取消部分请参考下面配置</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">普通登陆链接：</span>
			<input type="text" {if $settings['base']['loginConfig']['close'] == 0}readonly="readonly"{/if} value="{$settings['base']['loginConfig']['url']}" name='base[loginConfig][url]' style="width:200px;">
			<font class="important" style="color:red">关闭后登陆框后，此项填写生效,链接可自定义</font>
		</div>
	</li>
	<li class="i item_i">
		<div class="form_ul_div">
			<span  class="title">M2O登陆类型：</span>
			<label>
				<input type="radio" name="base[closeLoginTypeSwitch][m2o]" class="radio closeType" value="1" {if $settings['base']['loginConfig']['close'] == 0}disabled="disabled"{/if} {if $settings['base']['closeLoginTypeSwitch']['m2o'] == 1} checked="checked"{/if} />
				关闭
			</label>
			<label>
				<input type="radio" name="base[closeLoginTypeSwitch][m2o]" class="radio openType" value="0"{if $settings['base']['closeLoginTypeSwitch']['m2o'] == 0} checked="checked"{/if} />
				启用
			</label>
			<font class="important" style="color:red">关闭M2O登陆功能，开启后用户将不能以M2O或者UC类型进行登陆</font>
		
		</div>
	</li>
	<li class="i item_i">
		<div class="form_ul_div">
			<span  class="title">手机登陆类型：</span>
			<label>
				<input type="radio" name="base[closeLoginTypeSwitch][shouji]" class="radio closeType" value="1" {if $settings['base']['loginConfig']['close'] == 0}disabled="disabled"{/if} {if $settings['base']['closeLoginTypeSwitch']['shouji'] == 1} checked="checked"{/if} />
				关闭
			</label>
			<label>
				<input type="radio" name="base[closeLoginTypeSwitch][shouji]" class="radio openType" value="0"{if $settings['base']['closeLoginTypeSwitch']['shouji'] == 0} checked="checked"{/if} />
				启用
			</label>
			<font class="important" style="color:red">关闭手机登陆功能，开启后用户将不能以手机类型进行登陆</font>
		
		</div>
	</li>
	<li class="i item_i">
		<div class="form_ul_div">
			<span  class="title">邮箱登陆类型：</span>
			<label>
				<input type="radio" name="base[closeLoginTypeSwitch][email]" class="radio closeType" value="1" {if $settings['base']['loginConfig']['close'] == 0}disabled="disabled"{/if} {if $settings['base']['closeLoginTypeSwitch']['email'] == 1} checked="checked"{/if} />
				关闭
			</label>
				<label><input type="radio" name="base[closeLoginTypeSwitch][email]" class="radio openType" value="0"{if $settings['base']['closeLoginTypeSwitch']['email'] == 0} checked="checked"{/if} />
				启用
			</label>
			<font class="important" style="color:red">关闭邮箱登陆功能，开启后用户将不能以邮箱类型进行登陆</font>
		</div>
	</li>
		</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">限制Appid：</span>
			<input type="text" {if $settings['base']['loginConfig']['close'] == 0}readonly="readonly"{/if} value="{$settings['base']['closeLoginTypeSwitchAppid']}" name='base[closeLoginTypeSwitchAppid]' style="width:200px;">
			<font class="important" style="color:red">此项控制关闭登陆功能(含M2O,手机,邮箱)生效范围,留空则不限制,例如：只限制手机端，填写手机端APPID，多个以英文逗号分割</font>
		</div>
	</li>
</ul>
<ul class="form_ul">
			<li class="i">
		<div class="form_ul_div">
			<span  class="title">星星基数：</span>
			<input type="text" value="{$settings['base']['showstars']}" name='base[showstars]' style="width:200px;">
			<font class="important" style="color:red">例如:填N,N个星星为1个月亮,N个月亮为1个太阳</font>
		</div>
	</li>
			<li class="i">
		<div class="form_ul_div">
			<span  class="title">总积分计算方案：</span>
			按货币单位计算<input type="radio" value=1 name='define[CREDITS_PLAN]' {if $settings['define']['CREDITS_PLAN']==1}checked=checked{/if}/>
			按等级体系计算<input type="radio" value=2 name='define[CREDITS_PLAN]' {if $settings['define']['CREDITS_PLAN']==2}checked=checked{/if}/>
			按所有积分之和计算<input type="radio" value=0 name='define[CREDITS_PLAN]' {if empty($settings['define']['CREDITS_PLAN'])}checked=checked{/if}/>
			<font class="important" style="color:red">选择方案后请勿随意更改，否则会导致会员统计积分字段数据错乱</font>
		</div>
	</li>
		<li class="i">
		<div class="form_ul_div">
			<span  class="title">注册验证码：</span>
						开启<input type="radio" value=1 name='define[IS_REGISTER_VERIFYCODE]' {if $settings['define']['IS_REGISTER_VERIFYCODE']}checked=checked{/if}/>
			关闭<input type="radio" value=0 name='define[IS_REGISTER_VERIFYCODE]' {if empty($settings['define']['IS_REGISTER_VERIFYCODE'])}checked=checked{/if}/>
		<font class="important" style="color:red">此处仅仅是接口开启，手机端临时关闭请在移动APP里传值设置is_mobile_verifycode ＝ 1，网页会员中心如需启用，请在系统设置应用->配置相应设置</font>
		</div>
	</li>
			<li class="i">
		<div class="form_ul_div">
			<span  class="title">登陆验证码：</span>
						开启<input type="radio" value=1 name='define[IS_LOGIN_VERIFYCODE]' {if $settings['define']['IS_LOGIN_VERIFYCODE']}checked=checked{/if}/>
			关闭<input type="radio" value=0 name='define[IS_LOGIN_VERIFYCODE]' {if empty($settings['define']['IS_LOGIN_VERIFYCODE'])}checked=checked{/if}/>
				<font class="important" style="color:red">此处仅仅是接口开启，手机端临时关闭请在移动APP里传值设置is_mobile_verifycode ＝ 1，网页会员中心如需启用，请在系统设置应用->配置相应设置</font>
		</div>
	</li>
				<li class="i">
		<div class="form_ul_div">
			<span  class="title">邮箱绑定验证：</span>
						开启<input type="radio" value=0 name='define[NO_VERIFY_EMAILBIND]' {if !$settings['define']['NO_VERIFY_EMAILBIND']}checked=checked{/if}/>
			关闭<input type="radio" value=1 name='define[NO_VERIFY_EMAILBIND]' {if $settings['define']['NO_VERIFY_EMAILBIND']}checked=checked{/if}/>
				<font class="important" style="color:red">开启后绑定邮箱需要验证通过才允许绑定,关闭无需验证邮箱直接绑定;此参数仅针对帐号注册功能、帐号完善功能有效</font>
		</div>
	</li>
				<li class="i">
		<div class="form_ul_div">
			<span  class="title">手机绑定验证：</span>
						开启<input type="radio" value=0 name='define[NO_VERIFY_MOBILEBIND]' {if !$settings['define']['NO_VERIFY_MOBILEBIND']}checked=checked{/if}/>
			关闭<input type="radio" value=1 name='define[NO_VERIFY_MOBILEBIND]' {if $settings['define']['NO_VERIFY_MOBILEBIND']}checked=checked{/if}/>
				<font class="important" style="color:red">开启后绑定手机需要验证通过才允许绑定,关闭无需验证手机直接绑定;此参数仅针对帐号注册功能、帐号完善功能有效</font>
		</div>
	</li>
		<li class="i">
		<div class="form_ul_div">
			<span  class="title">免登陆验证类型：</span>
			<input type="text" value="{$settings['base']['avoidLoginVerifyCode']}" name='base[avoidLoginVerifyCode]' style="width:200px;">
			<font class="important" style="color:red">请填写免除登陆验证码类型,多个英文逗号分割，例如：第三方帐号登陆不需要验证码，配置如：sina,qq</font>
		</div>
	</li>
				<li class="i">
		<div class="form_ul_div">
			<span  class="title">密码找回验证码：</span>
						开启<input type="radio" value=1 name='define[IS_RESETPASSWORD_VERIFYCODE]' {if $settings['define']['IS_RESETPASSWORD_VERIFYCODE']}checked=checked{/if}/>
			关闭<input type="radio" value=0 name='define[IS_RESETPASSWORD_VERIFYCODE]' {if empty($settings['define']['IS_RESETPASSWORD_VERIFYCODE'])}checked=checked{/if}/>
				<font class="important" style="color:red">此处仅仅是接口开启，手机端临时关闭请在移动APP里传值设置is_mobile_verifycode ＝ 1，网页会员中心如需启用，请在系统设置应用->配置相应设置</font>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">短信服务器：</span>
			开启<input type="radio" value="0" name='base[closesms]' {if $settings['base']['closesms']==0}checked=checked{/if}/>
			关闭<input type="radio" value="1" name='base[closesms]' {if $settings['base']['closesms']==1}checked=checked{/if}/>
			<input type="text" style="width:200px;" value="{$settings['base']['error_text']['closesms']}" name='base[error_text][closesms]'><font class="important" style="color:red">关闭短信服务器之后的用户提示</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">每日短信限制：</span>
			<input type="text" value="{$settings['define']['MAX_SENDSMS_LIMITS']}" name='define[MAX_SENDSMS_LIMITS]' style="width:20px;">
			
			<input type="text" value="{$settings['base']['error_text']['sms_max_limits']}" style="width:300px;" name='base[error_text][sms_max_limits]'><font class="important" style="color:red">每日发送短信最大限制，达到最大值之后的错误提示</font>
			
		</div>
	</li>
		<li class="i">
		<div class="form_ul_div">
			<span  class="title">验证码有效期：</span>
			<input type="text" style="width:40px" value="{$settings['define']['VERIFYCODE_EXPIRED_TIME']}" name='define[VERIFYCODE_EXPIRED_TIME]'>
			<font class="important" style="color:red">验证码的有效期，单位秒</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">用户名修改功能：</span>
			<label><input type="radio" name="define[ALLOW_UPDATE_MEMBERNAME]" value="1" {if $settings['define']['ALLOW_UPDATE_MEMBERNAME'] == 1} checked="checked"{/if} />开启</label><label><input type="radio" name="define[ALLOW_UPDATE_MEMBERNAME]" value="0"{if $settings['define']['ALLOW_UPDATE_MEMBERNAME'] == 0} checked="checked"{/if} />关闭</label>
					<font class="important" style="color:red">此功能仅在UCenter未启用且未同步数据的用户有效,否则设置无效</font>
		
		</div>
	</li>
		<li class="i">
		<div class="form_ul_div">
			<span  class="title">推广设备唯一性：</span>
			<label><input type="radio" name="define[SPREADDTONLY]" value="1" {if $settings['define']['SPREADDTONLY'] == 1} checked="checked"{/if} />开启</label><label><input type="radio" name="define[SPREADDTONLY]" value="0"{if $settings['define']['SPREADDTONLY'] == 0} checked="checked"{/if} />关闭</label>
					<font class="important" style="color:red">推广设备唯一，开启后，一个设备只能被推广1次！</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">UCenter功能：</span>
			开启<input type="radio" value="1" name='base[ucenter][open]' {if $settings['base']['ucenter']['open']}checked=checked{/if}/>
			关闭<input type="radio" value="0" name='base[ucenter][open]' {if !$settings['base']['ucenter']['open']}checked=checked{/if}/>
			<font class="important" style="color:red">开启之后uc的设置才有效</font>
		</div>
	</li>
		<li class="i">
		<div class="form_ul_div">
			<span  class="title">UCenter配置：</span>
			<font class="important" style="color:red">请先至UCenter用户中心进行添加应用配置，然后将配置信息复制到下面相应配置项</font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">UC_CONNECT：</span>
			<input type="text" value="{$settings['define']['UC_CONNECT']}" name='define[UC_CONNECT]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">UC_DBHOST：</span>
			<input type="text" value="{$settings['define']['UC_DBHOST']}" name='define[UC_DBHOST]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">UC_DBUSER：</span>
			<input type="text" value="{$settings['define']['UC_DBUSER']}" name='define[UC_DBUSER]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">UC_DBPW：</span>
			<input type="text" value="{$settings['define']['UC_DBPW']}" name='define[UC_DBPW]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">UC_DBNAME：</span>
			<input type="text" value="{$settings['define']['UC_DBNAME']}" name='define[UC_DBNAME]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">UC_DBCHARSET：</span>
			<input type="text" value="{$settings['define']['UC_DBCHARSET']}" name='define[UC_DBCHARSET]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">UC_DBTABLEPRE：</span>
			<input type="text" value="{$settings['define']['UC_DBTABLEPRE']}" name='define[UC_DBTABLEPRE]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">UC_DBCONNECT：</span>
			<input type="text" value="{$settings['define']['UC_DBCONNECT']}" name='define[UC_DBCONNECT]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">UC_KEY：</span>
			<input type="text" value="{$settings['define']['UC_KEY']}" name='define[UC_KEY]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">UC_API：</span>
			<input type="text" value="{$settings['define']['UC_API']}" name='define[UC_API]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">UC_CHARSET：</span>
			<input type="text" value="{$settings['define']['UC_CHARSET']}" name='define[UC_CHARSET]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">UC_IP：</span>
			<input type="text" value="{$settings['define']['UC_IP']}" name='define[UC_IP]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">UC_APPID：</span>
			<input type="text" value="{$settings['define']['UC_APPID']}" name='define[UC_APPID]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">UC_PPP：</span>
			<input type="text" value="{$settings['define']['UC_PPP']}" name='define[UC_PPP]' style="width:200px;">
			<font class="important" style="color:red"></font>
		</div>
	</li>
</ul>

<script type="text/javascript">
$(function(){
	var MC = $('.setting_form');
	MC
	.on('click' , '.closeGeneral' , function( event ){
		var self = $( event.currentTarget ),
			checked = JSON.parse( self.val() ),
			parent_ul = self.closest('.form_ul');
		doSelected( parent_ul , checked );
	})
	.on('click' , '.radio' , function( event ){
		var self = $( event.currentTarget ),
			parent = self.closest('.form_ul');
		var checked = parent.find('.item_i').map(function(){
			return $(this).find(':radio[checked="checked"]').val();
		}).get();
		var close = checked.every(function(elem){
			var e = JSON.parse(elem);
		    return e > 0;
		});
		var open = checked.every(function(elem){
			var e = JSON.parse(elem);
		    return e == 0;
		});
		if( close ){
			parent.find('li:eq(0)').find('.close').attr('checked' , true);
			parent.find('li:eq(0)').find('.open').removeAttr("checked");
		}
		if( open ){
			parent.find('li:eq(0)').find('.open').attr('checked' , true);
			parent.find('li:eq(0)').find('.close').removeAttr("checked");
			doSelected( parent , false);
		}
	});
	
	function doSelected( parent_ul , checked ){
		if( checked ){
			parent_ul.find('.closeType').attr('checked' , true ).attr('disabled' , false);
			parent_ul.find('.openType').removeAttr("checked");
			parent_ul.find('input[type="text"]').attr("readonly" , false);
		}else{
			parent_ul.find('.openType').attr( 'checked', true  ).attr('disabled' , false );
			parent_ul.find('input[type="text"]').attr("readonly" , true).val('');
			parent_ul.find('.closeType').removeAttr("checked").attr('disabled' , true );
		}
	};
});
</script>