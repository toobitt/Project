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
{/code}
{css:vod_style}
{template:list/common_list}
<script type="text/javascript">
function create_off_line_over(data)
{
	if(data)
	{
		alert('离线成功');
	}
	else
	{
		alert('离线失败');
	}
}
</script>
<div class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a class="blue mr10"  href="?mid={$_INPUT['mid']}&a=form{$_ext_link}">
		<span class="left"></span>
		<span class="middle"><em class="add">新增议程</em></span>
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
	                    		/*****************议程日期******************/
								$attr_agenda_date = array(
                            		'class' => 'agenda_date down_list',
                            		'show'  => 'agenda_date_show',
                            		'width' => 160,
                            		'state' => 0,
                            		'is_sub'=> 0,
                                );
                                
                                if(!$_INPUT['date_id'])
                                {
                                	$_INPUT['date_id'] = 0;
                                }
                                /*****************议程日期******************/
                                
                                /*****************议程专题******************/
                                $attr_special = array(
                            		'class' => 'special down_list',
                            		'show'  => 'special_show',
                            		'width' => 104,
                            		'state' => 0,
                            		'is_sub'=> 0,
                                );
                                
                                if(!$_INPUT['special_id'])
                                {
                                	$_INPUT['special_id'] = 0;
                                }
                                $_special_sort[0] = '全部专题';
                                if($special_sort)
                                {
                                	foreach($special_sort AS $kk => $vv)
                                	{
                                		$_special_sort[$vv['id']] = $vv['name'];
                                	}
                                }
                                /*****************议程专题******************/
							{/code}
							{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
							{template:form/search_source,date_id,$_INPUT['date_id'],$_configs['agenda_date'],$attr_agenda_date}
							{template:form/search_source,special_id,$_INPUT['special_id'],$_special_sort,$attr_special}
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
                            </div>
                            <div class="common-list-right">
                                <div class="common-list-item wd60">查看</div>
                                <div class="common-list-item wd60">删除</div>
                                <div class="common-list-item wd180">所属日期</div>
                                <div class="common-list-item wd180">所属专题</div>
                                <div class="common-list-item wd120">时间区间</div>
                                <div class="common-list-item wd60">演讲人</div>
                                <div class="common-list-item open-close wd120">添加人/时间</div>
                            </div>
                            <div class="common-list-biaoti">
						        <div class="common-list-item">议程标题</div>
					        </div>
                        </li>
                    </ul>
	                <ul class="common-list hg_sortable_list public-list" id="auth_form_list">
					    {if $list}
		       			    {foreach  $list  as $k => $v}
		                      {template:unit/agenda_list_item}
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
					       <a style="cursor:pointer;"  onclick="return hg_ajax_post(this, '生成离线数据', 1);" href="./run.php?mid={$_INPUT['mid']}&a=create_off_line" >生成离线数据</a>
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