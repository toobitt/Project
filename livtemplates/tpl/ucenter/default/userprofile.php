{template:head}

    <div class="con1 clear">
  <div class="content_top"></div>	
		<div class="content_middle lin_con clear" style="padding:0">  
     	<!-- 导航按钮  -->
		
       {template:unit/userset} 

      <div class="clear"></div>
      <p style="padding-left:50px;">以下信息将显示在你的<a>个人资料页</a>，方便大家了解你。&nbsp;&nbsp;&nbsp;&nbsp;<span class="red">*</span>为必填项</p>
 <table width="735" border="0" align="center" cellpadding="0" cellspacing="0" height="420" class="content_user_info">
  <tr>
    <td width="113" align="right" valign="middle">
    {if $edit_username == 1}
    <span class="red">*</span>
    {/if}
    {$_lang['username']}：
    </td>
    <td width="360" align="left" valign="middle">   
	
	{if $edit_username == 1}
	<div class="biankuang">
      <input type="text" name="username" id="username" value="{$userinfo['username']}" />
	</div>
	{else}
	<span style="padding:10px;float:left">{$userinfo['username']}</span>
	{/if}
	
	</td>
    <td width="262" align="left" valign="middle" id="info01" >&nbsp;</td>
  </tr>
  <tr>
    <td align="right" valign="middle">{$_lang['truename']}：</td>
    <td width="320" align="left" valign="middle">
		<div class="biankuang">
    <input type="text" name="truename" id="truename"  value="{$userinfo['truename']}" />
		</div>
    </td>
    <td align="left" valign="middle" ><select id="pub_name" class="pub-option">
	{$privacy[0]}
	</select></td>
  </tr>
  
  	{if SHOW_LOCATION}

  <tr>
    <td align="right" valign="middle"><span class="red">*</span>{$_lang['location']}：</td>
    <td width="320" align="left" valign="middle">
    	<select id='province' name='province' ><option value='0'>{$_lang['choose']}</option></select>
		<select id='city' name='city'><option value='0'>{$_lang['choose']}</option></select>
		<select id='country' name='country'><option value='0'>{$_lang['choose']}</option></select>
    </td>
    <td align="left" valign="middle" class="color" id="info03" >&nbsp;</td>
  </tr>
 {/if}
  <tr>
    <td align="right" valign="middle"><span class="red">*</span>{$_lang['sex']}：</td>
    <td width="320" height="40" align="left" valign="middle"  class="color">
    <label><input type="radio" name="sex" value="1" {code} if(intval($userinfo['sex']) == 1) echo "checked='checked'"{/code} >{$_lang['male']}</label>&nbsp;&nbsp;
	<label><input type="radio" name="sex" value="2" {code} if(intval($userinfo['sex']) == 2) echo "checked='checked'"{/code}>{$_lang['female']}</label>
	</td>
    <td align="left" valign="middle" class="color" id="info04" >&nbsp;</td>
  </tr>
  <tr>
    <td align="right" valign="middle">{$_lang['birthday']}：</td>
    <td width="320" align="left" valign="middle" class="color"><label for="year"></label>
      <select name="year" id="year">
     {$birthday[0]}
      </select>
      年
      <label for="month"></label>
      <select name="month" id="month">
      {$birthday[1]}
      </select>
      月
      <label for="date"></label>
      <select name="date" id="date">
      {$birthday[2]}
      </select>
      日</td>
    <td align="left" valign="middle" class="color">
    	<select id="pub_birth" class="pub-option">
      {$privacy[1]}
		</select>
	</td>
  </tr>
    <tr>
    <td align="right" valign="middle">{$_lang['email']}：</td>
    <td width="320" align="left" valign="middle">
		<div class="biankuang">
	<input type="text" name="email" id="email"  value="{$userinfo['email']}" />
		</div>
	</td>
    <td align="left" valign="middle" class="color" >
		<select id="pub_email" class="pub-option">
        {$privacy[2]}
		</select>	
		<span id="info06">
		</span>
	</td>
  </tr>
    <tr>
    <td align="right" valign="middle">QQ：</td>
    <td width="320" align="left" valign="middle">
		<div class="biankuang">
	<input type="text" name="qq" id="qq" value="{$userinfo['qq']}" />
		</div>
	</td>
    <td align="left" valign="middle" class="color">
    	<select id="pub_qq" class="pub-option">
        {$privacy[3]}
		</select>
	</td>
  </tr>
  <tr>
    <td align="right" valign="middle">MSN：</td>
    <td width="320" align="left" valign="middle">
		<div class="biankuang">
	<input type="text" name="msn" id="msn" value="{$userinfo['msn']}" />
		</div>
	</td>
    <td align="left" valign="middle" class="color">
    	<select id="pub_msn" class="pub-option">
  		{$privacy[4]}
		</select>
	</td>
  </tr>
  <tr>
    <td align="right" valign="middle">MOBILE：</td>
    <td width="320" align="left" valign="middle">
	<div class="biankuang">
	<input type="text" name="mobile" id="mobile" value="{$userinfo['mobile']}" />
	</div>
	</td>
    <td align="left" valign="middle" class="color">
		<select id="pub_mobile" class="pub-option">
  		{$privacy[5]}
		</select>
	</td>
  </tr>
  <tr>
    <td align="right" valign="middle">数字电视号：</td>
    <td width="320" align="left" valign="middle">
	<div class="biankuang">
	<input type="text" name="tv" id="tv" value="{$userinfo['digital_tv']}" />
	</div>
	</td>
    <td align="left" valign="middle" class="color">

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