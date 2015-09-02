<style type="text/css">
   .tijiao{position:absolute;left:690px;top:75px;}
   #content_list{width:145px;height:auto;border:1px solid gray;display:block;position:absolute;left:40px;top:23px;display:none;}
   #div_win{width:100%;height:1000px;position:relative;}
   #vod_videos_info{width:300px;height:1000px;float:left;text-align:center;overflow:auto;}
   .tp_checkbox{position:absolute;left:10px;top:8px;}
   .tp_img{position:absolute;left:35px;top:5px;}
   .tp_title{position:absolute;left:75px;top:12px;}
   .input_words{margin-left:40px;float:left;width:142px;height:16px;}
   .img_style{width:30px;height:30px;}
   .video_content{position:relative;height:40px;border-bottom:1px solid #CFCFCF;cursor:pointer;}
   .vbg_color{background:#DDEEFE;}
   .videos_list_title{border-bottom:1px dotted #CFCFCF;height:30px;margin-top:10px;}
</style>

<div id="div_win">
 <form action="./run.php?mid={$_INPUT['mid']}"  method="post"  enctype="multipart/form-data" name="collect_form" id="collect_form"  onsubmit="return hg_videos_to_collect('collect_form');">
    <div id="vod_videos_info">
     <div class="videos_list_title">您所选的视频如下：</div>
       {foreach $formdata as $v}
       <div class="video_content vbg_color" id="videos_{$v['id']}"  onclick="hg_switch_checked(this);">
           <input class="tp_checkbox" type="checkbox" name="videos_ids[]"  value="{$v['id']}" title="{$v['id']}" checked="checked"  onclick="hg_switch_checked('#videos_{$v['id']}');"  />
	       <div class="tp_img"><img src="{$v['img']}"  class="img_style" /></div>
	       <div class="tp_title">《{$v['title']}》{$v['duration']}</div>
	   </div>
       {/foreach}
    </div>
    
    <div  style="width:260px;height:180px;float:right;">
      <div   style="padding-left:40px;margin-top:10px;">填写添加到的集合名称：</div>
      <div style="margin-top:15px;width:100%;height:140px;position:relative;">
		  <input type="text" name="get_contents"  id="get_contents"    onkeyup="hg_getcollect_video();"   onblur="hg_hide_contents();"    onfocus="hg_getcollect_video();" autocomplete="off" class="input_words" />
		  <input type="button" class="button_2"   id="create_collect"  value="创建"  style="margin-left:10px;float:left;display:none;"  onclick="hg_create_collect({$_INPUT['mid']},{$$primary_key});" />
		  <input type="submit" class="button_2"   id="add_collect"  value="添加"  style="margin-left:10px;float:left;" />
		  <div  id="content_list" style="max-height:360px;overflow:hidden;overflow-y:scroll;"></div>
		  <input type="hidden" value=""  id="collect_id"  name="collect_id"  class="add_collect"  />
		  <input type="hidden" value="video2collect" name="a" />
		  <input type="hidden" value="" name="id"  id="videos_id" />
		  <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		  <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	  </div>
    </div>
</form>
</div>