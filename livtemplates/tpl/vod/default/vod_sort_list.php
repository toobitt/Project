
{template:head}
<!-- <input type="button" value="添加视频类别" id="add_vod" style="margin-left:1000px;cursor:pointer;" onclick="return hg_ajax_post('./run.php?mid={$_INPUT['mid']}&a=form', '添加', 0);"/> -->
<a href="./run.php?mid={$_INPUT['mid']}&a=form" target="mainwin" style="margin-left:1000px;" >添加视频类别</a>
<div class="wrap">
{code}
	$list = $vod_sort_list;
{/code}
{template:list}
</div>
{template:foot}