{template:head}
{code}
    $session = $formdata['session_info'];
    if(!$session['id'])
    {
        $optext="新建";
        $ac="create_session";
    }
    else
    {
        $optext="更新";
        $ac="update_session";
    }
{/code}
{css:ad_style}
{js:ad}
{css:common/common_form}
{js:common/auto_textarea}
{js:common/common_form}
{css:hg_sort_box}
{js:hg_sort_box}
<style>
body{overflow:auto;height:auto;}
</style>
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post"   id="content_form">
<h2>{$optext}</h2>
<ul class="form_ul">
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">名称：</span>
        <div style="display:inline;float:left;margin-right:10px;">
            <input type="text" value='{$session['title']}' name='title' class="title">
        </div>
    </div>
</li>
{if !$session['id']}
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">联系人：</span>
        <div style="display:inline;float:left;margin-right:10px;">
            <input type="text" value='{$session['title']}' name='touser_name' class="title">
        </div>
        <font class="important">填写用户昵称 多个逗号隔开  默认类型为m2o注册会员</font>
    </div>
</li>
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">内容：</span>
        <div style="display:inline;float:left;margin-right:10px;">
            <textarea name="message"></textarea>
        </div>
    </div>
</li>
{/if}

<li class="i">
    <div class="form_ul_div clear">
        <span class="title">人数限制：</span>
        <div style="display:inline;float:left;margin-right:10px;">
            <input type="text" value="{$session['settings']['person_limit']}" name="settings[person_limit]" class="title" style="width:80px;">
        </div>
        <font class="important">留空不限制人数</font>
    </div>
</li>

<li class="i">
    <div class="form_ul_div clear">
        <span class="title">推送通知：</span>
        <div style="display:inline;float:left;margin-right:10px;">
            {template:form/radio, settings[push_notice], $session['settings']['push_notice']}
        </div>
    </div>
</li>


</ul>
<input type="hidden" name="a" value="{$ac}" />
<input type="hidden" name="session_id" value="{$session['id']}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
    <h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>
<script>
</script>
{template:foot}