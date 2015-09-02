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
	$list = $vodinfo_list_html[0];
	$image_resource = RESOURCE_URL;
{/code}

{code}
$states = array(-1 => '全部', 2=> '待审核', 1 => '审核通过', 0 => '审核不过');
if(!isset($_INPUT['k']))
{
	$_INPUT['k'] = '关键字';
	$onclick = ' onfocus="if(this.value==\'关键字\') this.value=\'\'" onblur="if (this.value==\'\') this.value=\'关键字\'"';
}
if (!isset($_INPUT['state']))
{
	$_INPUT['state'] = -1;
}
{/code}

<style type="text/css">
  .player_style_o{position:absolute;z-index:10;border:13px solid #B2B2B2;border-radius:6px;}
  .player_style_c{border:13px solid black;background:black;}
  .close_player{display:block;width:30px;height:30px;position:absolute;left:609px;top:-14px;z-index:20;display:none;}
</style>
{css:vod_style}
{js:jquery.ui.core}
{js:jquery.ui.widget}
{js:jquery.ui.mouse}
{js:jquery.ui.sortable}

<script type="text/javascript">
   $(function() {
        $("#list").sortable({delay:1});
   });

</script>


<body class="biaoz">

<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
    <div class="button_op">
	    <input type="button"  value="上传视频"  class=" button_4" onclick="hg_add_single_video({$_INPUT['mid']});" />
	    <input type="button"  id="vod_upload"  value="批量新增"  onclick="vod_upload();" class=" button_4" />
	    <input type="button"  value="切换到列表"  class=" button_4" />
    </div>
</div>

<div class="content clear">
<form method="post" action="" name="listform">
 <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td valign="top" style="background:url({$image_resource}ybz_title.png) top repeat-x;border-left:1px solid #d8d8d8;min-width:931px;">
          <div class="right">
                <div class="search_a">
                    <div class="right_1">
                    	<div class="data_time input">
                            	<span class="input_left"></span>
                                <span class="input_right"></span>
                                <span class="input_middle"><a><em></em>内容1</a></span>
                        </div>
                        
                        <div class="transcoding down_list" id="transcoding_id">
                            	<span class="input_left"></span>
                                <span class="input_right"></span>
                                <span class="input_middle"><a><em></em>转码中</a></span>
                                <ul id="transcoding_show" style="display:none">
                                    	<li><a>已审核</a></li>
                                        <li><a>待审核</a></li>
                                        <li><a>被打回</a></li>
                                </ul>
                        </div>
                        
                        <div class="colonm down_list" id="colonm_id">
                            	<span class="input_left"></span>
                                <span class="input_right"></span>
                                <span class="input_middle"><a><em></em>网站栏目</a></span>
                                <ul id="colonm_show" style="display:none">
                                    	<li><a>编辑上传</a></li>
                                        <li><a>网友上传</a></li>
                                        <li><a>直播归档</a></li>
                                        <li><a>娱乐新闻</a></li>
                                        <li><a>直播归档</a></li>
                                </ul>
                        </div>
                    </div>
                    <div class="right_2">
                    	<div class="button">
                            	<span class="button_left"></span>
                                <span class="button_right"></span>
                                <span class="button_middle"><a>搜索</a></span>
                        </div>
                    	<div class="search input clear" id="search">
                            	<span class="input_left"></span>
                                <span class="input_right"></span>
                                <span class="input_middle"><em></em><input name="" type="text"  id="search_id"/></span>
                        </div>
                        
                    </div>
                </div>
                <ul class="list" id="list">
                    <li class="first clear">
                    	<span class="left"><a class="lb"><em></em></a><a class="slt">缩略图</a><a class="bf">播放</a></span>
                        <span class="right"><a class="fb">发布</a><a class="ml">码流</a><a class="fl">分类</a><a class="zt">状态</a><a class="tjr">添加人/时间</a></span><a class="title">标题</a>
                    </li>
			   
			   <div vodlist></div>  
               {if $list['info']}
       			{foreach $list['info'] as $k => $v} 
       			 
                 <li class="clear"  id="r_{$v['id']}"  onclick="hg_row_interactive(this, 'click', 'cur');" onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
                    	<span class="left"><a class="lb"><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}" /></a><a class="slt"><img src="{$v['img']}" width="40" height="30"  onclick="hg_get_img({$v['vodid']},{$v['id']});" /></a><a class="bf"><em class="current"  onclick="hg_play_video({$v['vodid']});"></em></a></span>
                        <span class="right"><a class="fb"><em class="b2"></em></a><a class="ml"><em>{$v['bitrate']}</em></a><a class="fl"><em class="color_green">{$v['vod_sort_id']}</em></a><a class="zt"><em><sup id="text_{$v['id']}">{$v['status']}</sup><sub id="tool_{$v['id']}" style="display:none;"><span id="status_{$v['id']}" style="width:0px;"></span></sub></em></a><a class="tjr"><em>{$v['addperson']}</em><span>{$v['create_time']}</span></a></span><span class="title">{if $v['collects']}<em></em>{/if}<a href="javascript:void(0);" onclick="check_menu({$v['id']},10);" id="t_{$v['id']}">{$v['title']}<strong>{$v['duration']}</strong></a></span>
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
               <div class="left"><a class="lb"><input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" />
                   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '审核', 1, 'id', '&audit=1', 'ajax');"    name="bataudit" >审核</a>
			       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '打回', 1, 'id', '&audit=0', 'ajax');"   name="batgoback" >打回</a>
			       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'move',  '移动', 1, 'id', '', 'ajax');"    name="batmove" >移动</a>
			       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>
			       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'add_to_collect', '添加到集合', 1, 'id', '', 'ajax');"   name="batadd_to_collect">添加到集合</a>
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

<script type="text/javascript">

var id="";
var vodid = "";
var status = "";
var transize = "";
var totalsize = "";
var img = "";
var title = "";
var bitrate = "";
var create_time = "";

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


function check_menu(id,total){
	
	$("#content_"+id).slideToggle();
	for(i=1;i<=total;i++)
	{
		if(i!==id)
		{
			$("#content_"+i).slideUp();	
		}	
	}
}

$(document).ready(function(){

	$("#search_id").focus(function(){
		$("#search").addClass("search_width")
		
		});
	$("#search_id").blur(function(){
		$("#search").removeClass("search_width")
		
		});	

		
	
		 $("#transcoding_id").mousemove(function(){
			 
			$("#transcoding_show").show();
			
		});
		$("#transcoding_id").mouseleave(function(){
			 
			$("#transcoding_show").hide();
			
		});
		$("#colonm_id").mousemove(function(){
			 
			$("#colonm_show").show();
			
		});
		$("#colonm_id").mouseleave(function(){
			 
			$("#colonm_show").hide();
			
		});
		
}
);

</script>
































