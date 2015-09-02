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
if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = '-1';
}
$app = $stat_user[0]['app'];
//print_r($stat_user);
{/code}
<script type="text/javascript">
function delete_record(user_id,module_id,date_search,start_time,end_time)
{
	if(!confirm('是否要清空'))
	{
		return false;
	}
	var url = "run.php?mid="+gMid+"&a=delete&stat_user_id="+user_id+"&module_id="+module_id+"&date_search="+date_search+"&start_time="+start_time+"&end_time="+end_time;
	hg_ajax_post(url);
	var basic = document.getElementsByName("stat_op_type_"+user_id);
	for(var i=0;i<basic.length;i++)
	{
		basic[i].innerHTML = '<font color=red>0</font>';
	}
	$('#stat_total_'+user_id).html('<font color=red>0</font>');
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
						$attr_app = array(
							'class' => 'transcoding down_list',
							'show' => 'app_type_show',
							'width' => 104,/*列表宽度*/
							'state' => 0,/*0--正常数据选择列表，1--日期选择*/
						);
						foreach($stat_user[0]['app'] AS $k => $v)
						{
							$app_arr[$v['bundle']] = $v['name'];
						}
						$app_arr[-1] = '模块';
						
						$attr_create_date = array(
							'class' => 'colonm down_list data_time',
							'show' => 'create_show',
							'width' => 104,/*列表宽度*/
							'state' => 1,/*0--正常数据选择列表，1--日期选择*/
						);
						
					{/code}
					{template:form/search_source,module_uniqueid,$_INPUT['module_uniqueid'],$app_arr,$attr_app}
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
                    	<a class="sharesltmix">用户</a>
                    	{foreach $_configs['statistics_type_cn'] as $k=>$v}
                    	<a class="statistic_user">{$v}</a>
                    	{/foreach}
                    	<a class="statistic_user">总计</a>
                    	<a class="statistic_user">操作</a>
                        <span class="right"></span>
                </div>
                <form method="post" action="" name="listform">
	                <ul class="list" id="tujilist">
					    {if $stat_user[0]['user']}
		       			    {foreach $stat_user[0]['user'] as $k=>$v} 
		                      {template:unit/stat_user_list}
		                    {/foreach}
						{else}
		  				{/if}
						<li style="height:0px;padding:0;" class="clear"></li>
	                </ul>
		            <div class="bottom clear">
		               <div class="left" style="width:400px;">
		                   <input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
		                   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');"    name="batdelete">清空</a>
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