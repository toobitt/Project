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
<h2>{$optext}配置</h2>
<ul class="form_ul">
<!--
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">根目录：</span><input type="text" value='{$root_path}' name='title' class="root_path">
    </div>
</li>


<li class="i">
    <div class="form_ul_div clear">
        <span class="title">父目录：</span><input type="text" value='{$parent_path}' name='title' class="parent_path">
    </div>
</li>
-->
<li class="i">
    <div class="form_ul_div clear">
        <span class="title">当前目录：</span><input type="text" value='{$current_path}' name='current_path' class="title">
    </div>
</li>



<li class="i">
    <div class="form_ul_div clear">
        <span class="title">配置名称：</span><input type="text" value='{$config_name}' name='config_name' class="title">
    </div>
</li>


<li class="i">
    <div class="form_ul_div clear">
        <span class="title">配置简述：</span><textarea name="config_brief">{$config_brief}</textarea>
    </div>
</li>


<li class="i">
    <div class="form_ul_div clear">
        <span class="title">配置内容：</span><textarea name="config_content"><?php echo var_export($config_content);?></textarea>
    </div>
</li>



</ul>
<?php if($_INPUT['custom_id']):?>
<input type="hidden" name="site_id" value="{$_INPUT['site_id']}" />
<?php else:?>
<input type="hidden" name="site_id" value="{$site_id}" />   
<?php endif;?>    
<input type="hidden" name="a" value="{$ac}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}配置" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
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