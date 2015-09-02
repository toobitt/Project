{template:head}
{code}
$list = $pics_list;

if(!isset($_INPUT['image_status']))
{
	$_INPUT['image_status'] = -1;
}

if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}

if(!isset($_INPUT['tuji_name']))
{
    $_INPUT['tuji_name'] = -1;
}

{/code}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:tuji_pics}
{css:vod_style}
<script type="text/javascript">
$(function(){
	tablesort('tujipiclist','pics','order_id');
	$("#tujipiclist").sortable('disable');
});
</script>
<style type="text/css">
  .vbg_color{background:#DDEEFE;}
</style>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a href="javascript:void(0);"   class="button_6" onclick="hg_showAddTuJipics(0,1);">新增图片</a>
</div>
<div class="content clear">
 <div class="f">
 			<!-- 新增图片模板开始 -->
		 	<div id="add_tuji_pics"  class="single_upload">
				<h2><span class="b" onclick="hg_closeTuJiPicsTpl();"></span><span id="tuji_pics_title">新增图片</span></h2>
				<div style="display:none;" id="images_upload_nav">
					<h3 id="single_select" class="select_item" onclick="hg_add_single_image();">上传单个文件<span class="a"></span></h3>
					<h3 id="more_select"   class="select_item" onclick="hg_add_more_image();">上传多个文件<span class="b"></span></h3>
				</div>
				<div id="tuji_pics_form"  class="upload_form" style="height:808px;margin-top:10px;overflow:auto;"></div>
			</div>
			<!-- 新增图片模板结束 -->
          <div class="right v_list_show">
                <div class="search_a" id="info_list_search">
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
                    <div class="right_1">
						{code}
							$attr_tuji_name = array(
								'class' => 'transcoding down_list',
								'show' => 'tuji_name_show',
								'width' => 104,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
							);
							
							$tuji_name_default = -1;
							$tuji_array[$tuji_name_default] = '全部图集';
							foreach($tuji_name[0] as $k => $v)
							{
								$tuji_array[$v['id']] = $v['title'];
							}
						
							$attr_source = array(
								'class' => 'transcoding down_list',
								'show' => 'image_status_show',
								'width' => 70,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
							);
							
							$attr_date = array(
								'class' => 'colonm down_list data_time',
								'show' => 'image_date_show',
								'width' => 104,/*列表宽度*/
								'state' => 1,/*0--正常数据选择列表，1--日期选择*/
							);

							$_configs['image_upload_status'][-1] = '全部状态';
						{/code}
						{template:form/search_source,image_status,$_INPUT['image_status'],$_configs['image_upload_status'],$attr_source}
						{template:form/search_source,tuji_name,$_INPUT['tuji_name'],$tuji_array,$attr_tuji_name}
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
                    	<span class="left"><a class="lb" style="cursor:pointer;"  onclick="hg_switch_order('tujipiclist');"  title="排序模式切换/ALT+R"><em></em></a><a class="slt">缩略图</a></span>
                        <span class="right"><a class="fb">编辑</a><a class="fb">删除</a><a class="fl">所属图集</a><a class="zt">状态</a><a class="tjr">添加时间</a></span><a class="title">图片名称</a>
                </div>
                <form method="post" action="" name="listform">
	                <ul class="list" id="tujipiclist">
					    {if $list}
		       			    {foreach $list as $k => $v} 
		                      {template:unit/picslist}
		                    {/foreach}
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
						<script>hg_error_html(tujipiclist,1);</script>
		  				{/if}
						<li style="height:0px;padding:0;" class="clear"></li>
	                </ul>
		            <div class="bottom clear">
		               <div class="left" style="width:400px;">
		                   <input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
		                   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');"    name="batdelete">删除</a>
		                   <a style="cursor:pointer;" href="javascript:void(0);"  onclick="hg_editMorePics(this);"    name="batedit">批量编辑</a>
					   </div>
		               {$pagelink}
		            </div>	
    			</form>
           </div>
        </div>
</div>
   <div id="infotip"  class="ordertip"></div>
   <div id="getimgtip"  class="ordertip"></div>
</body>
{template:foot}