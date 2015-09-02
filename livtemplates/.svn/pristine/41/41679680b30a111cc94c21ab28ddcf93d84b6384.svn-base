{template:head}
{code}
if(!$_INPUT['date_search'])
{
	$_INPUT['date_search'] = 1;
}
{/code}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{css:vod_style}
{css:edit_video_list}
{css:mark_style}
{css:common/common_list}
{js:common/common_list}
{js:tree/animate}
{code}
	//if($access_list['_type_'])
	//{
		//$_type_ = $access_list['_type_'];
		//unset($access_list['_type_']);		
	//}
{/code}
<pre>
	{code}
		//print_r($performance_list);
	{/code}
</pre>
<style>
	.one{width:74px;}
	.two{width:70px;}
</style>
<script type="text/javascript">
	$(document).ready(function(){
		$(".access_nums_type").click(function(){
			$("input[name = access_nums]").val($(this).attr('type'));			
			$("#searchform").submit();
		});
	});
</script>
<div class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div class="content clear">
	<div class="f">			
          <div class="right v_list_show">
		  	<div id="infotip"  class="ordertip"></div>
	        <div id="getimgtip"  class="ordertip"></div>
			<div class="search_a" id="info_list_search">
				<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
					<div class="right_1">		
						<input type="hidden" name="a" value="show" />
						<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
						<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
						<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
						<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
						<input type="hidden" name="_appid" value="{$_INPUT['_appid']}" />
						<input type="hidden" name="_modid" value="{$_INPUT['_modid']}" />
						<input type="hidden" name="access_nums" value="{$_INPUT['access_nums']}"  />
					  </div>
					  <div class="right_2">
						<div class="button_search">
							<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
						</div>
						{template:form/search_input,key,$_INPUT['key']}                        
					  </div>
	             </form>
			</div>
            <form method="post" action="" name="listform">
                   <ul class="common-list" >
                        <li class="common-list-head clear" style="text-align:center;">
                            <div class="common-list-right">
                           		<div class="access-cz common-list-item open-close" style="width:30px;"></div>
                            		<div class="access-cz common-list-item open-close one">标题</div>
                                <div class="access-cz common-list-item open-close one" style="width:260px;">文稿URL</div>
                                <div class="access-cz common-list-item open-close one">页面总点击</div>
                                <div class="access-cz common-list-item open-close one">外网/内网</div>
                                <div class="access-cz common-list-item open-close one">视频播放数</div>
                                <div class="access-cz common-list-item open-close one">分页</div>
                                <div class="access-cz common-list-item open-close one">频道来源</div>
                                <div class="access-cz common-list-item open-close one">统计图</div>
                                <div class="access-cz common-list-item open-close one">部门</div>
                                <div class="access-cz common-list-item open-close one">编辑</div>
                                <div class="access-cz common-list-item open-close one">时间</div>
                            </div>
                            <!--  
                            <div class="common-list-biaoti ">
						        <div class="common-list-item open-close access-biaoti">标题</div>
					        </div>
					        -->
                        </li>
                    </ul>
               		<ul class="common-list" id="vodlist">
					  	{if is_array($performance_list) && count($performance_list)>0}
							{foreach $performance_list as $k => $v}		
		                      {template:unit/performance_list_list}
		                    {/foreach}
						{else}
								<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;border-top:1px solid #c8d4e0;margin:0 10px">没有您要找的内容！</p>
								<script>hg_error_html(vodlist,1);</script>
		  				{/if}
                	</ul>
		            <div class="bottom clear">
	                   {$pagelink}
					</div>
              </form>

			  <div class="edit_show" style="">
			  <span class="edit_m" id="arrow_show"></span>
			  <div id="edit_show"></div>
			  </div>
		</div>
</div>
</div>
</div>
<script type="text/javascript">
	function hg_call_access_del(id) {
		 var ids=id.split(",");
		 for(var i=0;i<ids.length;i++)
		{
			$("#r_"+ids[i]).remove();			
		}
	}
</script>
{template:foot}