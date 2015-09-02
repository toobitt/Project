<ul class="form_ul">
<li class="i">
<div class="form_ul_div">
<span class="title">&nbsp;&nbsp;&nbsp;开启会员积分：</span>
<input type="radio"  {if $settings['define']['IS_CREDITS']} checked{/if} name='define[IS_CREDITS]' value="1" > 是
<input type="radio"  {if !$settings['define']['IS_CREDITS']} checked{/if} name='define[IS_CREDITS]' value="0"> 否
<font class="important" style="color:red"></font>
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">&nbsp;&nbsp;&nbsp;每个投票增加积分次数：</span>
<input type="text" value="{$settings['define']['CREDIT_NUM']}" name='define[CREDIT_NUM]'>
<font class="important" style="color:red"></font>
</div>
</li>

<li class="i">
<div class="form_ul_div">
<span class="title">&nbsp;&nbsp;&nbsp;限制保留ip：</span>
<input type="radio"  {if $settings['define']['RESERVED_IP_LIMIT']} checked{/if} name='define[RESERVED_IP_LIMIT]' value="1" > 是
<input type="radio"  {if !$settings['define']['RESERVED_IP_LIMIT']} checked{/if} name='define[RESERVED_IP_LIMIT]' value="0"> 否
<font class="important" style="color:red"></font>
</div>
</li>

<li class="i">
<div class="form_ul_div">
<span class="title">&nbsp;&nbsp;&nbsp;未传递设备号时，禁止投票：</span>
<input type="radio"  {if $settings['define']['NO_DEVICE_VOTE']} checked{/if} name='define[NO_DEVICE_VOTE]' value="1" > 是
<input type="radio"  {if !$settings['define']['NO_DEVICE_VOTE']} checked{/if} name='define[NO_DEVICE_VOTE]' value="0"> 否
<font class="important" style="color:red"></font>
</div>
</li>

<li class="i">
<div class="form_ul_div">
<span class="title">&nbsp;&nbsp;&nbsp;未传递设备号时，错误提示：</span>
<input type="text" style="width:325px;" value="{$settings['define']['NO_DEVICE_TIPS']}" name='define[NO_DEVICE_TIPS]'>
<font class="important" style="color:red"></font>
</div>
</li>
</ul>