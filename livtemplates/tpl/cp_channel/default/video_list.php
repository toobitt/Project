{template:head}
{code}

$list = $video_list[0]['video'];
$channel = $video_list[0]['channel'];
$image_resource = RESOURCE_URL;

if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}

if(!isset($_INPUT['video_state']))
{
    $_INPUT['video_state'] = -1;
}

{/code}
{css:vod_style}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:channel_video}
{js:mblog}
<script type="text/javascript">
   $(document).ready(function(){
		tablesort('pictures_list','pictures','order_id');
		$("#pictures_list").sortable('disable');
   });
   function hg_video_gb()
   {
		$('#flashhandler').animate({'top':'-463px'},1000,function(){
			$('#flashhandler').hide();
		});
   }
   function hg_check_boxok(id)
   {
	  if($('#select_'+id).attr('checked'))
	  {
		  $('#right_'+id).show();
	  }
	  else
	  {
		  $('#right_'+id).hide();
	  }
   }
    function hg_show_tips(id,e)
   {
	   if(e)
	   {
		    $('#tips_'+id).show();
			$('#delete_'+id).show();
			$('#update_'+id).show();
			$('#pub_'+id).show();
			$('#r_'+id).css('border','1px solid #90bff3');
	   }
	   else
	   {
		   $('#tips_'+id).hide();
		   $('#delete_'+id).hide();
		   $('#update_'+id).hide();
		   $('#pub_'+id).hide();
		   $('#r_'+id).css('border','1px solid #fff');
	   }
   } 
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
		<span type="button" class="button_6" ><strong>新增频道</strong></span>
</div>
<div class="content clear">
 <div class="f">
          <div class="right v_list_show">
                <div class="search_a" id="info_list_search">
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
                    <div class="right_1">
						{code}
							$attr_state = array(
								'class' => 'transcoding down_list',
								'show' => 'state_show',
								'width' => 80,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
								'is_sub'=> 0,
							);
							
							$attr_date = array(
								'class' => 'colonm down_list data_time',
								'show' => 'colonm_show',
								'width' => 104,/*列表宽度*/
								'state' => 1,/*0--正常数据选择列表，1--日期选择*/
							);
							
						{/code}
						{template:form/search_source,video_state,$_INPUT['video_state'],$_configs['video_state'],$attr_state}
						{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
						<input type="hidden" name="a" value="show" />
						<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
						<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
						<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
						<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
                    </div>
                    <div class="right_2">
                    	<div class="button_search">
							<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
                        </div>
						{template:form/search_input,k,$_INPUT['k']}                        
                    </div>
                    </form>
                </div>
				
				{if isset($channel)}
                <div class="list_first clear" style="height:90px;border-bottom:1px dotted #ccc;"> 
                	<div style="width:80px;height:80px;float:left;margin-left:12px;">
                		<img src="{$channel['logo_url']}" width="80" height="80" />
                	</div>
                	<div style="width:850px;height:100%;float:left;margin-left:18px;">
                		<div style="width:280px;float:left;">
                			<div style="float:left;color:#727272;">名称：</div>
                			<div style="float:left;">{$channel['web_station_name']}</div>
                		</div>
                		
                		<div style="width:280px;float:left;">
                			<div style="float:left;color:#727272;">创建人：</div>
                			<div style="float:left;">{$channel['username']}</div>
                		</div>
                		
                		<div style="width:280px;float:left;">
                			<div style="float:left;color:#727272;">标签：</div>
                			<div style="float:left;">{$channel['tags']}</div>
                		</div>
                		
                		<div style="width:280px;float:left;">
                			<div style="float:left;color:#727272;">关注：</div>
                			<div style="float:left;">{$channel['click_count']}</div>
                		</div>
                		
                		<div style="width:280px;float:left;">
                			<div style="float:left;color:#727272;">创建时间：</div>
                			<div style="float:left;">{$channel['create_time']}</div>
                		</div>
						<div style="width:280px;float:left;">
                			<div style="float:left;color:#727272;">简介：</div>
                			<div style="float:left;">{$channel['brief']}</div>
                		</div>
                	</div>
                </div>
				{/if}

                <form method="post" action="" name="listform">
                <ul class="list_img" id="pictures_list">
				    {if $list}
	       			    {foreach $list as $k => $v}
	                      {template:unit/videolist}
	                    {/foreach}
					{else}
					<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">未找到视频！</p>
					<script>hg_error_html(pictures_list,1);</script>
	  				{/if}
					<!--<li style="height:0px;padding:0;" class="clear"></li>  -->
                </ul>
	            <div class="bottom clear">
					<div class="left" style="width:400px;">
	                   <input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
				       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>
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
   <div id="flashhandler" style="z-index:9999;width:39%;height:auto;border:2px solid grey;background:black;position:absolute;left:27%;top:-56%;display:none;">
   
   </div>
<!--发布模板-->
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
<!--发布-->
</body>
<script type="text/javascript" src="http://video.hcrt.cn/flash-player/swfobject.js"></script>
<script type="text/javascript" src="http://video.hcrt.cn/flash-player/tvieplayer.js"></script>
{template:foot}