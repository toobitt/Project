{template:head}
{code}
$list = $albums_list;
$image_resource = RESOURCE_URL;

if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}

if(!isset($_INPUT['albums_state']))
{
    $_INPUT['albums_state'] = -1;
}
{/code}
{css:vod_style}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
<script type="text/javascript">
   $(document).ready(function(){
		tablesort('albums_list','albums','order_id');
		$("#albums_list").sortable('disable');
   });

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
			$('#top_box_'+id).css('border','1px solid #90bff3');
			$('#top_box2_'+id).css('border','1px solid #90bff3');
	   }
	   else
	   {
		 
		   $('#tips_'+id).hide();
		   $('#delete_'+id).hide();
		   $('#top_box_'+id).css('border','1px solid #ccc');
		   $('#top_box2_'+id).css('border','1px solid #ccc');
	   }
   } 
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
		<span type="button" class="button_6" ><strong>新增相册</strong></span>
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
						{template:form/search_source,albums_state,$_INPUT['albums_state'],$_configs['albums_state'],$attr_state}
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

                <div class="list_first clear"  id="list_head">
                    	<span class="left"><a class="lb" style="cursor:pointer;" onclick="hg_switch_order('albums_list');"  title="排序模式切换/ALT+R"><em></em></a><a class="slt">缩略图</a></span>
                        <span class="right"  style="width:370px;" ><a class="fb">删除</a><a class="fl">图片数</a><a class="fl">评论数</a><a class="tjr">创建人/创建时间</a></span><a class="title">标题</a>
                </div>
                <form method="post" action="" name="listform">
                <ul class="list_img" id="albums_list">
				    {if $list}
	       			    {foreach $list as $k => $v}
	                      {template:unit/albumslist}
	                    {/foreach}
					{else}
					<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
					<script>hg_error_html(albums_list,1);</script>
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
</body>
{template:foot}