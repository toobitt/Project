{template:head}

{code}
    $formdata = $vod_collect_video_list[0];
	$image_resource = RESOURCE_URL;

	if(!isset($_INPUT['trans_status']))
	{
	    $_INPUT['trans_status'] = -1;
	}
{/code}

{css:vod_style}
{js:jquery.ui.core}
{js:jquery.ui.widget}
{js:jquery.ui.mouse}
{js:jquery.ui.sortable}
{js:vod_opration}

<script type="text/javascript">

   hg_resize_nodeFrame();

   $(document).ready(function(){
 
	tablesort('list','drag_order');

	$(window).resize(function(){
		  hg_get_size();		
	 });
	
	
	 var menuYloc =  parseInt($(window).height())/2 - 275;
		$(window).scroll(function (){  
			var offsetTop = menuYloc + $(this).scrollTop();
			$("#player_container_o").animate({top : offsetTop },{ duration:600 , queue:false });  
	  });  
	
   	$("#search_list").focus(function(){
   		$("#search").addClass("search_width");
   		
   	});
   	
   	$("#search_list").blur(function(){
   		$("#search").removeClass("search_width");
   	  });	

   });

</script>
<style type="text/css">
   .head_style{width:100%;height:50px;background:#E6F3FC;border:1px solid #B9DBF7;}
   .head_content{float:left;font-size:13px;margin-left:10%;margin-top:15px;}
   .player_style_o{position:absolute;z-index:10;border:13px solid #B2B2B2;border-radius:6px;}
   .player_style_c{border:13px solid black;background:black;}
   .close_player{display:block;width:30px;height:30px;position:absolute;left:609px;top:-14px;z-index:20;display:none;}
</style>

<body class="biaoz">

<div class="head_style">
  <div class="head_content" ><label><font color="#7B7D7C">视频集合：</font></label>{$formdata['collect']['collect_name']}</div>
  <div class="head_content" ><label><font color="#7B7D7C">分类：</font></label>{$formdata['collect']['vod_sort_id']}</div>
  <div class="head_content" ><label><font color="#7B7D7C">视频数量：</font></label>{$formdata['collect']['count']}</div>
  <div class="head_content" ><label><font color="#7B7D7C">来源：</font></label>{$formdata['collect']['source']}</div>
  <div class="head_content" ><label><font color="#7B7D7C">最后更新：</font></label>{$formdata['collect']['update_time']}</div>
</div>

<div class="content clear">
<form method="post" action="" name="listform">
 <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td valign="top" style="background:url({$image_resource}ybz_title.png) top repeat-x;border-left:1px solid #d8d8d8;min-width:931px;">
          <div class="right">
                
                <div class="list_first clear"  id="list_head">
                    	<span class="left"><a class="lb"><em></em></a><a class="slt">缩略图</a><a class="bf">播放</a></span>
                        <span class="right"><a class="fb">发布</a><a class="ml">码流</a><a class="fl">分类</a><a class="zt">状态</a><a class="tjr">添加人/时间</a></span><a class="title">标题</a>
                </div>
                <ul class="list" id="list">
               {if $formdata['collect_video']}
       			{foreach $formdata['collect_video'] as $k => $v} 
       			 
                 <li class="clear"  id="r_{$v['id']}"  name="{$v['cid']}"  orderid="{$v['order_id']}"   onclick="hg_row_interactive(this, 'click', 'cur');" onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
                    	<span class="left"><a class="lb"><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}" /></a><a class="slt"><img src="{$v['img']}" width="40" height="30"   /></a><a class="bf"><em class="current"  onclick="hg_play_video({$v['vodid']});"></em></a></span>
                        <span class="right"><a class="fb"><em class="b2"></em></a><a class="ml"><em>{$v['bitrate']}</em></a><a class="fl"><em class="color_green">{$v['vod_sort_id']}</em></a><a class="zt"><em><sup id="text_{$v['id']}">{$v['status']}</sup><sub id="tool_{$v['id']}" style="display:none;"><span id="status_{$v['id']}" style="width:0px;"></span></sub></em></a><a class="tjr"><em>{$v['addperson']}</em><span>{$v['create_time']}</span></a></span><span class="title"><a href="javascript:void(0);" onclick="check_menu({$v['id']});" id="t_{$v['id']}">{$v['title']}<strong>{$v['duration']}</strong></a></span>
                        <div class="content_more clear" id="content_{$v['id']}" style="display:none">
                            	<ul class="content_more_left">
                                	<li>来&nbsp;&nbsp;&nbsp;&nbsp;源：<span>{$v['source']}</span></li>
                                    <li>分&nbsp;&nbsp;&nbsp;&nbsp;类：<span>{$v['vod_leixing']} > {$v['vod_sort_id']}</span></li>
                                    <li>关键字：<span>{$v['keywords']}</span></li>
                                    <li>发布至：<span>新闻综合频道</span></li>
                                    <li class="more">描&nbsp;&nbsp;&nbsp;&nbsp;述：<span>{$v['comment']}</span></li>
                                </ul>
                            <div class="content_more_right">
                            <ul>
                   
                                <li class="button">
                                        <span class="button_left"></span>
                                        <span class="button_right"></span>
                                        <span class="button_middle" ><a onclick="return hg_ajax_post(this, '删除', 1);" title="" href="./run.php?mid={$relate_module_id}&a=remove&collect_id={$formdata['collect']['id']}&id={$v['id']}">移出</a></span>
                                </li>
                               
                            </ul>
                              
                            </div>
                        </div>
                    </li>
                    {/foreach}
	  				{/if}
                </ul>
            </div>
            <div class="bottom clear">
               <div class="left"><a class="lb"><input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" />
                  <a  onclick="return  hg_batchremove(this, 'remove', '移除集合', 1, 'id', '', 'ajax',{$relate_module_id},{$formdata['collect']['id']});"   name="batremove"  style="cursor:pointer;">移除集合</a> 
			   </div>
               <div>{$pagelink}</div>
            </div>	
        </td>
      </tr>
    </table>
  </form>
</div>
 <div id="player_container_o">
     <h5 class="close_player"  id="close_player"><img src="{$image_resource}close_play.png" width="30px;" height="30px;"  style="cursor:pointer;"  id="close_video"   onclick="hg_close_video();"  onmouseover="hg_chang_pic(this,'close_play_glow.png');"   onmouseout="hg_back_pic(this,'close_play.png');" /></h5>
	 <div id="player_container_c">
	    <div id="player"></div>
	 </div>    
  </div>

</body>

{template:foot}
