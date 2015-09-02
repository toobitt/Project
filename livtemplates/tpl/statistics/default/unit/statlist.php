{template:head}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:share}
{css:vod_style}
{css:edit_video_list}
<script type="text/javascript">
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
</style>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
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
          <div class="right v_list_show">
                <div class="search_a" id="info_list_search">
                  <form name="searchform" id="searchform" action="./run.php?mid={$_INPUT['mid']}&a=get_record&post_id={$v['post_id']}" method="get" >
                    <div class="right_1">
                    
                    	{code}
						{/code}
						<select style="width:90px;height:25px;" name="module_uniqueid">
							<option value="all" {code}if($statlist[0]['module_uniqueid']=='all') echo "selected";{/code}>全部</option>
							{foreach $statlist[0]['app'] as $k1=>$v1}
							<option value="{$v1['bundle']}" {code}if($statlist[0]['module_uniqueid']==$v1['bundle']) echo "selected";{/code}>{$v1['name']}</option>
							{/foreach}
						</select>
						<select style="width:90px;height:25px;" name="douser_id">
							<option value="all" {code}if($statlist[0]['douser_id']=='all') echo "selected";{/code}>全部</option>
							{foreach $statlist[0]['user'] as $k1=>$v1}
							<option value="{$v1['user_id']}" {code}if($statlist[0]['douser_id']==$v1['user_id']) echo "selected";{/code}>{$v1['user_name']}</option>
							{/foreach}
						</select>
						<select style="width:90px;height:25px;" name="stat_type">
							<option value="all" {code}if($statlist[0]['stat_type']=='all') echo "selected";{/code}>全部</option>
							{foreach $_configs['statistics_type_cn'] as $k1=>$v1}
							<option value="{$k1}" {code}if($statlist[0]['stat_type']==$k1) echo "selected";{/code}>{$v1}</option>
							{/foreach}
						</select>
						从：<input type="text" name="start_time" id="start_time" style="width:90px;height:15px;" autocomplete="off" size="12" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd'})" value="{code}echo empty($statlist[0]['start_time'])?date('Y-m-d',time()):$statlist[0]['start_time'];{/code}"/>
						到：<input type="text" name="end_time" id="end_time" style="width:90px;height:15px;" autocomplete="off" size="12" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd'})" value="{code}echo empty($statlist[0]['end_time'])?date('Y-m-d',strtotime("+1 day")):$statlist[0]['end_time'];{/code}"/>
						{code}echo "总记录：";echo empty($statlist[0]['num']['total'])?0:$statlist[0]['num']['total'];{/code}
						<div class="button_search">
							<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
                        </div>
						<input type="hidden" name="a" value="show" />
						<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
						<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
						<input type="hidden" name="_id" value="{$statlist[0]['app_uniqueid']}" />
                    </div>
                    </form>
                </div>
                <div class="list_first clear"  id="list_head">
                    	<span class="left"><a class="lb">&nbsp</a><a class="shareslt">内容ID</a>
                    						<a class="shareslt">内容归属人</a><a class="shareslt">内容操作者</a>
                    						<a class="shareslt">操作类型</a><a class="shareslt">操作时间</a>
                    						<a class="shareslt">操作</a></span>
                        <span class="right"></span>
                </div>
                <form method="post" action="" name="listform">
	                <ul class="list" id="tujilist">
					    {if $statlist[0]['record']}
		       			    {foreach $statlist[0]['record'] as $k => $v} 
		                      {template:unit/stat_record}
		                    {/foreach}
						{else}
		  				{/if}
						<li style="height:0px;padding:0;" class="clear"></li>
	                </ul>
		            <div class="bottom clear">
		               <div class="left" style="width:400px;">
		                   <input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
		                   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');"    name="batdelete">删除</a>
					   </div>
		               {$pagelink}
		            </div>
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