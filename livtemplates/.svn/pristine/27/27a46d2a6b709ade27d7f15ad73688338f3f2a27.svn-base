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

/*属性类型*/
$attr_type_source = array(
	'class'  => 'attr_type down_list',
	'show'   => 'attr_type_show',
	'width'  => 104,
	'state'  => 0,
	'is_sub' => 0,
);

if(!$_INPUT['attr_type_id'])
{
	$_INPUT['attr_type_id'] = 0;
}

$attr_type_arr = array();
foreach($_configs['attribute_type'] AS $_k => $_v)
{
	 if(intval($_k) == 0)
	 {
	 	$attr_type_arr[$_k] = $_v;
	 }
	 else
	 {
	 	$attr_type_arr[$_k] = $_v['name'];
	 }
}
/*属性类型*/

/*UI*/
$attr_ui = array(
	'class' => 'attr_ui down_list',
	'show'  => 'attr_ui_show',
	'width' => 104,
	'state' => 0,
	'is_sub'=> 0,
);

if(!$_INPUT['ui_id'])
{
	$_INPUT['ui_id'] = 0;
}

$ui_arr[0] = '选择UI';
if($ui_data)
{
	foreach($ui_data AS $_k => $_v)
	{
		 $ui_arr[$_v['id']] = $_v['name'];                                  		
	}
}
/*UI*/

/*角色*/
$attr_role = array(
	'class' => 'attr_role down_list',
	'show'  => 'attr_role_show',
	'width' => 104,
	'state' => 0,
	'is_sub'=> 0,
);

if(!$_INPUT['role_type_id'])
{
	$_INPUT['role_type_id'] = 0;
}
/*角色*/
  
{/code}
{css:vod_style}
{template:list/common_list}
<div class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
    <a class="blue mr10"  href="?mid={$_INPUT['mid']}&a=form{$_ext_link}&ui_id={$_INPUT['ui_id']}&ui_name={$_INPUT['ui_name']}">
    	<span class="left"></span>
    	<span class="middle"><em class="add">为({$_INPUT['ui_name']})新增属性</em></span>
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
							<!-- 
							{template:form/search_source,ui_id,$_INPUT['ui_id'],$ui_arr,$attr_ui}
							 -->
							{template:form/search_source,attr_type_id,$_INPUT['attr_type_id'],$attr_type_arr,$attr_type_source}
							{template:form/search_source,role_type_id,$_INPUT['role_type_id'],$_configs['role_type'],$attr_role}
							<input type="hidden" name="a" value="show" />
							<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
							<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
							<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
							<input type="hidden" name="ui_id" value="{$_INPUT['ui_id']}" />
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
                            </div>
                            <div class="common-list-right">
                                <div class="common-list-item wd50">查看</div>
                                <div class="common-list-item wd50">删除</div>
                                <div class="common-list-item wd50">设置值</div>
                                <div class="common-list-item wd150">标识</div>
                                <div class="common-list-item wd150">所属基础属性</div>
                                <div class="common-list-item wd80">所属分组</div>
                                <div class="common-list-item wd80">属性类型</div>
                                <div class="common-list-item wd80">所属角色</div>
                                <div class="common-list-item wd80">是否有默认值</div>
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
		                      {template:unit/attribute_relate_list_item}
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