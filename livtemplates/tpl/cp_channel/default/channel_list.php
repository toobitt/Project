<?php 
/* 
$Id:channel_list.php 17960 2013-03-21 14:28:00 jeffrey $ 

*/
?>
{template:head}
{code}
$list = $channel_list;
$image_resource = RESOURCE_URL;

if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}

if(!isset($_INPUT['channel_state']))
{
    $_INPUT['channel_state'] = -1;
}

{/code}
{css:vod_style}
{template:list/common_list}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:mblog}
<script type="text/javascript">
   $(document).ready(function(){
		tablesort('pictures_list','pictures','order_id');
		$("#pictures_list").sortable('disable');
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
		<a class="blue mr10"  href="?mid={$_INPUT['mid']}&a=form{$_ext_link}">
		<span class="left"></span>
		<span class="middle"><em class="add">新增频道</em></span>
		<span class="right"></span>
		</a>
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
						{template:form/search_source,channel_state,$_INPUT['channel_state'],$_configs['channel_state'],$attr_state}
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
                <form method="post" action="" name="listform">
                <ul class="list_img" id="pictures_list">
				    {if is_array($list) &&!empty($list) && count($list)>0}
	       			    {foreach $list as $k => $v}
	                      {template:unit/channellist}
	                    {/foreach}
					{else}
					<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">未找到频道！</p>
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
{template:foot}