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
{js:vod_opration}
{css:edit_video_list}
{template:list/common_list}
<script type="text/javascript">
function change_status(id, status)
{
	var url;
	if (status == 0 || status == 2)
	{
		url = "run.php?mid=" + gMid + "&a=audit&id=" + id + "&status=1";
	}else{
		url = "run.php?mid=" + gMid + "&a=audit&id=" + id + "&status=2";
	}
	hg_ajax_post(url);
}
function hg_audit_back(json)
{
	var obj = eval("("+json+")");
	var con = '';
	if(obj.status == 1)
	{
		con = '已审核';
		for(var i = 0;i<obj.id.length;i++)
		{
			$('#audit_'+obj.id[i]).css('color', 'green');
			$('#audit_'+obj.id[i]).text(con);
			$('#audit_'+obj.id[i]).attr('onclick','change_status('+obj.id[i]+','+obj.status+')');
		}
	}
	if(obj.status == 2)
	{
		con = '被打回';
		for(var i = 0;i<obj.id.length;i++)
		{
			$('#audit_'+obj.id[i]).css('color', 'red');
			$('#audit_'+obj.id[i]).text(con);
			$('#audit_'+obj.id[i]).attr('onclick','change_status('+obj.id[i]+','+obj.status+')');
		} 
	}
}
</script>
{code}
	//hg_pre($list);
{/code}
<div class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a class="blue mr10"  href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" target="nodeFrame">
		<span class="left"></span>
		<span class="middle"><em class="add">新增帐户</em></span>
		<span class="right"></span>
	</a>
</div>
<div class="content clear">
 <div class="f">
          <div class="right v_list_show">
                <div class="search_a" id="info_list_search">
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
	                    <div class="right_1">
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
			 	                   <a title="排序模式切换/ALT+R" onclick="hg_switch_order('account_list');"  class="common-list-paixu"></a>
			                    </div>
                            </div>
                            <div class="common-list-right">
                                <div class="common-list-item wd100">app数</div>
                                <div class="common-list-item wd100">注册IP</div>
                                <div class="common-list-item wd100">邮箱</div>
                                <div class="common-list-item wd100">所用邀请码</div>
                                <div class="common-list-item wd100">状态</div>
                                <div class="common-list-item wd60">编辑</div>
                                <div class="common-list-item wd60">删除</div>
                                <div class="common-list-item open-close wd120">添加人/时间</div>
                            </div>
                            <div class="common-list-biaoti">
						        <div class="common-list-item">用户名</div>
					        </div>
                        </li>
                    </ul>
	                <ul class="common-list hg_sortable_list public-list" id="account_list" data-order_name="order_id" data-table_name="account">
					    {if $list}
		       			    {foreach  $list  as $k => $v}
		                      {template:unit/user_list}
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
					       	<a onclick="return hg_ajax_batchpost(this, 'audit', '审核', 1, 'id', '&status=1', 'ajax');" name="batdelete">审核</a>
			      			<a onclick="return hg_ajax_batchpost(this, 'audit', '打回', 1, 'id', '&status=2', 'ajax');" name="batdelete">打回</a>
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
{template:unit/record_edit}
{template:foot}