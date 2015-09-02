{template:head}
{if is_array($formdata)}
    {foreach $formdata as $key => $value}
        {code}
            $$key = $value; 
        {/code}
    {/foreach}
{/if}
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
<h2>添加联系人</h2>
<ul class="form_ul">
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">收件人：</span>
        <div style="display:inline;float:left;margin-right:10px;">
            <input type="text" value='' name='user_name' class="title">
        </div>
    </div>
</li>
</ul>
<input type="hidden" name="a" value="add_person" />
<input type="hidden" name="session_id" value="{$formdata['session_id']}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="添加" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
    <h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>
<script>
</script>
{template:foot}