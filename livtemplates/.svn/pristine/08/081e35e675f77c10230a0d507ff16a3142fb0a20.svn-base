
{template:head}
{css:common/common_list}
{js:common/common_list}
{css:vod_style}
{js:public_bicycle/station}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{code}
	if(!isset($_INPUT['state']))
	{
		$_INPUT['state'] = -1;
	}
	
	if(!isset($_INPUT['company']))
	{
		$_INPUT['company'] = -1;
	}
	
	if(!isset($_INPUT['date_search']))
	{
		$_INPUT['date_search'] = 1;
	}
{/code}
<script type="text/javascript">
$(function(){
	tablesort('vodlist','station','order_id');
	$("#vodlist").sortable('disable');
});
</script>
{js:ios/switch}
<script>
$(function(){
	var i = 1;
	var custom_ajax_post = function(id, callback){
		var url = './run.php?mid=' + gMid + '&a=stationStatus&id=' + id;
		hg_ajax_post(url,"","", callback);
	}
	var onandoff = function(self, state){
		var tmp = 'mySwitchCallback' + ++i;
		window[tmp]= function(ajax){
					var title = '';
					if(ajax == 1){
						title = '已审核';
					}else{
						title = '待审核';
					}/*else{
						self.trigger('callback', [state == 'on' ? 'off' : 'on']);
						return;
					}*/
					self.selector.attr('title', title);
					self.trigger('callback', ['ok']);
					delete window[tmp];
				}
				custom_ajax_post(self.data('data'), tmp);
	}
	$('.need-switch').each(function(){
		$(this).switchButton({
			data : $(this).attr('vid'),
			init : $(this).attr('state') > 0 ? 'on' : 'off',
			on : function(self){
				onandoff(self, 'on');
			},
			off : function(self){
				onandoff(self, 'off');
			}
		})
	});
});
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a class="blue mr10" href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" target="nodeFrame">
		<span class="left"></span>
		<span class="middle"><em class="add">新增站点</em></span>
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
							$attr_source_type = array(
								'class' => 'transcoding down_list',
								'show' => 'source_type_show',
								'width' => 104,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
							);
							$attr_source = array(
								'class' => 'transcoding down_list',
								'show' => 'transcoding_show',
								'width' => 104,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
							);
							$attr_date = array(
								'class' => 'colonm down_list data_time',
								'show' => 'colonm_show',
								'width' => 104,/*列表宽度*/
								'state' => 1,/*0--正常数据选择列表，1--日期选择*/
							);
							
							$company = $company[0];
							$company[-1] = '所有公司';
						{/code}
						{template:form/search_source,company,$_INPUT['company'],$company,$attr_source_type}
						{template:form/search_source,state,$_INPUT['state'],$_configs['status'],$attr_source}
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
                    <div class="custom-search">
						{code}
							$attr_creater = array(
								'class' => 'custom-item',
								'state' =>2, /*0--正常数据选择列表，1--日期选择, 2--input自动检索*/
								'place' =>'添加人'
							);
						{/code}
						{template:form/search_input,user_name,$_INPUT['user_name'],1,$attr_creater}
					</div>
                    </form>
                </div>
				
				<div id="infotip" class="ordertip" ></div>
                <form method="post" action="" name="listform">
                <!-- 标题 -->
                   <ul class="common-list public-list-head" id="list_head">
                        <li class="common-list-head clear">
                            <div class="common-list-left">
                                <div class="common-paixu common-list-item"><a class="common-list-paixu" onclick="hg_switch_order('vodlist');" title="排序模式切换/ALT+R"></a></div>
                            </div>
                            <div class="common-list-right">
                            	<div class="common-list-item open-close wd100">公告</div>
                                <div class="common-list-item open-close">操作</div>
                            	<div class="common-list-item open-close wd80">运营公司</div>
                                
                                <div class="common-list-item open-close wd60">可借车数</div>
                                <div class="common-list-item open-close wd60">可停车位</div>
                                <div class="common-list-item open-close wd80">区域划分</div>
                                <div class="common-list-item open-close wd60">状态</div>
                                <div class="common-list-item open-close wd120">添加人/时间</div>
                            </div>
                            <div class="common-list-biaoti ">
						        <div class="common-list-item open-close">站点名称</div>
					        </div>
                        </li>
                    </ul>
                <ul class="common-list public-list" id="vodlist">
				    {if $list}
	       			    {foreach $list as $k => $v}
	                      {template:unit/stationlist}
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
	<div id="getimgtip"  class="ordertip"></div>
</body>
{template:foot}