{template:head}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:share}
{css:vod_style}
{css:edit_video_list}
{code}
//print_r($deploy);
{/code}
<script>
function formsubmit(id)
{
	document.getElementById(id).submit();
}
</script>
{code}
if(!class_exists('column'))
{
    include_once(ROOT_DIR . 'lib/class/column.class.php');
    $publish = new column();
}
{/code}
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
				$time_css = array(
					'class' => 'transcoding down_list',
					'show' => 'time_item',
					'width' => 120,	
					'state' => 1,/*0--正常数据选择列表，1--日期选择*/
					'para'=> array('fid'=>$_INPUT['fid']),
				);
				$_INPUT['create_time'] = $_INPUT['create_time'] ? $_INPUT['create_time'] : 1;
				
				if(!$_INPUT['site_id'])
				{
					$_INPUT['site_id'] = 1;
				}
				//获取所有站点
				$hg_sites = array();
				foreach ($publish->getallsites() as $index => $value) {
					$hg_sites[$index] = $value;
				}
				$attr_site = array(
					'class'  => 'colonm down_list date_time',
					'show'   => 'app_show',
					'width'  => 104,
					'state'  => 0,
				);
				
				
		{/code}	
		{template:form/search_source,site_id,$_INPUT['site_id'],$hg_sites,$attr_site}
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
        
        <div style="width:150px;height:800px;border-right:#E0E0E0 solid 1px;">
    <!--    	 {foreach $mkpublish[0] as $v}
                 	<li >
                 	<a href="./run.php?mid={$_INPUT['mid']}&a=get_page_type&infrm=1&site_id={$v['id']}" target="page_type_iframe">
                 	{$v['site_name']}
                 	</a>
                 	<a href="./run.php?mid={$_INPUT['mid']}&a=deploy_form&infrm=1&site_id={$v['id']}" target="set_iframe">
                 	<img src="{$RESOURCE_URL}vote_opearte.png">
                 	</a>
                 	</li>
                 {/foreach}
     -->
     			<a href="./run.php?mid={$_INPUT['mid']}&a=mkpublish_form&infrm=1&site_id={$mkpublish[0]['site_id']}&deploy_name={$mkpublish[0]['site_name']}"  target="set_iframe">
                 {$mkpublish[0]['site_name']}
                 </a>
                 &nbsp;&nbsp;&nbsp;
                 <a href="./run.php?mid={$_INPUT['mid']}&a=open_url&infrm=1&site_id={$mkpublish[0]['site_id']}&deploy_name={$mkpublish[0]['site_name']}">
                 浏览
                 </a><br>
                 {foreach $mkpublish[0]['page_list'] as $v}
                 	<a href="./run.php?mid={$_INPUT['mid']}&a=mkpublish_form&infrm=1&page_id={$v['id']}&deploy_name={$v['title']}"  target="set_iframe">
                 	{$v['title']}
                 	</a>
                 	<a href="./run.php?mid={$_INPUT['mid']}&a=get_page_data&infrm=1&page_id={$v['id']}" target="page_data_iframe">
                 	>
                 	</a>
                 	<br>
                 {/foreach}
        </div>
        <div style="width:150px;height:800px;margin-left:150px;margin-top:-800px;border-right:#E0E0E0	 solid 1px;">
             <iframe class="iframe" src=""   name="page_data_iframe" id="page_data_iframe"></iframe>
        </div>
        <div style="height:800px;margin-left:310px;margin-top:-800px;">
        <form  action="" method="post"  class="ad_form h_l" name="mkmaterial_form" id="mkmaterial_form">
        <input type="hidden" name="a" value="mk_material" />
				<input type="hidden" name="site_id" value="{$_INPUT['site_id']}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
        <input type="button" onclick="hg_ajax_submit('mkmaterial_form')" name="sub" value="生成模板素材" class="button_6_14" />
        </form>
        <br>
        <form  action="" method="post"  class="ad_form h_l" name="mkframe_form" id="mkframe_form">
        <input type="hidden" name="a" value="mk_frame" />
				<input type="hidden" name="site_id" value="{$_INPUT['site_id']}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
        <input type="button" onclick="hg_ajax_submit('mkframe_form')" name="sub" value="生成基础框架" class="button_6_14" />
        </form>
             <iframe class="iframe2" src="" scrolling="No" noresize="noresize" name="set_iframe" id="set_iframe"></iframe>
        </div>
</div>

</body>
{template:foot}