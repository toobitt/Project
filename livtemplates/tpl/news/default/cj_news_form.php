{template:head}
{css:hg_sort_box}
{js:hg_sort_box}
{js:common/common_form}
{code}
!$formdata  && ($formdata = $_INPUT);
	if ( is_array($formdata ) )
	{  
		foreach ( $formdata as $k => $v ) 
		{
			$$k = $v;
		}
	}		
	if($id)
	{
		$optext="更新";
		$ac="update";
	}
	else
	{
		$optext="添加";
		$ac="create";
	}
	$currentSort[$sort_id] = ($sort_id ? $sort_name : '选择分类');
{/code}
{code}
$ueditorDir = './res/ueditor/';
{/code}
<script type="text/javascript" src="{$ueditorDir}ueditor.all.js"></script>
<script type="text/javascript" src="{$ueditorDir}ueditor.config.js"></script>
<style>
#content_form>table{width:1210px;margin:10px auto;border-collapse:collapse;background:white;}
.title{height:100px;}
#editor{width:1059px!important;}
.title>td{border:1px solid #d7d7d7;}
.title input[type="text"]{width:95%;margin-left:10px;}
.title .keyword-area{width:76%!important;margin-right:10px;}
.head{text-align:center;font-size:16px;width:140px;color:#333;}
.title{height:50px;}
.content{height:240px;}
.keywords{height:130px;}
.sort-name{padding-left:10px;}
.save{margin-top:10px;padding:0 40px;height:34px;background:#6EA5E8;line-height:34px;color:#fff;border:none;border-radius:2px;font-size:14px;cursor:pointer;}
.save:hover{backgorund-color:#357ed3;border:none;}
.hg-sort-box li .sort-name{width:70%;}
</style>

<form method="post"  id="content_form">
	<table>
		<tr class="title">
			<td class="head">标题</td>
			<td><input type="text" name="title" value="{$title}"/></td>
		</tr>
		<tr class="title">
			<td class="head">原始链接</td>
			<td><input type="text" name="ori_url" value="{$ori_url}"/></td>
		</tr>
		<tr class="title">
			<td class="head">摘要</td>
			<td><textarea name="brief" style="margin: 10px 0 10px 10px;width: 400px;height: 80px;">{$brief}</textarea></td>
		</tr>
		<tr class="title content">
			<td class="head">内容</td>
			<td>
					<script type="text/plain" id="editor" name="content">{code}echo htmlspecialchars_decode($content);{/code}</script>
			</td>
		</tr>
		<tr class="title">
			<td class="head">关键字</td>
			<td>
				<div>
					<input type="text" name="keywords" value="{$keyword}" class="keyword-area" />
					<span>多个关键字用逗号分割</span>
				</div>
			</td>
		</tr>
        <tr class="title">
            <td class="head">来源</td>
            <td>
                <div>
                    <input type="text" name="source" value="<?php echo $from;?>" class="keyword-area" />
                </div>
            </td>
        </tr>
        <tr class="title">
			<td class="head">分类</td>
			<td>
			<div id="sort-box">
				<p style="display:inline-block;" class="sort-label" _multi="news_node"> {$currentSort["$sort_id"]}<img class="common-head-drop" src="{$RESOURCE_URL}tuji/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
				<div class="sort-box-outer" style="width:190px;"><div class="sort-box-inner"></div></div>
                <input name="sort_id" type="hidden" value="{$sort_id}" id="sort_id" />
    		</div>
			</td>
		</tr>
		<tr>
			<td class="title"></td>
			<td>
					<input type="submit" value="保存" class="save" />
			</td>
		</tr>
	</table>
			<input type="hidden" name="iscj"  value="1" />
			<input type="hidden" name="a" value="{$ac}" />
            <input type="hidden" id="id"  name="id" value="{$formdata['id']}" />
            <input type="hidden" name="referto" value="{$_INPUT['referto']}" id="referto" />
            <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
            <input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
            <input type="hidden" name="app" value="{$_INPUT['app']}" />
</form>
<script>
var options = {};
var ue = UE.getEditor('editor', options);
</script>
{template:foot}