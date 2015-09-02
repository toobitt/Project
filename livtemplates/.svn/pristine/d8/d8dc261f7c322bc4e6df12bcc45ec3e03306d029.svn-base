{template:head}
{code}
	$list = $formdata;
	$opname = "空间";
	if($list['bucket_name'])
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
<style>
.introduce .important{float:none;padding-left:15px;}
.introduce h3{font-size:14px;}
.introduce .important div{padding-left:20px;}
</style>
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post"   id="content_form">
<h2>{$optext}{$opname}</h2>
<div id="test">

</div>
<ul class="form_ul">
<li class="i">
        <div class="form_ul_div">
            <span  class="site_title">空间名：</span>
            <input type="text" value="{$list['bucket_name']}" name='bucket_name'  style="width:200px;">
            <font class="important" style="color:red">*</font>
            <font class="important">英文字母开头的小写英文字符、数字、或横杠组合</font>
        </div>
    </li>
    <li class="i">
        <div class="form_ul_div">
            <span class="site_title">空间类型：</span>
			<select name='type'  value="{$formdata['type']}">
				{foreach $_configs['cdn']['space_type'] as $k=>$v}
				<option value="{$k}" {code}if($formdata['type']==$k) echo "selected";{/code}>
					{$v}
				</option>
				{/foreach}
			</select>
        </div>
    </li>
    <li class="i">
        <div class="form_ul_div">
            <span  class="site_title">存储配额：</span>
            <input type="text" value="{$list['quota']}" name='quota'  style="width:200px;">MB
            <font class="important" style="color:red"></font>
            <font class="important">留空或零表示无限制</font>
        </div>
    </li>
    <li class="i">
        <div class="form_ul_div">
            <span  class="site_title">回源请求域名：</span>
            <input type="text" value="{$list['domain']}" name='domain'  style="width:200px;">
            <font class="important" style="color:red"></font>
        </div>
    </li>
    <li class="i">
        <div class="form_ul_div">
            <span  class="site_title">电信线路IP：</span>
            <input type="text" value="{$list['ip_tel']}" name='ip_tel'  style="width:200px;">
            <font class="important" style="color:red"></font>
        </div>
    </li>
    <li class="i">
        <div class="form_ul_div">
            <span  class="site_title">联通线路IP：</span>
            <input type="text" value="{$list['ip_cnc']}" name='ip_cnc'  style="width:200px;">
            <font class="important" style="color:red"></font>
        </div>
    </li>
    <li class="i introduce">
        <div class="form_ul_div">
			<div class="important">
				<h3>说明：</h3>
				<div>
				 空间创建后，空间名称和空间类型不允许修改!<br/>
				 文件类空间：上传文件类型不限，无缩略图功能；<br/>
				 图片类空间：仅支持上传图片格式文件，支持缩略图功能；<br/>
				 <br/>
				 空间创建后会默认绑定二级域名：{空间名}.b0.upaiyun.com<br/>
				 用户可以添加绑定自己的二级域名，比如 xxx.yyy.com<br/>
				 添加域名绑定后，需在域名服务商的DNS解析管理中，把CNAME解析到{空间名}.b0.aicdn.com<br/>
				 </div>
				<h3>作用：</h3>
				<div>
				 若空间名为 bucket 的空间根目录下存在一张图片 pic.jpg，那么该图片的访问地址为：<br/>
				 http://bucket.b0.upaiyun.com/pic.jpg<br/>
				 http://xxx.yyy.com/pic.jpg<br/>
				 </div>
			</div>
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