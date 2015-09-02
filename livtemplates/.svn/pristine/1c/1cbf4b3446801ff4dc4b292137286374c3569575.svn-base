{template:head}
{code}
	$opname = "域名";
	$optext="新增";
	$ac="create";
	
{/code}
{css:ad_style}
{js:ad}
{css:column_node}
{js:column_node}

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
            <span  class="site_title">域名：</span>
            <input type="text"  name='domain'  style="width:200px;">
            <font class="important" style="color:red"></font>
            <font class="important">必填</font>
        </div>
    </li>
    <li class="i">
        <div class="form_ul_div">
            <span  class="site_title"> 配置前请须知：</span>
            <div class="important" style="float:none;overflow:hidden;">
              待绑定域名必须在工信部成功备过案<br/>
			  动态页面不支持加速<br/>
			  单个资源文件需小于100MB<br/>
			  资源缓存时间默认设置为7天，且不能修改<br/>
			  会过滤 Cookie、Etag、query 参数及自定义 head<br/>
			</div>
        </div>
    </li>
   
</ul>

<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="a" value="{$ac}" />
<input type="hidden" name="bucket_name" value="{$_INPUT['bucket_name']}" /> 
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