{template:head}
{code}
    if($id)
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
{if is_array($formdata)}
    {foreach $formdata as $key => $value}
        {code}
            $$key = $value; 
        {/code}
    {/foreach}
{/if}
{css:ad_style}
{js:ad}
{js:hg_water}
{css:column_node}
{js:column_node}
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post"   id="content_form">
<h2>{$optext}客户站点</h2>
<ul class="form_ul">
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">站点名称：</span><input type="text" value='{$title}' name='title' class="title">
    </div>
</li>
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">站点描述：</span><textarea name="brief">{$brief}</textarea>
    </div>
</li>

<li class="i">
    <div class="form_ul_div clear">
        <span class="title">站点网址：</span><input type="text" value='{$url}' name='url' class="title">
    </div>
</li>

<!--
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">站点IP：</span><input type="text" value='{$ip}' name='ip' class="title">
    </div>
</li>


<li class="i">
    <div class="form_ul_div clear">
        <span class="title">站点端口：</span><input type="text" value='{$port}' name='port' class="title">
    </div>
</li>
-->
</ul>
<?php if($_INPUT['custom_id']):?>
<input type="hidden" name="custom_id" value="{$_INPUT['custom_id']}" />
<?php else:?>
<input type="hidden" name="custom_id" value="{$custom_id}" />   
<?php endif;?>    
<input type="hidden" name="a" value="{$ac}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}客户" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
    <h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>

<script type="text/javascript">
function hg_remove_rows(id)
{
     var ids=id.split(",");
     for(var i=0;i<ids.length;i++)
     {
        $("#news_sort_name_"+ids[i]).remove();
     }
}


</script>
{template:foot}