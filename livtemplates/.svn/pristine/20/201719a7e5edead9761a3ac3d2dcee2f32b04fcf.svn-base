{template:head}
{code}
if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}
{/code}
{css:vod_style}
{css:edit_video_list}
{css:mark_style}
{js:vod_opration}
{js:jquery-ui-1.8.16.custom.min}
{js:contribute_sort}
{template:list/common_list}
<script type="text/javascript">
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']} style="display:none"{/if}>
	<a href="./run.php?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6" style="font-weight:bold;">添加水印配置</a>
</div>
<div class="content clear">
 <div class="f">
          <div class="right v_list_show">
                <div class="search_a" id="info_list_search">
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
                    <div class="right_1">
						{code}
							$attr_date = array(
								'class' => 'colonm down_list data_time',
								'show' => 'colonm_show',
								'width' => 104,/*列表宽度*/
								'state' => 1,/*0--正常数据选择列表，1--日期选择*/
							);
						{/code}
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
                    	<span class="left"><a class="lb" style="cursor:pointer;"></a><a class="fb" style="width:120px;">水印配置名称</a></span>
                        <span class="right"  style="width:400px;"><a class="fb">编辑</a><a class="fb">删除</a><a class="tjr" style="width:150px;">添加人/时间</a><a class="fb" style="width:130px;">添加IP</a></span><a class="fb" style="margin-left:10px;margin-top:9px;">水印类别</a>
                </div>
                <form method="post" action="" name="listform">
	                <ul class="list" id="contri_sortlist">
					    {if $water_list}
		       			    {foreach $water_list as $k => $v} 
		                      {template:unit/waterlist}
		                    {/foreach}
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
						
		  				{/if}
						<li style="height:0px;padding:0;" class="clear"></li>
	                </ul>
		            <div class="bottom clear">
		               <div class="left" style="width:400px;">
		                   <input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
		                   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');"    name="delete">删除</a>
					   </div>
		               {$pagelink}
		            </div>	
    			</form>
           </div>
 </div>
</div>
</body>
<script type="text/javascript">
function hg_delete_call(id)
{
	 var ids=id.split(",");
	 for(var i=0;i<ids.length;i++)
	 {
		$("#r_"+ids[i]).remove();
	 }
}
</script>
{template:foot}