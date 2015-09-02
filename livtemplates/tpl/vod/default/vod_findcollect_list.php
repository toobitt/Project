
<style type="text/css">
   .left{float:left;margin-left:10px;}
   .tijiao{position:absolute;left:690px;top:75px;}
   #content_list{width:145px;height:auto;border:1px solid gray;display:block;position:absolute;left:40px;top:23px;display:none;}
   #div_win{width:100%;height:200px;position:relative;}
   #add_collect{display:none;}
   #video_info{width:360px;height:200px;float:left;text-align:center;overflow:auto;}
   .tp_img{position:absolute;left:10px;top:5px;}
   .tp_title{position:absolute;left:50px;top:9px;}
   .input_words{margin-left:40px;float:left;width:142px;height:16px;}
   .img_style{width:30px;height:30px;}
   .video_content{position:relative;width:320px;height:40px;margin-left:20px;border-bottom:1px solid #CFCFCF;}
   .videos_list_title{border-bottom:1px dotted #CFCFCF;height:30px;}
</style>

<div id="div_win">
 <form action="./run.php?mid={$_INPUT['mid']}"  method="post"  enctype="multipart/form-data" name="collect_form" id="collect_form"  onsubmit="return hg_ajax_submit('collect_form', '');">
    <div id="video_info">
     <div class="videos_list_title">您所选的视频如下：</div>
       {foreach $formdata as $v}
       <div class="video_content">
	       <div class="tp_img"><img src="{$v['img']}"  class="img_style" /></div>
	       <div class="tp_title">《{$v['title']}》</div>
	   </div>
       {/foreach}
    </div>
    
    <div class="left"  style="width:344px;height:180px;">
      <div   style="padding-left:40px;">填写添加到的集合名称：</div>
      <div style="margin-top:15px;width:100%;height:140px;position:relative;">
		  <input type="text" name="get_contents"  id="get_contents"    onkeyup="hg_getcollect_video({$_INPUT['mid']},0);" onfocus="hg_getcollect_video({$_INPUT['mid']},1);" autocomplete="off" class="input_words" />
		  <input type="button" class="button_2"   id="create_collect"  value="创建"  style="margin-left:10px;float:left;"  onclick="hg_create_collect({$_INPUT['mid']});" />
		  <input type="submit" class="button_2"   id="add_collect"  value="添加"  style="margin-left:10px;float:left;" />
		  <div  id="content_list"></div>
		  <input type="hidden" value=""  id="collect_id"  name="collect_id"  />
		  <input type="hidden" value="video2collect" name="a" />
		  <input type="hidden" value="{$$primary_key}" name="{$primary_key}" />
		  <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	  </div>
    </div>
</form>
</div>