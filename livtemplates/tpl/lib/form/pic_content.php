{code}
	$arr = array(1,2,3,3,3,3,1,3,3,3,3,3,3,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1);
{/code}

<div id="pic_content_ul" class="img_info">
{foreach $arr AS $v}
  <span  class="li" onclick="hg_img_select($(this));" >
     <div  class="item_img"><img src="http://10.0.1.40/livtemplates/tpl/lib/images/tu1.jpg" /></div>
	 <div  class="show_item">
		<span  class="overflow" >名称：适宜的小猫</span>
		<span  class="overflow" >描述：哈哈</span>
	 </div>
  </span>
{/foreach}
</div>

<div class="page_ye">
   
		<a href="javascript:void(0);" onclick="hg_getManyVideos({$formdata['first_page']});" class="p">|<</a>
		<a href="javascript:void(0);" onclick="hg_getManyVideos({$formdata['prev_page']});" class="p"><</a>
		
		<a href="javascript:void(0);" onclick="hg_getManyVideos({$formdata['next_page']});" class="p" >></a>
		<a href="javascript:void(0);" onclick="hg_getManyVideos({$formdata['last_page']});" class="p">>|</a>
	
	<span class="button_4" style="float:right;margin-right:9px;" id="add_all_videos" onclick="hg_selectAllVideos();">全部添加</span>
	<input type="button"  style="float:right;cursor:pointer;margin:0 5px;"  class="button_4"   value="大图模式"   id="switch_button"  onclick="hg_switchThecollect();"  />
	<span style="float:right;margin-right:10px;">1/2页 3条</span>
</div>
