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
<div class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
<a class="blue mr10"  href="http://{$_configs['App_meeting']['host']}/{$_configs['App_meeting']['dir']}admin/excel_download.php?access_token={$_user['token']}">
		<span class="left"></span>
		<span class="middle"><em class="add">导出excel</em></span>
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
							{code}
									$attr_sign = array(
										'class' => 'transcoding down_list',
										'show' => 'sign_show',
										'width' => 104,/*列表宽度*/
										'state' => 0,/*0--正常数据选择列表，1--日期选择*/
									);
							
									$_signArr = array(
										'0' => '是否签到',
										'1' => '未签到', 
										'2' => '已签到',
									);
									
									if(!$_INPUT['is_sign'])
									{
										$_INPUT['is_sign'] = 0;
									}
							{/code}
							{template:form/search_source,is_sign,$_INPUT['is_sign'],$_signArr,$attr_sign}
							
							{code}
									$attr_guest_type = array(
										'class' => 'transcoding down_list',
										'show' => 'guest_type_show',
										'width' => 104,/*列表宽度*/
										'state' => 0,/*0--正常数据选择列表，1--日期选择*/
									);
									
									if(!$_INPUT['guest_type'])
									{
										$_INPUT['guest_type'] = 0;
									}
							{/code}
							{template:form/search_source,guest_type,$_INPUT['guest_type'],$_configs['guest_type'],$attr_guest_type}
							
							
							{code}
									$attr_rank = array(
										'class' => 'transcoding down_list',
										'show' => 'rank_show',
										'width' => 104,/*列表宽度*/
										'state' => 0,/*0--正常数据选择列表，1--日期选择*/
									);
									
									$_rankArr = array(
										'0' => '不排名',
										'1' => '排名', 
									);
									
									if(!$_INPUT['mrank'])
									{
										$_INPUT['mrank'] = 0;
									}
							{/code}

							{template:form/search_source,mrank,$_INPUT['mrank'],$_rankArr,$attr_rank}

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
			                    <div class="common-list-item wd60">头像</div>
                            </div>
                            <div class="common-list-right">
                                <div class="common-list-item wd60">查看</div>
                                <div class="common-list-item wd60">删除</div>
                                <div class="common-list-item wd80">单位</div>
                                <div class="common-list-item wd80">职务</div>
                                <div class="common-list-item wd100">手机号</div>
                                <div class="common-list-item wd100">邮箱</div>
                                <div class="common-list-item wd60">交换名片</div>
                                <div class="common-list-item open-close wd120">添加人/时间</div>
                            </div>
                            <div class="common-list-biaoti">
						        <div class="common-list-item">姓名</div>
					        </div>
                        </li>
                    </ul>
	                <ul class="common-list hg_sortable_list public-list" id="auth_form_list">
					    {if $list}
		       			    {foreach  $list  as $k => $v}
		                      {template:unit/member_list_item}
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