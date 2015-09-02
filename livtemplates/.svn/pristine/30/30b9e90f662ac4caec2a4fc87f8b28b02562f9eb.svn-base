<?php 
/* $Id: photoedit_one_all.php 17834 2013-03-22 03:25:33Z jeffrey $ */
?>
{template:head}
{css:ad_style}
{code}
$_INPUT['referto'] = str_replace("a=viewall&","",$_INPUT['referto']);
//hg_pre($formdata);
{/code}

<style type="text/css">
.listimg {
width:200px;
padding:10px 20px;
float:left;
}
.listimg span {
color:#f00;
}
</style>
<div class="ad_middle">
	<form name="editform"  id="editform" action="./run.php?mid={$_INPUT['mid']}" method="post"  class="ad_form h_l">
		<h2>历史痕迹</h2>
		{if is_array($formdata) &&!empty($formdata) && count($formdata)>0}
				{foreach $formdata as $k => $v}
                    <div class="listimg">
                    <img src="{$v['lujing']}{$v['filename']}" width="170" height="150">
                    <br>
                    <span>最新操作时间：</span>
                    <br>
                    {$v['update_time']}
                    <br>
                    <input type="radio" name="ismain" value="{$v['filename']}" {if $v['active']} checked="checked" {/if}/> 选择主图
                    </div>
                {/foreach}
        {/if}
        <div style="clear:both;"></div>
		</br>
		<input type="submit" name="sub" value="确认选择" id="sub" class="button_6_14"/>
		<input type="hidden" name="a" value="updatepic" id="action" />
		<input type="hidden" name="fid" value="{$_INPUT['fid']}" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</form>
</div>
<div class="right_version">
	<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
</div>
{template:foot}