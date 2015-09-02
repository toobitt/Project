
{template:head}
{css:vod_style}
{css:vote_style}
{js:vote}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{code}
	if(!isset($_INPUT['state']))
	{
		$_INPUT['state'] = -1;
	}

	if(!isset($_INPUT['date_search']))
	{
		$_INPUT['date_search'] = 1;
	}
{/code}
<script type="text/javascript">
$(function(){
	tablesort('vodlist','vote','order_id');
	$("#vodlist").sortable('disable');
});
</script>
{js:ios/switch}
<script>
$(function(){
	var i = 1;
	var custom_ajax_post = function(id, callback){
		var url = './run.php?mid=' + gMid + '&a=voteState&id=' + id;
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
	<a class="blue mr10" href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" target="formwin">
		<span class="left"></span>
		<span class="middle"><em class="add">新增问卷</em></span>
		<span class="right"></span>
	</a>
</div>
<div class="content clear">
 <div class="f">
          <div class="right v_list_show">
                <div class="search_a" id="info_list_search" style="display:none;">
                  <span class="serach-btn"></span>
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
                    <div class="select-search">
						{code}
			
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
							$_configs['vote_state'][-1] = '全部状态';
							$type_search = '';
							$type_search[0] = '全部分类';
							if (!empty($group_info))
							{
								foreach ($group_info AS $k=>$v)
								{
									$type_search[$v['id']] = $v['name']; 
								}
							}
							$default_node_type = $_INPUT['node_type'] ? $_INPUT['node_type'] : 0;
						{/code}
						{template:form/search_source,state,$_INPUT['state'],$_configs['vote_state'],$attr_source}
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

                <div class="list_first clear"  id="list_head">
                    	<span class="left"><a class="lb" style="cursor:pointer;"  {if !$list['colname']}onclick="hg_switch_order('vodlist');"{/if}  title="排序模式切换/ALT+R"><em></em></a></span>
                        <span style="width:460px;" class="right"><a class="fb">操作</a><a class="ml" style="width:54px;">分类</a><a class="fl" style="margin-left:75px;">剩余时间</a><a class="zt" style="margin-left:26px;">状态</a><a class="tjr">添加人/时间</a></span><a class="title" style="margin-left: 50px;margin-top: 8px;">标题</a>
                </div>
                <form method="post" action="" name="listform">
                <ul class="list" id="vodlist">
				    {if $list}
	       			    {foreach $list as $k => $v}
	                      {template:unit/vote_list_list}
	                    {/foreach}
					{else}
					<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
					<script>hg_error_html(vodlist,1);</script>
	  				{/if}
					
					<li style="height:0px;padding:0;" class="clear"></li>
                </ul>
	            <div class="bottom clear">
	               <div class="left" style="width:400px;">
	                   <input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
	                  <!--  <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '审核', 1, 'id', '&audit=1', 'ajax','hg_change_status');"    name="bataudit" >审核</a>
				       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '打回', 1, 'id', '&audit=0', 'ajax','hg_change_status');"   name="batgoback" >打回</a>
				       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'move',  '移动', 0, 'id', '', 'ajax');"    name="batmove" >移动</a> -->
				       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>
				   </div>
	               {$pagelink}
	            </div>	
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
	<!--发布模板-->
	<span class="vod_fb" id="vod_fb"></span>
	<div id="vodpub" class="vodpub lightbox">
		<div class="lightbox_top">
			<span class="lightbox_top_left"></span>
			<span class="lightbox_top_right"></span>
			<span class="lightbox_top_middle"></span>
		</div>
		<div class="lightbox_middle">
			<span onclick="hg_vodpub_hide();" style="position:absolute;right:25px;top:25px;z-index:1000;background:url('{$RESOURCE_URL}close.gif') no-repeat;width:14px;height:14px;cursor:pointer;display:block;"></span>
			<div id="vodpub_body" class="text" style="max-height:500px;padding:10px 10px 0;">
			
			</div>
		</div>
		<div class="lightbox_bottom">
			<span class="lightbox_bottom_left"></span>
			<span class="lightbox_bottom_right"></span>
			<span class="lightbox_bottom_middle"></span>
		</div>				
	</div>
	<!--发布-->
</body>
{template:foot}