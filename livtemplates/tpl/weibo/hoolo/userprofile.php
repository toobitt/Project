
{template:head/head_register_login}

    <div class="content clear">
  <div class="content_top"></div>
		<div class="content_middle clear">  
     	<!-- 导航按钮  -->

		{template:unit/userset}

    
      <p>以下信息将显示在你的<a>个人资料页</a>，方便大家了解你。&nbsp;&nbsp;&nbsp;&nbsp;<span class="red">*</span>为必填项</p>
 <table width="695" border="0" align="center" cellpadding="0" cellspacing="0" height="420" class="content_user_info">
  <tr>
    <td width="113" align="right" valign="middle"><span class="red">*</span>{$_lang['username']}：</td>
    <td width="320" align="left" valign="middle">
		<div class="biankuang">
      <input type="text" name="username" id="username" value="{$_userinfo['username']}" />
		</div>
	</td>
    <td width="262" align="left" valign="middle" id="info01" >&nbsp;</td>
  </tr>
  <tr>
    <td align="right" valign="middle">{$_lang['truename']}：</td>
    <td width="320" align="left" valign="middle">
		<div class="biankuang">
    <input type="text" name="truename" id="truename"  value="{$_userinfo['truename']}" />
		</div>
    </td>
    <td align="left" valign="middle" ><select id="pub_name" class="pub-option">
		{$_privacy[0]}
	</select></td>
  </tr>
  <tr>
    <td align="right" valign="middle"><span class="red">*</span>{$_lang['location']}：</td>
    <td width="320" align="left" valign="middle">
    	<select id='province' name='province'><option value='0'>{$_lang['choose']}</option></select>
		<select id='city' name='city'><option value='0'>{$_lang['choose']}</option></select>
		<select id='country' name='country'><option value='0'>{$_lang['choose']}</option></select>
    </td>
    <td align="left" valign="middle" class="color" id="info03" >&nbsp;</td>
  </tr>
  <tr>
    <td align="right" valign="middle"><span class="red">*</span>{$_lang['sex']}：</td>
	 <td width="320" height="40" align="left" valign="middle"  class="color"><input type="radio" name="sex" value="1" {if $_userinfo['sex']==1}{code} echo "checked";{/code}{/if} >{$_lang['male']}&nbsp;&nbsp;
		<input type="radio" name="sex" value="2" {if $_userinfo['sex']==2}{code} echo "checked";{/code}{/if}>{$_lang['female']}</td>
    <td align="left" valign="middle" class="color" id="info04" >&nbsp;</td>
  </tr>
  <tr>
    <td align="right" valign="middle">{$_lang['birthday']}：</td>
    <td width="320" align="left" valign="middle" class="color"><label for="year"></label>
      <select name="year" id="year">
      {$_birthday[0]}
      </select>
      年
      <label for="month"></label>
      <select name="month" id="month">
      {$_birthday[1]}
      </select>
      月
      <label for="date"></label>
      <select name="date" id="date">
      {$_birthday[2]}
      </select>
      日</td>
    <td align="left" valign="middle" class="color">
    	<select id="pub_birth" class="pub-option">
		{$_privacy[1]}
		</select>
	</td>
  </tr>
    <tr>
    <td align="right" valign="middle">{$_lang['email']}：</td>
    <td width="320" align="left" valign="middle">
		<div class="biankuang">
	<input type="text" name="email" id="email"  value="{$_userinfo['email']}" />
		</div>
	</td>
    <td align="left" valign="middle" class="color" >
		<select id="pub_email" class="pub-option">
		{$_privacy[2]}
		</select>	
		<span id="info06">
		</span>
	</td>
  </tr>
    <tr>
    <td align="right" valign="middle">QQ：</td>
    <td width="320" align="left" valign="middle">
		<div class="biankuang">
	<input type="text" name="qq" id="qq" value="{$_userinfo['qq']}" />
		</div>
	</td>
    <td align="left" valign="middle" class="color">
    	<select id="pub_qq" class="pub-option">
		{$_privacy[3]}
		</select>
	</td>
  </tr>
  <tr>
    <td align="right" valign="middle">MSN：</td>
    <td width="320" align="left" valign="middle">
		<div class="biankuang">
	<input type="text" name="msn" id="msn" value="{$_userinfo['msn']}" />
		</div>
	</td>
    <td align="left" valign="middle" class="color">
    	<select id="pub_msn" class="pub-option">
		{$_privacy[4]}
		</select>
	</td>
  </tr>
  <tr>
    <td align="right" valign="middle">MOBILE：</td>
    <td width="320" align="left" valign="middle">
	<div class="biankuang">
	<input type="text" name="mobile" id="mobile" value="{$_userinfo['mobile']}" />
	</div>
	</td>
    <td align="left" valign="middle" class="color">
		<select id="pub_mobile" class="pub-option">
		{$_privacy[5]}
		</select>
	</td>
  </tr>
  <tr>
    <td align="center" valign="middle" class="user_info_ok">&nbsp;</td>
    <td align="left" valign="middle" class="user_info_ok"><input name="input" type="button"  value="" id="submit01" /> <div class='info-prompt' id="info_prompt"></div></td>
    <td align="left" valign="middle">&nbsp;</td>
  </tr>
</table>
</div>
<div class="content_bottom"></div>

  </div>

{template:foot}