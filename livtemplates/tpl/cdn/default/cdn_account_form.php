{template:head}
{code}
	$list = $cdn_account_form[0];
	$user_id = $list['user_id'];
	$opname = "帐号";
	if($user_id)
	{
		$optext="更新";
		$ac="update";
	}
	else
	{
		$optext="新增";
		$ac="create";
	}
{/code}
{css:ad_style}
{js:ad}
{css:column_node}
{js:column_node}

<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post"   id="content_form">
<h2>CDN{$opname}</h2>

<ul class="form_ul">
<li class="i">
        <div class="form_ul_div">
            <span  class="site_title">CDN用户名：</span>
            <input type="text" value="{$list['username']}" name='username'  style="width:200px;">
            <font class="important" style="color:red">*</font>
            <font class="important">必填</font>
        </div>
    </li>
    <li class="i">
        <div class="form_ul_div">
            <span  class="site_title">CDN密码：</span>
            <input type="text" value="{$list['password']}" name='password'  style="width:200px;">
            <font class="important" style="color:red">*</font>
            <font class="important">必填</font>
        </div>
    </li>
    <li class="i">
        <div class="form_ul_div">
            <span  class="site_title">邮箱：</span>
            <input type="text" value="{$list['email']}" name='email'  style="width:200px;">
            <font class="important" style="color:red">*</font>
            <font class="important">必填</font>
        </div>
    </li>
    <li class="i">
        <div class="form_ul_div">
            <span  class="site_title">帐户类型：</span>
            <input type="text" value="{$list['account_type']}" name='account_type'  style="width:200px;">
            <font class="important" style="color:red">*</font>
            <font class="important">必填</font>
        </div>
    </li>
   
    <li class="i">
        <div class="form_ul_div">
            <span  class="site_title">公司名称：</span>
            <input type="text" value="{$list['company_name']}" name='company_name'  style="width:200px;">
            <font class="important" style="color:red"></font>
        </div>
    </li>
    <li class="i">
        <div class="form_ul_div">
            <span  class="site_title">联系人姓名：</span>
            <input type="text" value="{$list['realname']}" name='realname'  style="width:200px;">
            <font class="important" style="color:red">*</font>
            <font class="important">必填</font>
        </div>
    </li>
    <li class="i">
        <div class="form_ul_div">
            <span  class="site_title">联系人手机：</span>
            <input type="text" value="{$list['mobile']}" name='mobile'  style="width:200px;">
            <font class="important" style="color:red">*</font>
            <font class="important">必填</font>
        </div>
    </li>
    <li class="i">
        <div class="form_ul_div">
            <span  class="site_title">联系人通讯方式：</span>
            <input type="text" value="{$list['im']}" name='im'  style="width:200px;">
            <font class="important" style="color:red"></font>
        </div>
    </li>
    <li class="i">
        <div class="form_ul_div">
            <span  class="site_title">用户web地址：</span>
            <input type="text" value="{$list['website']}" name='website'  style="width:200px;">
            <font class="important" style="color:red"></font>
        </div>
    </li>
</ul>

<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="a" value="{$ac}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}{$opname}" class="button_6_14"/>
<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>

{template:foot}