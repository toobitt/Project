{template:head}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:share}
{css:vod_style}
{css:edit_video_list}
{code}
//print_r($dep);
{/code}
<script>
function formsubmit(id)
{
	document.getElementById(id).submit();
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
.iframe{width:150px;height:800px;}
.iframe2{width:100%;height:800px;}
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
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
                    <div class="right_1">
						{code}
							if(!isset($_INPUT['site_id']))
							{
								$_INPUT['site_id'] = empty($deploy[0]['site_id'])?'-1':$deploy[0]['site_id'];
							}
							$attr_site = array(
								'class' => 'colonm down_list data_time',
								'show' => 'colonm_show',
								'width' => 104,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
							);
							$site_id = array();
							$site_id['-1'] = '全部';
							foreach($deploy[0]['site_data'] AS $k => $v)
							{
								$site_id[$v['id']] = $v['site_name'];
							}
						{/code}
						{template:form/search_source,site_id,$_INPUT['site_id'],$site_id,$attr_site}
					
						<input type="hidden" name="a" value="show" />
						<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
						<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
						<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
                    </div>
                    <div class="right_2">
                    	<div class="button_search">
							<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
                        </div>
                        {template:form/search_input,keyword,$_INPUT['keyword']} 
                    </div>
                    </form>
                    
    			<div class="edit_show">
				<span class="edit_m" id="arrow_show"></span>
				<div id="edit_show"></div>
				</div>
           </div>
        </div>
        
        <div style="width:150px;height:800px;border-right:#000 solid 1px;">
        	 {foreach $dep[0] as $v}
                 	<li >
                 	<a href="./run.php?mid={$_INPUT['mid']}&a=get_page_type&infrm=1&site_id={$v['id']}" target="page_type_iframe">
                 	{$v['site_name']}
                 	</a>
                 	<a href="./run.php?mid={$_INPUT['mid']}&a=deploy_form&infrm=1&site_id={$v['id']}" target="set_iframe">
                 	<img src="{$RESOURCE_URL}vote_opearte.png">
                 	</a>
                 	</li>
                 {/foreach}
        </div>
        <div style="width:150px;height:800px;margin-left:150px;margin-top:-800px;border-right:#000 solid 1px;">
             <iframe class="iframe" src=""  name="page_type_iframe" id="page_type_iframe"></iframe>
        </div>
        <div style="width:150px;height:800px;margin-left:300px;margin-top:-800px;border-right:#000 solid 1px;">
             <iframe class="iframe" src=""   name="page_data_iframe" id="page_data_iframe"></iframe>
        </div>
        <div style="height:800px;margin-left:450px;margin-top:-800px;">
             <iframe class="iframe2" src="" scrolling="No" noresize="noresize" name="set_iframe" id="set_iframe"></iframe>
        </div>
</div>

</body>
{template:foot}