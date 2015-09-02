<?php 
?>
{template:head}
{js:common/ajax_upload}
{css:vod_style}
{css:mark_style}
{css:common/common_list}
{js:common/common_list}
{code}
$list = $mode_list[0][0];
if(!class_exists('column'))
{
    include_once(ROOT_DIR . 'lib/class/column.class.php');
    $publish = new column();
}
//获取所有站点
$hg_sites = $publish->getallsites();

/*if(!$_INPUT['site_id'])
{
	$_INPUT['site_id'] = $this->settings['site_default'];
}
if(!$_INPUT['site_id'])
{
	$_INPUT['site_id'] = 1;
}*/

if($formdata)
{
	header ( 'Pragma: public' );
	header ( 'Expires: 0' );
	header ( 'Cache-Control:' );
	header ( 'Cache-Control: public' );
	header ( 'Content-Description: File Transfer' );
	header ( 'Content-Type: application/force-download' );
	header ( 'Content-Disposition: attachment; filename="' . $formdata['file_name'] . '";' );
	header ( 'Content-Transfer-Encoding: binary' );
	header ( 'Content-Length: ' . strlen ($formdata['content']) );
	echo $formdata['content'];
}
{/code}
<script>
$(function(){
	$('#Filedata').ajaxUpload({
		type : 'php',
		url : './run.php?mid='+gMid+'&a=import_mode',
		phpkey : 'Filedata',
		after : function( json ){
			window.location.href = './run.php?mid=' + gMid + '&infrm=1&nav=1';
		}
	});
	parent.$('.export-style').on('click',function(){
		//alert('111');
		$('#Filedata').click();
	});
});
gBatchAction['delete'] = './run.php?mid=' + gMid + '&a=delete';

</script>

<div id="hg_page_menu" class="head_op_program"{if $_INPUT['infrm']} style="display:none"{/if}>

<!--<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}&_id={$_INPUT['_id']}&site_id={$_INPUT['site_id']}" class="button_6"><strong>新增样式</strong></a>-->
<form action="" method="POST" name="add_mode" id="add_mode">
	<a type="button" class="button_6"  href="./run.php?mid={$_INPUT['mid']}&a=form&_id={$_INPUT['_id']}&site_id={$_INPUT['site_id']}&infrm=1" target="formwin">新增样式</a>
</form>
<form action="" method="POST" name="import_mode" id="import_mode">
	<span type="button" class="button_6 export-style">导入样式</span>
	<input type="file" name="Filedata" id="Filedata" style="display:none;">
</form>
<!--<form action="" method="POST" name="add_pic" id="add_pic">
	<span type="button" class="button_6"  onclick="pic_form()">上传样式图片</span>
</form>-->
</div>
<div class="wrap">
{template:unit/modesearch}

	<div class="common-list-content" style="min-height:auto;min-width:auto">
	<form method="post" action="" id="channel_table" class="common-list-form">
		<ul class="common-list news-list">
			<li class="common-list-head public-list-head clear">
				<div class="common-list-left">
					<div class="common-list-item paixu"></div>
				</div>
				<div class="common-list-right">
					<!--<div class="common-list-item wd80">站点</div>-->
					<div class="common-list-item wd80">样式分类</div>
					<div class="common-list-item wd80">样式类型</div>
					<div class="common-list-item wd100">样式操作</div>
					<div class="common-list-item wd100">添加人/添加时间</div>
				</div>
				<div class="common-list-biaoti">
					<div class="common-list-item wd80">示意图</div>	
					<div class="common-list-item">样式名</div>
				</div>
			</li>
		</ul>	
		<ul id="status" class="common-list public-list">
		{if $list}
		   {foreach $list as $k => $v}
			<li id="r_{$v['id']}" class="common-list-data clear h" name="{$v['id']}">
				<div class="common-list-left">
					<div class="common-list-item paixu">
					<div id="primary_key_img_{$v['id']}"><input id="primary_key_{$v['id']}" type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"/></div>
					</div>
				</div>
				<div class="common-list-right">
					<div class="common-list-item wd80"><span id="name_{$v['site_name']}">{$v['site_name']}</span></div>
					<div class="common-list-item wd80"><span id="name_{$v['sort_name']}">{$v['sort_name']}</span></div>
					<div class="common-list-item wd80"><span id="name_{$v['mode_type']}">{if 1==$v['mode_type']}正文{else}其他{/if}</span></div>
					<div class="common-list-item wd100">
						<a title="更新" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&site_id={$_INPUT['site_id']}&infrm=1" target=formwin>编辑</a>
						<!--<a title="下载" href="./run.php?mid={$_INPUT['mid']}&a=download&id={$v['id']}&infrm=1">下载</a>-->
						<a title="导出" href="./run.php?mid={$_INPUT['mid']}&a=download&id={$v['id']}">导出</a>
						<a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}">删除</a>	
					</div>
					<div class="common-list-item wd100">
						<span class="common-user" id="name_{$v['user_name']}">{$v['user_name']}</span>
						<span class="common-time" id="name_{$v['create_time']}">{$v['create_time']}</span>
					</div>
				</div>
				<div class="common-list-biaoti">
				<div class="common-list-item wd80">
					<div class="common-list-overflow">
						<a id="name_{$v['site_name']}">
				{code}
				 	$pic = '';
				  	if($v['indexpic'])
				  	{
				  		$pic = $v['indexpic']['host'] . $v['indexpic']['dir'] . $v['indexpic']['filepath'] . $v['indexpic']['filename'];
				  	}
				{/code}
				{if $pic}
					<img src="{$pic}" style="width:40px;height:30px;margin-right:10px;" />
				{else}
				{/if}
						</a>
					</div>
				</div>
				<div class="common-list-item  biaoti-transition"><span id="name_{$v['name']}" class="m2o-common-title">{$v['title']}</span></div>
				</div>
			</li>
			{/foreach}
		{else}
		<div><div class="hg_error">暂无记录</div></div>
		{/if}
		</ul>

			<ul class="common-list">
				     <li class="common-list-bottom clear">
					   <div class="common-list-left">
	                     <input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
				         <a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" name="delete">删除</a>
				      </div>
	                  {$pagelink}
	               </li>
	             </ul>	
	{template:foot}
	</form>
</div>
<script>
function add_mode()
{
	/*var site_id = {$_INPUT['site_id']};
	if (   site_id && site_id == '-1' ) {
		jAlert('请选择站点！', '提示');
		return;
	}*/
	window.location.href="./run.php?mid={$_INPUT['mid']}&a=form&_id={$_INPUT['_id']}&site_id={$_INPUT['site_id']}&infrm=1";
}
function pic_form()
{
	/*var site_id = {$_INPUT['site_id']};
	if ( site_id && site_id == '-1' ) {
		jAlert('请选择站点！', '提示');
		return;
	} */
	window.location.href="./run.php?mid={$_INPUT['mid']}&a=upload&site_id={$_INPUT['site_id']}&infrm=1";
}
</script>