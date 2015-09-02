<style type="text/css">
   .all_content{width:615px;border:1px solid gray;}
   .level0{height:40px;border-bottom:1px dotted #7D7D7D;background:#EFEFEF;}
   .level1{height:46px;border-bottom:1px dotted #7D7D7D;}
   .level2{height:106px;border-bottom:1px dotted #7D7D7D;}
   .level3{height:46px;border-bottom:1px dotted #7D7D7D;}
   .level4{height:38px;border-bottom:1px dotted #7D7D7D;}
   .level5{height:58px;}
   .add_vodtitle{font-size:20px;font-weight:bold;margin-left:16px;height:30px;width:85px;padding-top:7px;}
   .sort_style{width:106px;height:24px;border:1px solid #7D7D7D;}
   .level1_left{float:left;width:110px;margin-left:16px;margin-top:5px;}
   .level1_middle{float:left;margin-left:100px;margin-left:6px;margin-top:7px;}
   .level1_right{float:right;margin-top:7px;margin-right:16px;cursor:pointer;}
   .level1_right a:hover{font-size:12px;color:green;text-decoration:none;}
   .level1_right a{color:#7D7D7D;text-decoration:underline;}
   .level2_left{float:left;width:425px;height:100%;}
   .level2_right{float:right;height:100%;width:175px;}
   .level2_left_title{width:400px;height:19px;border:1px solid gray;margin-left:16px;margin-top:9px;}
   .level2_left_comment{width:400px;height:40px;border:1px solid gray;margin-left:16px;margin-top:13px;}
   .level2_right_source{width:130px;height:24px;border:1px solid gray;float:left;}
   .lab{width:30px;font-size:12px;float:left;color:#7D7D7D;margin-top:3px;}
   .level2_right_top{height:28px;margin-top:9px;}
   .level2_right_input{width:124px;height:19px;border:1px solid gray;float:left;}
   .level3_left{width:315px;float:left;margin-top:9px;}
   .level3_left_input{width:251px;height:19px;border:1px solid gray;}
   .level3_right{width:290px;float:left;margin-top:9px;}
   .level3_right_input{width:236px;height:19px;border:1px solid gray;}
   .level4_a{color:#7D7D7D;font-size:14px;text-decoration:underline;margin-left:16px;}
   .vod_button{width:127px;height:30px;margin-left:16px;margin-top:14px;cursor:pointer;}
   .futi{margin-left:16px;}
   .level4_box{width:120px;margin-top:8px;cursor:pointer;}
   .localurl{float:left;height:19px;width:auto;margin-top:10px;font-size:14px;color:black;margin-left:10px;}
</style>


{code}
  $image_resource = RESOURCE_URL;
  $video_type = $_configs['video_type'];
{/code}
{js:jquery.filestyle}
<script type="text/javascript">
	$(function() {
	
	    $("#upload_video").filestyle({
	
	        image:  RESOURCE_URL+"select_upload.png",
	
	        imageheight : 24,
	
	        imagewidth : 89,
	
	        display : "none"
	
	    });
	
	});

</script>

<form action="./run.php?mid={$_INPUT['mid']}"  method="post" enctype="multipart/form-data" name="single_video_form" id="single_video_form"  onsubmit="return single_video_submit();">	
<div class="all_content">
  <div class="level0">
     <div class="add_vodtitle">新增视频</div>
  </div>
  <div class="level1">
      <div class="level1_left">
	    <select name="vod_sort_id" class="sort_style">
          {foreach $formdata['sort'] as $v}
		       <option value="{$v['id']}">{$v['sort_name']}</option>
		  {/foreach}
	    </select>
	  </div>
	   <div id="video_localurl" class="localurl"></div>
	   <div class="level1_middle">
	     <input type="file"  id="upload_video"  name="Filedata"  onchange="hg_show_localurl(this);"/>
	  </div>
	  <div class="level1_right">
	     <a herf="#"  onclick="hg_switch_upload()" id="switch_upload" >切换至批量模式</a>
	  </div>
  </div>
  <div class="level2">
     <div class="level2_left">
        <input type="text" name="vod_title" class="level2_left_title">
        <textarea class="level2_left_comment"   name="comment"></textarea>
     </div>
     <div class="level2_right">
       <div class="level2_right_top">
         <div class="lab">来源</div>
         <select name="source" class="level2_right_source">
          {foreach $formdata['source'] as $k => $v}
		       <option value="{$k}">{$v}</option>
		  {/foreach}
	    </select>
	   </div>
	   <div class="level2_right_top">
         <div class="lab">作者</div>
         <input type="text"  class="level2_right_input"  name="author" id="author"/>
	   </div>
     </div>
 
  </div>
  <div class="level3">
    <div class="level3_left">
     <div class="lab futi">副题</div>
     <input type="text" class="level3_left_input" name="subtitle" id="subtitle" />
    </div>
    
     <div class="level3_right">
     <div class="lab" style="width:38px;">关键字</div>
     <input type="text" class="level3_right_input"  name="keywords" id="keywords" />
    </div>
     
  </div>
  <div class="level4">
    <div class="level4_box">
     <a herf="#" class="level4_a">发布至网站栏目</a>
    </div>
  </div>
  <div class="level5">
    <input type="button" class="vod_button" value="确定并继续新增"  onclick="hg_submit_more();"  />
    <input type="submit" class="vod_button" style="width:90px;" value="确定" id="single_video"  name="single_video" />
  </div>
</div>
  <input type="hidden" value="{$video_type}" name="video_type" id="video_type" />
  <input type="hidden" value="single_upload" name="a" />
  <input type="hidden" value="{$$primary_key}" name="{$primary_key}" />
  <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
</form>



