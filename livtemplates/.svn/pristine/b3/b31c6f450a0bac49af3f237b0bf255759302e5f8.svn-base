{template:head}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:share}
{css:common/common_list}
{js:common/common_list}
{css:vod_style}
{css:edit_video_list}
{code}
//print_r($relate_module_id,$relate_module);
//print_r($_INPUT['fid']);
{/code}
<script type="text/javascript">
	function add(id)
	{
		window.location.href="./run.php?mid={$_INPUT['mid']}&a=add&infrm=1";
	}

	function hg_audit_back(json)
	{
		var obj = eval("("+json+")");
		var con = '';
		if(obj.status == 1)
		{
			con = '已审核';
		}
		else if(obj.status == 2)
		{
			con = '已打回 ';    
		}
		for(var i = 0;i<obj.id.length;i++)
		{
			$('#audit_'+obj.id[i]).text(con);
		}
		if($('#edit_show'))
		{
			hg_close_opration_info();
		}	
	}
</script>
<style type="text/css">
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
.special-slt{width:100px}
.special-ztlj{width:320px}
</style>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
<form action="" method="POST" name="add" id="add">
	<span type="button" class="add-button mr10"  onclick="add()">新增线路</span>
</form>
</div>
<div class="content clear">
 <div class="common-list-content">
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
                {template:unit/linesearch}
                <form method="post" action="" name="listform">
                   <!-- 标题 -->
                   <ul class="common-list public-list-head" id="list_head">
                        <li class="common-list-head clear">
                        	<div class="common-list-left">
                        		<div class="common-list-item common-paixu"></div>
                        	</div>
                            <div class="common-list-right">
                                <div class="common-list-item open-close wd60">操作</div>
                                <div class="common-list-item open-close wd60" >状态</div>
                                <div class="common-list-item open-close wd80">城市名称</div>
                                <div class="common-list-item open-close wd300">运行时间</div>
                            </div>
                            <div class="common-list-biaoti ">
						        <div class="common-list-item open-close special-biaoti">线路名称</div>
					        </div>
                        </li>
                    </ul>
	                <ul class="common-list public-list" id="tujilist">
					    {if $line_list[0]}
		       			    {foreach $line_list[0] as $k => $v} 
		                      {template:unit/linelist}
		                    {/foreach}
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有线路</p>
						<script>hg_error_html(linelist,1);</script>
		  				{/if}
	                </ul>
		            <ul class="common-list">
				     <li class="common-list-bottom clear">
					   <div class="common-list-left">
		                   <input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
		                   <a style="cursor:pointer;margin-left:10px;"  onclick="return hg_ajax_batchpost(this, 'audit', '审核', 1, 'id', '&audit=1', 'ajax');" name="bataudit">审核</a>
						   <a style="cursor:pointer;margin-left:10px;"  onclick="return hg_ajax_batchpost(this, 'audit', '打回', 1, 'id', '&audit=2', 'ajax');" name="bataudit">打回</a>
						   <a style="cursor:pointer;margin-left:10px;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" name="batdelete">删除</a>
					   </div>
		               {$pagelink}
		            </li>
		          </ul>	
    			</form>
    			<div class="edit_show">
				<span class="edit_m" id="arrow_show"></span>
				<div id="edit_show"></div>
				</div>
           </div>
        </div>
</div>
   <div id="infotip"  class="ordertip"></div>
   <div id="getimgtip"  class="ordertip"></div>
</body>
{template:foot}