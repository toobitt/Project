{template:head}
{css:common/common_list}
{js:common/common_list}
{css:vod_style}
{css:vote_from}
{css:2013/list}
{css:public}
{css:common/common}
{js:2013/ajaxload_new}
{js:2013/list}
{js:vote/vote_list}
{js:vote}
{template:list/common_list}
{code}
	if(!isset($_INPUT['state']))
	{
		$_INPUT['state'] = -1;
	}
	
	if(!isset($_INPUT['install_type']))
	{
		$_INPUT['install_type'] = -1;
	}
	if(!isset($_INPUT['source_type']))
	{
		$_INPUT['source_type'] = -1;
	}
	
	if(!isset($_INPUT['date_search']))
	{
		$_INPUT['date_search'] = 1;
	}
	//print_r($list);
{/code}
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a class="blue mr10" href="?mid={$_INPUT['mid']}&a=form{$_ext_link}">
		<span class="left"></span>
		<span class="middle"><em class="add">新增客户</em></span>
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
							$install_type = array(
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
							$_configs['vote_state'][-1] = '全部状态';
							$_configs['install_type'][-1]='全部类型';
						{/code}
						{template:form/search_source,install_type,$_INPUT['install_type'],$_configs['install_type'],$install_type}
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
				
				<div id="infotip" class="ordertip" ></div>
				<div id="add_question"  class="single_upload" style="min-height:100px;">
					<div id="question_option_con"></div>
				</div>
                <form method="post" action="" name="listform">
                <!-- 标题 -->
                   <ul class="common-list public-list-head" id="list_head">
                        <li class="common-list-head clear">
                            <div class="common-list-left">
                                <div class="common-paixu common-list-item"><a class="common-list-paixu" {if !$list['colname']}onclick="hg_switch_order('vodlist');"{/if}  title="排序模式切换/ALT+R"></a></div>
                            </div>
                            <div class="common-list-right">
                                  <div class="common-list-item open-close m2o-sorts">用户标识</div>
                            
                                   <div class="common-list-item open-close m2o-sorts">项目域名</div>
                            
<!--                                 <div class="common-list-item open-close wd60">操作</div> -->
                                
                                <div class="common-list-item open-close wd70">状态</div>
                                
                                 <div class="common-list-item open-close ">是否授权</div>
                                  <div class="common-list-item open-close ">安装类型</div>
                                 
                                <div class="common-list-item open-close ">源码类型</div>
                                 <div class="common-list-item open-close ">授权提示方式</div>
                                
                             <!--  <div class="common-list-item open-close vote-xxs wd100">最少/最多(选项数)</div>
                                <div class="common-list-item open-close wd60">投票总数</div> -->  
                                <div class="common-list-item open-close vote-jssj wd80">过期时间</div>
                                
                                <div class="common-list-item open-close vote-tjr wd120">添加人/时间</div>
                            </div>
                            <div class="common-list-biaoti ">
						        <div class="common-list-item open-close vote-biaoti">客户名称</div>
					        </div>
                        </li>
                    </ul>
                <ul id="vodlist" class="common-list channel-list  public-list hg_sortable_list" data-table_name="channel" data-order_name="order_id">
				  
				 
				    {if $list}
	       			    {foreach $list AS $k => $v}
	                  {template:unit/vote_question_list_list} 
	            
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
				       <a onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>
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

</body>
{template:unit/record_edit}
{template:foot}

