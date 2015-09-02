{template:head}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:share}
{css:vod_style}
{css:edit_video_list}
{code}
if(!isset($_INPUT['module_uniqueid']))
{
    $_INPUT['module_uniqueid'] = '-1';
}
if(!isset($_INPUT['douser_id']))
{
    $_INPUT['douser_id'] = '-1';
}
if(!isset($_INPUT['stat_type']))
{
    $_INPUT['stat_type'] = '-1';
}
if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = '-1';
}
$app = $statlist[0]['app'];
{/code}
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
<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
	            <a class="gray mr10" href="run.php?mid={$_INPUT['mid']}&a=configuare&infrm=1" target="mainwin">
	                <span class="left"></span>
	                <span class="middle"><em class="set">工作量统计</em></span>
	                <span class="right"></span>
	             </a>
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
          <div class="right v_list_show">
                <div class="search_a" id="info_list_search">
                  <form name="searchform" id="searchform" action="./run.php?mid={$_INPUT['mid']}&a=get_record&post_id={$v['post_id']}" method="get" >
                    <div class="right_1">
                    {code}
						$attr_app = array(
							'class' => 'transcoding down_list',
							'show' => 'app_type_show',
							'width' => 104,/*列表宽度*/
							'state' => 0,/*0--正常数据选择列表，1--日期选择*/
						);
						foreach($statlist[0]['app'] AS $k => $v)
						{
							$app_arr[$v['mod_uniqueid']] = $v['name'];
						}
						$app_arr[-1] = '模块';
						
						$attr_douser = array(
							'class' => 'transcoding down_list',
							'show' => 'douser_type_show',
							'width' => 104,/*列表宽度*/
							'state' => 0,/*0--正常数据选择列表，1--日期选择*/
						);
						
						$douser_arr = array();
						foreach($statlist[0]['user'] AS $k => $v)
						{
							$douser_arr[$v['user_id']] = $v['user_name'];
						}
						$douser_arr['-1'] = '用户';
						$attr_stat_type = array(
							'class' => 'transcoding down_list',
							'show' => 'stat_type_type_show',
							'width' => 104,/*列表宽度*/
							'state' => 0,/*0--正常数据选择列表，1--日期选择*/
						);
						
						$_configs['statistics_type_cn'][-1] = '操作方式';
						
						$attr_create_date = array(
							'class' => 'colonm down_list data_time',
							'show' => 'create_show',
							'width' => 104,/*列表宽度*/
							'state' => 1,/*0--正常数据选择列表，1--日期选择*/
						);
						
					{/code}
					{template:form/search_source,module_uniqueid,$_INPUT['module_uniqueid'],$app_arr,$attr_app}
					{template:form/search_source,douser_id,$_INPUT['douser_id'],$douser_arr,$attr_douser}
					{template:form/search_source,stat_type,$_INPUT['stat_type'],$_configs['statistics_type_cn'],$attr_stat_type}
					{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_create_date}
						<input type="hidden" name="a" value="show" />
						<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
						<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
						<input type="hidden" name="_id" value="{$statlist[0]['app_uniqueid']}" />
                    </div>
                    </form>
                </div>
                <div class="list_first clear"  id="list_head">
                    	<span class="left"><a class="lb">&nbsp</a>
                    	<a class="sharesltmix">模块</a>
                    	<a class="sharesltmix">内容ID</a>
                    						<a class="sharesltmix">内容归属人</a>
                    						<a class="sharesltmix">内容操作者</a>
                    						<a class="sharesltmix">操作类型</a><a class="shareslt">操作时间</a>
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