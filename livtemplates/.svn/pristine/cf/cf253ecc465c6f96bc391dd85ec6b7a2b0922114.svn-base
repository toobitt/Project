{template:head}
{code}
if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}
$attr_date = array(
	'class' => 'colonm down_list data_time',
	'show' => 'colonm_show',
	'width' => 104,/*列表宽度*/
	'state' => 1,/*0--正常数据选择列表，1--日期选择*/
	'is_sub'=> 0,
);

/*类型控件*/
$ui_type_source = array(
	'class' => 'transcoding down_list',
	'show' => 'ui_type_show',
	'width' => 104,/*列表宽度*/
	'state' => 0,/*0--正常数据选择列表，1--日期选择*/
);

if(!isset($_INPUT['type']))
{
    $_INPUT['type'] = 0;
}

{/code}
{css:vod_style}
{template:list/common_list}
<div class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
    <a class="blue mr10"  href="?mid={$_INPUT['mid']}&a=form{$_ext_link}">
    	<span class="left"></span>
    	<span class="middle"><em class="add">新增UI</em></span>
    	<span class="right"></span>
    </a>
</div>
<div class="content clear">
 <div class="f">
          <div class="right v_list_show">
                <div class="search_a" id="info_list_search">
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
	                    <div class="right_1">
							{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
							{template:form/search_source,type,$_INPUT['type'],$_configs['ui_type'],$ui_type_source}
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
                <form method="post" action="" name="vod_sort_listform">
                    <!-- 标题 -->
                   <ul class="common-list">
                        <li class="common-list-head public-list-head clear">
                            <div class="common-list-left">
                                <div class="common-list-item paixu">
			 	                   <a title="排序模式切换/ALT+R" onclick="hg_switch_order('newslist');"  class="common-list-paixu"></a>
			                    </div>
			                    <div class="common-list-item wd60">UI图</div>
                            </div>
                            <div class="common-list-right">
                                <div class="common-list-item wd60">查看</div>
                                <div class="common-list-item wd60">删除</div>
                                <div class="common-list-item wd60">查看属性</div>
                                <div class="common-list-item wd60">复制属性</div>
                                <div class="common-list-item wd180">标识</div>
                                <div class="common-list-item wd80">类型</div>
                                <div class="common-list-item wd180">简介</div>
                                <div class="common-list-item open-close wd120">创建人/创建时间</div>
                            </div>
                            <div class="common-list-biaoti">
						        <div class="common-list-item">名称</div>
					        </div>
                        </li>
                    </ul>
	                <ul class="common-list hg_sortable_list public-list" id="auth_form_list">
					    {if $list}
		       			    {foreach  $list  as $k => $v}
		                      {template:unit/user_interface_item}
		                    {/foreach}
		                {else}
							<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
							<script>hg_error_html(vodlist,1);</script>
		  				{/if}
	                </ul>
	                
		            <ul class="common-list public-list">
				     <li class="common-list-bottom clear">
					   <div class="common-list-left">
		                   <input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
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
</div>
{template:foot}