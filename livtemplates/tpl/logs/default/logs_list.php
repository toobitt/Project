{template:head}
{code}
//print_r($logs_list);
$list = $logs_list[0];
$ops = $logs_list[1];
$sos = $logs_list[2];
{/code}

{css:common/common_list}
{css:vod_style}
{css:edit_video_list}
{js:vod_video_edit}
{js:vod_opration}
{js:common/list}
<style type="text/css">
.common-list-i{top:12px;}
.edit_show .info.vider_s span{z-index:10000;}
.tuji_pics_show{width:398px;height:300px;background:#000 url({$image_resource}loading7.gif) no-repeat center;border:1px solid gray;position:relative;}
.tip_box{width:200px;height:100px;position:absolute;left:25%;top:-33%;background:none repeat scroll 0 0 #000000;opacity:0.7;display:none;z-index:20;}
.close_tip{position:absolute;left:89%;top:6%;z-index:20;width:15px;height:15px;background: url({$image_resource}hoge_icon.png) no-repeat -185px -18px;overflow:hidden;}
.pic_info{width:95%;height:15%;cursor:pointer;}
.arrL{position:absolute;width:50%;height:100%;cursor:pointer;left:0;top:0;z-index:10;}
.arrR{position:absolute;width:50%;height:100%;cursor:pointer;left:50%;top:0;z-index:10;}
.btnPrev{position:absolute;top:37%;left:12px;width:39px;z-index:15;height:80px;cursor:pointer;background:url({$image_resource}btnL_1.png)}
.btnNext{position:absolute;top:37%;right:12px;width:39px;z-index:15;height:80px;cursor:pointer;background:url({$image_resource}btnR_1.png)}
.btn_l{background:url({$image_resource}btnL_2.png) no-repeat;}
.btn_r{background:url({$image_resource}btnR_2.png) no-repeat;}
.special-slt{width:60px}
.special-ztlj{width:320px}
.special-biaoti-overflow{max-width:400px;}
.special-biaoti a{font-size:14px;}
.module{width: 120px;}

.extend-buttons{position:absolute;right:0px;top:6px;z-index:1;}
</style>
<script>

$(function ($) {
	$("#removeAll").click(function () {
		var doDelete = function () {
			var param = $('#searchform').serialize();
			$.post('run.php?' + param, {
				a: 'delete_select',
				ajax: 1
			}, function (data) {
				var data = data[0];
				if (data.error) {
					top.jAlert('删除失败!' + data.error, '失败提醒'); 	
				} else{
					top.jAlert('删除成功!' + data.success, '成功提醒'); 	
					location.reload(true);
				}
			}, 'json');
		};
		jConfirm('你确定要删除条件下的所有日志吗？', '删除提醒', function (yes) {
			yes && doDelete();
		}).position(this);
	});
});
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
</div>
<!-- 
<div class="extend-buttons">
	<a class="button_4" id="removeAll">删除</a>
</div>
 -->
<div class="content clear">
 <div class="common-list-content" style="min-width:auto;">
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
          <div class="right v_list_show">
                {template:unit/logssearch}
                <form method="post" action="" name="listform" style="position:relative;">
                   <!-- 标题 -->
                   <ul class="common-list" id="list_head">
                         <li class="common-list-head clear">
                           <div class="common-list-left">
                                <div class="vod-paixu common-list-item"><a class="common-list-paixu1" style="cursor:pointer;"  onclick="hg_switch_order('slist');"  title="排序模式切换/ALT+R"></a></div>
                            </div>
                            <div class="common-list-right">
                                <div class="common-list-item open-close">应用</div>
                                <div class="common-list-item open-close module">模块</div>
                                <!-- <div class="common-list-item open-close">删除</div> -->
                                <div class="common-list-item open-close">操作</div>
                                <div class="common-list-item open-close">操作人</div>
                                <div class="common-list-item open-close">来源</div>
                                <div class="common-list-item open-close">ip</div>
                            </div>
                            <div class="common-list-biaoti ">
						        <div class="common-list-item open-close special-biaoti">标题</div>
					     	</div>
                        </li>
                   
                    </ul>
	               
	                <ul class="common-list hg_sortable_list" id="logslist" data-order_name="orderid">
		                {code}
		                	//print_r($logs_list);
		                {/code}
					    {if $logs_list[0]}
		       			    {foreach $logs_list[0] as $k => $v} 
		                      {template:unit/logslist}
		                    {/foreach}
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有日志信息</p>
						<script>hg_error_html('p',1);</script>
		  				{/if}
	                </ul>
		            
		            <ul class="common-list">
				     <li class="common-list-bottom clear">
					  <!-- <div class="common-list-left">
		                   <input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
		                   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');"    name="batdelete">删除</a>
					   </div>-->
		               {$pagelink}
		            </li>
		          </ul>	
		          <div class="edit_show">
					<span class="edit_m" id="arrow_show" style="position:absolute;"></span>
					<div id="edit_show"></div>
				  </div>
    			</form>
    			
           </div>
        </div>
</div></body>
{template:foot}