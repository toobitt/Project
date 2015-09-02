{template:head}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:share}
{css:edit_video_list}
{css:common/common_list}
{css:vod_style}
{js:common/common_list}
<script>
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
<div id="hg_page_menu" class="head_op_program">
	<a href="?mid={$_INPUT['mid']}&a=set_form&infrm={$_INPUT['infrm']}&_id={$_INPUT['_id']}&column_fid={$column[0]['column_fid']}" class="add-button mr10">添加配置</a>
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
          <div class="right v_list_show" style="background: #fff;">
                <div class="search_a" id="info_list_search">
                 <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
                    <div class="right_2">
                    	<div class="button_search">
							<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
                        </div>
                        {template:form/search_input,keyword,$_INPUT['keyword']} 
                    </div>
                    </form>
                    {foreach $set_list[0]['father_set'] as $k=>$v}
                    <form name="searchform{$k}" id="searchform{$k}" action="" method="get" onsubmit="return hg_del_keywords();">
                    <div class="right_1" {if $k!=0}style="margin-left:-200px"{/if}>
							{code}
								$selectnum = $k;
								$column_id = "set_id".$k;
								if(!isset($_INPUT[$column_id]))
								{
									$_INPUT[$column_id] = $v['select_column'];
								}
								$column_arr = array(
								'class' => 'colonm down_list data_time',
								'show' => 'colonm_show'.$k,
								'width' => 104,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
								'id' => $k,
								);
								$idname = array();
								$idname['-1'] = '全部';
								unset($v['select_column']);
								foreach($v AS $kk => $vv)
								{
									$idname[$vv['id']] = $vv['name'];
								}
							{/code}
							{template:form/column_search_source,$column_id,$_INPUT[$column_id],$idname,$column_arr}
						<input type="hidden" name="a" value="show" />
						<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
						<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
						<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
						<input type="hidden" name="selectnum" value="{$selectnum}" />
                    </div>
                    </form>
                    {/foreach}
                </div>
                <form method="post" action="" name="listform">
                   <ul class="common-list" id="list_head">
                        <li class="common-list-head clear public-list-head">
                            <div class="common-list-left">
                                <div class="common-list-item paixu"></div>
                            </div>
                            <div class="common-list-right">
                                <div class="common-list-item wd300">栏目链接</div>
                                <div class="common-list-item wd50">编辑</div>
                                <div class="common-list-item wd50">删除</div>
                                <div class="common-list-item wd150">添加时间</div>
                            </div>
                            <div class="common-list-biaoti ml35">
						        <div class="common-list-item">栏目名称</div>
					        </div>
                        </li>
                     </ul>
	                <ul class="common-list public-list" id="tujilist">
					    {if $set_list[0]['set_data']}
		       			    {foreach $set_list[0]['set_data'] as $k => $v} 
		                      {template:unit/setlist}
		                    {/foreach}
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有配置！</p>
						<script>hg_error_html(setlist,1);</script>
		  				{/if}
	                </ul>
		            <ul class="common-list">
				      <li class="common-list-bottom clear">
						   <div class="common-list-left">
		                    <input type="checkbox"  name="checkall"  value="infolist" title="全选" rowtag="LI" />
		                    <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');"    name="batdelete">删除</a>
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