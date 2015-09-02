{template:head}
{js:vod_opration}
{js:share}
{css:common/common_list}
{css:template_list}
{js:common/common_list}
{css:vod_style}
{css:edit_video_list}
{js:publishsys/init_create_page}
{code}
//print_r($template_list);
$sites = $template_list[0]['site'];
$clients = $template_list[0]['client'];
$template_styles = $template_list[0]['template_styles'];
$auth = $template_list[0]['auth'];
$site_info = serialize($sites);
$client_info = serialize($clients);
$sor = $template_list[0][1];
$templateinfo = empty($template_list[0]['c'])?'':$template_list[0]['c'];

if($templateinfo)
{
		header("content-type: application/octet-stream");
    	header("accept-ranges: bytes");
    	header("accept-length: ".filesize($templateinfo['filename']));
    	header("content-disposition: attachment; filename=tv_index.html");
		$fp = fopen($templateinfo['filename'],"r");
		echo fread($fp,filesize($templateinfo['filename']));
		
		fclose($fp);
		exit();
}

if(!class_exists('column'))
{
    include_once(ROOT_DIR . 'lib/class/column.class.php');
    $publish = new column();
}
//获取所有站点
//$hg_sites = $publish->getallsites();

if(!$_INPUT['site_id'])
{
	$_INPUT['site_id'] = 1;
}
{/code}
<style>
.common-list-overflow{max-width:200px;}
</style>
<body style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
<form action="" method="POST" name="add_page" id="add_page">
	<a type="button" class="button_6"  href="./run.php?mid={$_INPUT['mid']}&a=form&site_id={$_INPUT['site_id']}&infrm=1&id=-1" target="formwin">新增模板</a>
</form>
<form action="" method="POST" name="zip_download" id="zip_download">
	<span type="button" class="button_6"  onclick="zip_download()">打包下载</span>
</form>
</div>
<div class="content clear">
 <div class="f">
  			<!--视频发布模板占位符-->
			<span class="vod_fb" id="vod_fb"></span>
			<div id="vodpub" class="vodpub lightbox">
				<div class="lightbox_top">
					<span class="lightbox_top_left"></span>
					<span class="lightbox_top_right"></span>
					<span class="lightbox_top_middle"></span>
				</div>
				<div class="lightbox_middle">
					<span onclick="hg_vodpub_hide();" style="position:absolute;right:25px;top:25px;z-index:1000;background:url('{$RESOURCE_URL}close.gif') no-repeat;width:14px;height:14px;cursor:pointer;display:block;"></span>
					<div id="vodpub_body" class="text" style="max-height:500px;padding:10px 10px 0;">
					</div>
				</div>
				<div class="lightbox_bottom">
					<span class="lightbox_bottom_left"></span>
					<span class="lightbox_bottom_right"></span>
					<span class="lightbox_bottom_middle"></span>
				</div>				
			</div>
			<!--//视频发布>
 
 		    <!-- 新增图集模板开始 -->
		 	<div id="add_tuji"  class="single_upload">
				<h2><span class="b" onclick="hg_closeTuJiTpl();"></span><span id="tuji_title">新增图集</span></h2>
				<div id="tuji_contents_form"  class="upload_form" style="height:808px;margin-top:10px;overflow:auto;"></div>
			</div>
			<!-- 新增图集模板结束 -->
			
 		    <!-- 移动图集模板开始 -->
		 	<div id="move_tuji"  class="single_upload">
				<h2><span class="b" onclick="hg_showMoveTuJi();"></span><span id="move_title">移动图集</span></h2>
				<div id="tuji_sort_form"  class="upload_form" style="height:808px;margin-top:10px;overflow:auto;"></div>
			</div>
			<!-- 移动图集模板结束 -->
		</div>
          <div class="right v_list_show">
          {template:unit/templatesearch}
    			<div class="edit_show">
					<span class="edit_m" id="arrow_show"></span>
					<div id="edit_show"></div>
				</div>
           </div>
           <div class="common-list-content">
           		<form method="post" action="" name="listform">
                   <!-- 标题 -->
                   <ul class="common-list" id="list_head">
                       <li class="common-list-head public-list-head clear">
                           <div class="common-list-left">
                               <div class="common-paixu common-list-item"><a class="common-list-paixu"></a></div>
                               <div class="special-slt common-list-item template-slt">示意图</div>
                           </div>
                           <div class="common-list-right">
	                           <div class="common-list-item open-close wd80">所属站点</div>
	                           <div class="common-list-item open-close wd80">模板套系</div>
                               <div class="common-list-item open-close wd80">所属终端</div>
                               <div class="common-list-item open-close template-fl wd80">模板分类</div>
                               <div class="common-list-item open-close template-cz wd180">操作</div>
                           </div>
                           <div class="common-list-biaoti ">
						       <div class="common-list-item open-close template-biaoti">模板名称</div>
					       </div>
                       </li>
                   </ul>
                   {code}
                   //echo $template_list[0][0][0]['content'];
                   {/code}
	               <ul class="common-list" id="tujilist">
					   {if $template_list[0][0]}
		       			   {foreach $template_list[0][0] as $k => $v} 
		                     {template:unit/templatelist}
		                   {/foreach}
					   {else}
					       <p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
		  			   {/if}
	               </ul>
		           <ul class="common-list">
				       <li class="common-list-bottom clear">
					   <div class="common-list-left">
		                   <input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
		                   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');"    name="batdelete">删除</a>
					   </div>
		               {$pagelink}
		            </li>
		            </ul>
		            <input type="hidden" name="html" value="ture" />	
		            <input type="hidden" name="site_id" value="{$_INPUT['site_id']}" id="site_id"/>	
		            <input type="hidden" name="sort_id" value="{$_INPUT['sort_id']}" id="sort_id"/>	
    			</form>
           </div>
      </div>
   <div id="infotip"  class="ordertip"></div>
   <div id="getimgtip"  class="ordertip"></div>
</body>
<script>
function template_form(id)
{
	if ( {$_INPUT['site_id']} == 0 ) {
		jAlert('请选择站点！', '提示');
		return;
	} 
	window.location.href="./run.php?mid={$_INPUT['mid']}&a=form&site_id={$_INPUT['site_id']}&infrm=1";
}

function zip_download()
{
	if ( {$_INPUT['sort_id']} == '-1' ) {
		jAlert('请选择模板分类！', '提示');
		return;
	} 
	var site_id = $('#site_id').val(),
	    sort_id = $('#sort_id').val(),
	    url = './run.php?mid=' + gMid + '&a=zip_download';
	$.post(url,{
		site_id : site_id,
		sort_id : sort_id,
	},function(data){
		var obj = eval('('+data+ ')');
		var url = obj[0];
		window.location.href=url;
	});
}

(function () {
    $('.template-download').on({
        'click':function () {
            var id = $(this).attr("_id");
            var options = {
                url:'run.php?mid='+gMid+'&a=download&id='+id,
                type:'get'
            };
            $.ajax(options);
            return false;
        }
    });

})($);

</script>
{template:foot}