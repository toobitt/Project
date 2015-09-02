{template:head}
{code}
$_INPUT['title'] = "点播视频上传";
$params = json_encode($_INPUT);
{/code}

<script type="text/javascript">
  var params = '{$params}';
  function vod_upload()
  {
	  hg_upload_template(params);
  }


     $(function(){

      $(window).resize(function(){
    	  hg_get_size();		
       });

      
       var menuYloc =  parseInt($(window).height())/2 - 275;
		$(window).scroll(function (){  
			var offsetTop = menuYloc + $(this).scrollTop();
			$("#player_container_o").animate({top : offsetTop },{ duration:600 , queue:false });  
		});  

     });

</script>

{code}
	$list = $vodinfo_list;
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
  
   $(document).ready(function(){
	   
	tablesort('vodlist','drag_order');
	     
   	$("#search_list").focus(function(){
   		$("#search").addClass("search_width");
   		
   	});
   	
   	$("#search_list").blur(function(){
   		$("#search").removeClass("search_width");
   	  });	

     var h = parseInt($(window).height())/4;
     var w = parseInt($(window).width())/2; 
   
     var sw = parseInt($('#collect_info').width())/2;
     $("#collect_info").css({'left':w-sw,'top':h});

   });


</script>
<body class="biaoz"  style="position:relative;"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
    <div class="button_op">
	    <input type="button"  value="上传视频"  class=" button_4" onclick="hg_add_single_video({$_INPUT['mid']});" />
	    <input type="button"  id="vod_upload"  value="批量新增"  onclick="vod_upload();" class=" button_4" />
	    <input type="button"  value="切换到列表"  class=" button_4"  onclick="hg_change_list('vodlist');" />
    </div>
</div>

<div class="content clear">
<form method="post" action="" name="listform">
 <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td valign="top" style="background:url({$image_resource}ybz_title.png) top repeat-x;min-width:931px;">
          <div class="right">
                <div class="search_a">
                
                  <form name="searchform" action="" method="get" >
                    <div class="right_1">
						    <select name="trans_status" class="select_box">
						      <option value=-1>全部</option>
						      {foreach $video_status as $k => $v}
						      {if $k == $_INPUT['trans_status']}
						      <option value="{$k}" selected="selected">{$v}</option>
						      {else}
						      <option value="{$k}">{$v}</option>
						      {/if}
						      {/foreach}
						    </select>
							<div class="input" style="width:114px;">
								<span class="input_left"></span>
								<span class="input_right"></span>
								<span class="input_middle">
									<input type="text" id="start_time" name="start_time"  autocomplete="off" value="{$_INPUT['start_time']}" size="12" onfocus="return showCalendar('start_time', 'y-mm-dd');"/>
								</span>
							</div>
							<div class="input" style="width:114px" >
								<span class="input_left"></span>
								<span class="input_right"></span>
								<span class="input_middle" >
									<input type="text" id="end_time" name="end_time" size="12"  autocomplete="off" value="{$_INPUT['end_time']}" onfocus="return showCalendar('end_time', 'y-mm-dd');" />
								</span>
							</div>
							<input type="hidden" name="a" value="show" />
							<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
							<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />

                    </div>
                    <div class="right_2">
                    	<div class="button">
                            	<span class="button_left"></span>
                                <span class="button_right"></span>
                                 <span class="button_middle"><input type="submit" name="hg_search"  value="搜索"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;" /></span>
                                
                        </div>
                    	<div class="search input clear" id="search">
                            	<span class="input_left"></span>
                                <span class="input_right"></span>
                                <span class="input_middle"><em></em><input type="text" name="k" id="search_list"   value="{$_INPUT['k']}" onfocus="if(this.value=='关键字') this.value=''" onblur="if (this.value=='') this.value='关键字'" /></span>
                        </div>
                        
                    </div>
                    </form>
                </div>
                <div class="list_first clear"  id="list_head">
                    	<span class="left"><a class="lb"><em></em></a><a class="slt">缩略图</a><a class="bf">播放</a></span>
                        <span class="right"><a class="fb">发布</a><a class="ml">码流</a><a class="fl">分类</a><a class="zt">状态</a><a class="tjr">添加人/时间</a></span><a class="title">标题</a>
                </div>
                <ul class="list" id="vodlist">
				{if $list}
       			{foreach $list as $k => $v} 
       			 
                 <li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['video_order_id']}"   onclick="hg_row_interactive(this, 'click', 'cur');" onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
                    	<span class="left"><a class="lb"><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" /></a><a class="slt"><img src="{$v['img']}" width="40" height="30"  onclick="hg_get_img({$v['vodid']},{$v['id']});" /></a><a class="bf"><em class="current"  onclick="hg_play_video({$v['vodid']});"></em></a></span>
                        <span class="right"><a class="fb"><em class="b2" onmouseover="hg_fabu('fabu_{$v[id]}');"  onmouseout="hg_back_fabu('fabu_{$v[id]}');"></em></a><a class="ml"><em>{$v['bitrate']}</em></a><a class="fl"><em class="color_green">{$v['vod_sort_id']}</em></a><a class="zt"><em><sup id="text_{$v['id']}">{$v['status']}</sup><sub id="tool_{$v['id']}" style="display:none;"><span id="status_{$v['id']}" style="width:0px;"></span></sub></em></a><a class="tjr"><em>{$v['addperson']}</em><span>{$v['create_time']}</span></a><span class="fb_column" style="display:none;"    id="fabu_{$v['id']}"   onmouseover="hg_fabu('fabu_{$v[id]}')"  onmouseout="hg_back_fabu('fabu_{$v[id]}')" ><span class="fb_column_l"></span><span class="fb_column_r"></span><span class="fb_column_m"><em></em><span class="fsz">发送至栏目：</span><a>国内</a>，<a>国外</a></span></span></span><span class="title">{if $v['collects']}<em onclick="hg_get_collect_info({$v['id']},{$_INPUT['mid']});" ></em>{/if}<a href="javascript:void(0);" onclick="check_menu({$v['id']});" id="t_{$v['id']}">{$v['title']}<strong>{$v['duration']}</strong></a></span>
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
                                        <span class="button_middle"><a  href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}">编辑</a></span>
                                </li>
                                <li class="button">
                                        <span class="button_left"></span>
                                        <span class="button_right"></span>
                                        <span class="button_middle"><a onclick="return hg_ajax_post(this, '删除', 1);" title="" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}">删除</a></span>
                                </li>
                                <li class="button">
                                        <span class="button_left"></span>
                                        <span class="button_right"></span>
                                        <span class="button_middle"><a href="./run.php?mid={$_INPUT['mid']}&a=audit&id={$v['id']}&audit=1" onclick="return hg_ajax_post(this, '审核', 1);">审核</a></span>
                                </li>
                                <li class="button">
                                        <span class="button_left"></span>
                                        <span class="button_right"></span>
                                        <span class="button_middle"><a href="./run.php?mid={$_INPUT['mid']}&a=audit&id={$v['id']}&audit=0" onclick="return hg_ajax_post(this, '打回', 1);">打回</a></span>
                                </li>
                                
                                <li class="button">
                                        <span class="button_left"></span>
                                        <span class="button_right"></span>
                                        <span class="button_middle"> <a href="./run.php?mid={$_INPUT['mid']}&a=move&id={$v['id']}"  onclick="return hg_ajax_post(this, '移动', 1);">移动</a></span>
                                </li>
                            </ul>
                                <p><a href="./run.php?mid={$_INPUT['mid']}&a=add_to_collect&id={$v['id']}" onclick="return hg_ajax_post(this, '添加至集合', 1);">添加至集合</a></p>
                                <p><a href="#">发布至网站</a></p>
                            </div>
                        </div>
                    </li>
                    {/foreach}
	  				{/if}
                </ul>
            </div>
            <div class="bottom clear">
               <div class="left">
                   <input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
                   <a onclick="return hg_ajax_batchpost(this, 'audit',  '审核', 1, 'id', '&audit=1', 'ajax');"    name="bataudit" >审核</a>
			       <a  onclick="return hg_ajax_batchpost(this, 'audit',  '打回', 1, 'id', '&audit=0', 'ajax');"   name="batgoback" >打回</a>
			       <a  onclick="return hg_ajax_batchpost(this, 'move',  '移动', 1, 'id', '', 'ajax');"    name="batmove" >移动</a>
			       <a  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>
			       <a  onclick="return hg_ajax_batchpost(this, 'add_to_collect', '添加到集合', 1, 'id', '', 'ajax');"   name="batadd_to_collect">添加到集合</a>
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
  
   <div id="collect_info"  style="display:none;width:400px;height:auto;border:10px solid #B2B2B2;position:absolute;left:0px;top:0px;background:white;border-radius:6px;"></div>
</body>

{template:foot}

<script type="text/javascript">

var id="";
var vodid = "";
var status = "";
var transize = "";
var totalsize = "";

function hg_panduan(json)
{
	 obj = json[0];
	  if(obj.length)
	  {
		  for(var i = 0;i<obj.length;i++)
		  {
			  id = obj[i].id;
			  vodid = obj[i].vodid;
			  status = obj[i].status;
			  transize = obj[i].transize;
			  totalsize = obj[i].totalsize;

			 $("#request_videoinfo").remove();
             if(!status)
             {
                 if(id)
                 {
                	  $("#text_"+id).text("转码中");
                	  $("#tool_"+id).css("display","block");
	   	           	  var tool = $("#tool_"+id).css("width");
	   	           	  var tool_width = Math.round((transize/totalsize)*parseInt(tool));
	   	           	  $("#status_"+id).css("width",tool_width);
	   	           	 
                 }
	             
             }
             else
             {
           	  $("#text_"+id).text("待审核");
             	  $("#tool_"+id).css("display","none");
             }
           
		   }

		   setTimeout("hg_getvideoinfo();", 5000);
	  }   

 }

hg_getvideoinfo();

</script>
































