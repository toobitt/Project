<!--
<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div">
			<span class="title">&nbsp;&nbsp;&nbsp;Chinacache用户名：</span> 
			<input
				type="text" value="{$settings['base']['ChinaCache']['username']}"
				name='base[ChinaCache][username]' style="width: 200px;"> <font
				class="important" style="color: red"></font>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div">
			<span class="title">&nbsp;&nbsp;&nbsp;Chinacache密码：</span> <input
				type="text" value="{$settings['base']['ChinaCache']['password']}"
				name='base[ChinaCache][password]' style="width: 200px;"> <font
				class="important" style="color: red"></font>
		</div>
	</li>

</ul>
-->
<script>
    $(function(){
         $(".datepicker").datepicker();   
    })
</script>
{code}
$cdn_type = $settings['define']['CDN_TYPE'];
{/code}
<ul class="form_ul" style="margin-bottom:50px;">
	{if $cdn_type == 'ChinaCache'}
    <li class="i">
        <div class="form_ul_div">
            <span  class="title">Chinacache用户名：</span>
            <input type="text" value="{$settings['define']['ChinaCache_UserName']}" name='define[ChinaCache_UserName]' style="width:200px;">
            <font class="important" style="color:red"></font>
        </div>
    </li>
    <li class="i">
        <div class="form_ul_div">
            <span  class="title">Chinacache密码：</span>
            <input type="text" value="{$settings['define']['ChinaCache_Password']}" name='define[ChinaCache_Password]'  style="width:200px;">
            <font class="important" style="color:red"></font>
        </div>
    </li>
    {else}
    <!--<li class="i">
        <div class="form_ul_div">
            <span  class="title">CDN用户名：</span>
            <input type="text" value="{$settings['define']['UpYun_Username']}" name='define[UpYun_Username]'  style="width:200px;">
            <font class="important" style="color:red"></font>
        </div>
    </li>
    <li class="i">
        <div class="form_ul_div">
            <span  class="title">CDN密码：</span>
            <input type="text" value="{$settings['define']['UpYun_Password']}" name='define[UpYun_Password]'  style="width:200px;">
            <font class="important" style="color:red"></font>
        </div>
    </li>
    <li class="i">
        <div class="form_ul_div">
            <span  class="title">CDN邮箱：</span>
            <input type="text" value="{$settings['define']['UpYun_Email']}" name='define[UpYun_Email]'  style="width:200px;">
            <font class="important" style="color:red"></font>
        </div>
    </li>
    <li class="i">
        <div class="form_ul_div">
            <span  class="title">CDN帐户类型：</span>
            <input type="text" value="{$settings['define']['UpYun_AccountType']}" name='define[UpYun_AccountType]'  style="width:200px;">
            <font class="important" style="color:red"></font>
        </div>
    </li>
   
    <li class="i">
        <div class="form_ul_div">
            <span  class="title">CDN公司名称：</span>
            <input type="text" value="{$settings['define']['UpYun_CompanyName']}" name='define[UpYun_CompanyName]'  style="width:200px;">
            <font class="important" style="color:red"></font>
        </div>
    </li>
    <li class="i">
        <div class="form_ul_div">
            <span  class="title">CDN联系人姓名：</span>
            <input type="text" value="{$settings['define']['UpYun_RealName']}" name='define[UpYun_RealName]'  style="width:200px;">
            <font class="important" style="color:red"></font>
        </div>
    </li>
    <li class="i">
        <div class="form_ul_div">
            <span  class="title">CDN联系人手机：</span>
            <input type="text" value="{$settings['define']['UpYun_Mobile']}" name='define[UpYun_Mobile]'  style="width:200px;">
            <font class="important" style="color:red"></font>
        </div>
    </li>
    <li class="i">
        <div class="form_ul_div">
            <span  class="title">CDN联系人通讯方式：</span>
            <input type="text" value="{$settings['define']['UpYun_Im']}" name='define[UpYun_Im]'  style="width:200px;">
            <font class="important" style="color:red"></font>
        </div>
    </li>
    <li class="i">
        <div class="form_ul_div">
            <span  class="title">CDN用户web站点地址：</span>
            <input type="text" value="{$settings['define']['UpYun_Website']}" name='define[UpYun_Website]'  style="width:200px;">
            <font class="important" style="color:red"></font>
        </div>
    </li>-->

    {/if}
    
	<li class="i">
		<div class = "form_ul_div">
			<span class="title">设置清除时间：</span>
			<input type="text" value="{$settings['define']['DELETE_DATA']}" name='define[DELETE_DATA]' style="width:100px;">天
			<font class="important" style="color:red">清除此时间之前的推送日志</font>
		</div>
	</li>
</ul>

