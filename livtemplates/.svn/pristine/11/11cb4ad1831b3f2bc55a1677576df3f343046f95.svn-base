<!-- 图片选择控件  -->
{js:jscroll}
{js:select_image}
{css:select_image}
{js:jquery-ui-1.8.16.custom.min}
{code}
    if(!class_exists('hg_get_img'))
	{
		include_once(ROOT_PATH . 'get_img.php');
	}
    $img_info = new hg_get_img();
    $ret = $img_info->get_img_info();
    print_r($ret);
  

{/code}
<script type="text/javascript">
  $(function(){
	  
	   $("#pic_content_ul").jscroll({ W:"4px"
			,Bg:"none"
			,Bar:{Bd:{Out:"#000",Hover:"#000"}
				 ,Bg:{Out:"#000",Hover:"#000",Focus:"#000"}}
			,Btn:{btn:false}
		});
		
	   $("#selected_images").jscroll({ W:"4px"
			,Bg:"none"
			,Bar:{Bd:{Out:"#000",Hover:"#000"}
				 ,Bg:{Out:"#000",Hover:"#000",Focus:"#000"}}
			,Btn:{btn:false}
		});
  });
</script>
{if $hg_data}
<div class="image_all_x" style="margin-top:20px;">
	<div class="l_x">
	   <div class="search_img">
	   		
	   </div>
	   <div class="show_image" id="cont_image_s">
	   	   {template:form/pic_content}
	   </div>
	</div>
	<div class="r_x">
		<div class="s_img" id="selected_images">
			{code}
				$arr2 = array(1,2,3);
			{/code}
			{foreach $arr2 AS $v}
			 <span  class="li">
			     <div  class="item_img"><img src="http://localhost/livtemplates/tpl/lib/images/tu1.jpg" /></div>
				 <div  class="show_item">
					<span  class="overflow" >名称：适宜的小猫</span>
					<span  class="overflow" >描述：哈哈</span>
				 </div>
  			</span>
  			{/foreach}
		</div>
	</div>
</div>
{else}
<p style="color:#da2d2d;text-align:center;font-size:14px;line-height:30px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
{/if}

