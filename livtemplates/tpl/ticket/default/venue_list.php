{template:head}
{css:common/common_list}
{js:common/common_list}
{css:vod_style}
{js:public_bicycle/station}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{code}
	if(!isset($_INPUT['date_search']))
	{
		$_INPUT['date_search'] = 1;
	}
{/code}
<script type="text/javascript">
$(function(){
	tablesort('vodlist','venue','order_id');
	$("#vodlist").sortable('disable');
});
</script>

<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a class="blue mr10" href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" target="nodeFrame">
		<span class="left"></span>
		<span class="middle"><em class="add">新增场馆</em></span>
		<span class="right"></span>
	</a>
</div>

<div class="content clear">
 <div class="f">
          <div class="right v_list_show">
                <div class="search_a" id="info_list_search">
                  <span class="serach-btn"></span>
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
                    <div class="select-search">
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
                    <div class="text-search">
                    	<div class="button_search">
							<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
                        </div>
						{template:form/search_input,k,$_INPUT['k']}                        
                    </div>
                    </form>
                </div>
				
                <form method="post" action="" name="listform">
                <!-- 标题 -->
                   <ul class="common-list public-list-head" id="list_head">
                        <li class="common-list-head clear">
                        	<div class="common-list-left">
                                <div class="common-paixu common-list-item"><a class="common-list-paixu" {if !$list['colname']}onclick="hg_switch_order('vodlist');"{/if}  title="排序模式切换/ALT+R"></a></div>
                            </div>
                            <div class="common-list-right">
                                <div class="common-list-item open-close wd60">编辑</div>
                                <div class="common-list-item open-close wd60">删除</div>
                            	<div class="common-list-item open-close wd300">场馆地址</div>
                                
                                <div class="common-list-item open-close wd120">添加人/时间</div>
                            </div>
                            <div class="common-list-biaoti ">
						        <div class="common-list-item open-close">场馆名称</div>
					        </div>
                        </li>
                    </ul>
                <ul class="common-list public-list" id="vodlist">
				    {if $list}
	       			    {foreach $list as $k => $v}
	                      {template:unit/venuelist}
	                    {/foreach}
					{else}
					<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
					<script>hg_error_html(vodlist,1);</script>
	  				{/if}
				</ul>
	           <ul class="common-list">
				     <li class="common-list-bottom clear">
					   <div class="common-list-left">
	                   <input type="checkbox"  name="checkall"  value="infolist" title="全选" rowtag="LI" />
				       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>
				   </div>
	               {$pagelink}
	            </li>
	          </ul>	
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