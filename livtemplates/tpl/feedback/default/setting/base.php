<ul class="form_ul">
<li class="i">
<div class="form_ul_div">
<span class="title">&nbsp;&nbsp;&nbsp;域名：</span>
<input type="text" value="{$settings['define']['FB_DOMAIN']}" name='define[FB_DOMAIN]' style="width:200px;">
<font class="important" style="color:red"></font>
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">&nbsp;&nbsp;&nbsp;上传素材大小：</span>
<input type="text" value="{$settings['define']['UPLOAD_MATERIAL_SIZE']}" name='define[UPLOAD_MATERIAL_SIZE]'>(单位：M)
<font class="important" style="color:red"></font>
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">&nbsp;&nbsp;&nbsp;上传视频大小：</span>
<input type="text" value="{$settings['define']['UPLOAD_MEDIA_SIZE']}" name='define[UPLOAD_MEDIA_SIZE]'>(单位：M)
<font class="important" style="color:red"></font>
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">&nbsp;&nbsp;&nbsp;通过审核后才加积分：</span>
<input type="radio" {if $settings['define']['AUDIT_ADD_CRIDET']}checked{/if} value="1" name='define[AUDIT_ADD_CRIDET]'>是
<input type="radio" {if !$settings['define']['AUDIT_ADD_CRIDET']}checked{/if} value="0" name='define[AUDIT_ADD_CRIDET]'>否
<font class="important" style="color:red"></font>
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">&nbsp;&nbsp;&nbsp;地址组件默认省份ID：</span>
<input type="text" value="{$settings['define']['PROVINCE_ID']}" name='define[PROVINCE_ID]'>
<font class="important" style="color:red"></font>
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">&nbsp;&nbsp;&nbsp;地址组件默认城市ID：</span>
<input type="text" value="{$settings['define']['CITY_ID']}" name='define[CITY_ID]'>
<font class="important" style="color:red"></font>
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">&nbsp;&nbsp;&nbsp;明信片分类id：</span>
<input type="text" value="{$settings['define']['GREETING_SORT']}" name='define[GREETING_SORT]'>
<font class="important" style="color:red"></font>
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">&nbsp;&nbsp;&nbsp;无设备号错误提示：</span>
<input type="text" value="{$settings['define']['NO_DEVICE_TIPS']}" name='define[NO_DEVICE_TIPS]'>
<font class="important" style="color:red"></font>
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">&nbsp;&nbsp;&nbsp;验证设备号：</span>
<input type="radio" {if $settings['define']['NEED_CHECK_DEVICE']}checked{/if} value="1" name='define[NEED_CHECK_DEVICE]'>是
<input type="radio" {if !$settings['define']['NEED_CHECK_DEVICE']}checked{/if} value="0" name='define[NEED_CHECK_DEVICE]'>否
<font class="important" style="color:red"></font>
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">&nbsp;&nbsp;&nbsp;默认appid：</span>
<input type="text" value="{$settings['define']['APPID']}" name='define[APPID]'>
<font class="important" style="color:red"></font>
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">&nbsp;&nbsp;&nbsp;默认appkey：</span>
<input type="text" value="{$settings['define']['APPKEY']}" name='define[APPKEY]'>
<font class="important" style="color:red"></font>
</div>
</li>
</ul>