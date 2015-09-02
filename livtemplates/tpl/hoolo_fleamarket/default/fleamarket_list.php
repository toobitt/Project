{template:head}
{code}
$_INPUT['status'] = $_INPUT['status'] ? $_INPUT['status'] : 1;
$_INPUT['cat'] = $_INPUT['cat'] ? $_INPUT['cat'] : 0;
{/code}
{css:vod_style}
{js:vod_opration}
{js:contribute_sort}
{code}
$list_data = $list[0]['data'];
$list = $list[0]['data'];

{/code}
{template:list/common_list}
{css:edit_video_list}
{css:mark_style}
<script type="text/javascript">
	var id = '{$id}';
	var frame_type = "{$_INPUT['_type']}";
	var frame_sort = "{$_INPUT['_id']}";
	function hg_road_delete(id)
	{
		if(confirm('您确定要删除此条记录?'))
		{
			var url = './run.php?mid=' + gMid + '&a=delete&id=' + id + '&infrm=1&ajax=1';
			hg_request_to(url);	
		}
	}
	function hg_road_call_delete(json)
	{
		var obj = new Function("return" + json)();
		var ids = obj.id;
		var data = ids.split(",");
		for(i=0;i<data.length;i++)
		{
			$("#r_"+data[i]).slideUp(5000).remove();
		}
		if($("#checkall").attr('checked'))
		{
			$("#checkall").removeAttr('checked');
		}
		if($('#edit_show'))
		{
			hg_close_opration_info();
		}
	}
	$(function(){
		if(id)
		{
		   hg_show_opration_info(id,frame_type,frame_sort);
		}
		tablesort('road_list','road','orderid');
		$("#road_list").sortable('disable');
	});
	
	function hg_check_auth()
	{
		if($("#auth-info").css("display") == 'none')
		{
			$("#auth-info").show();
			$.get("./run.php?mid=" + gMid + "&a=show_plat_auth&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass,{key:''},
					function (data)	{
					$("#auth-info").html(data);
				 });	
		}
		else
		{
			var str='<div id="auth-loading"></div>';
			$("#auth-info").html(str);
			$("#auth-info").hide();	
		}
	}
	
	function hg_request_auth(platid,type)
	{
		$.get("./run.php?mid="+gMid+"&a=request_auth&gmid="+gMid+"&platid="+platid+"&type="+type+"&admin_id=" + gAdmin.admin_id +"&admin_pass="+gAdmin.admin_pass,{key:''},
					function(data) {
					var obj = eval('('+data+')');
					var url = obj[0].url;
					window.open(url);
				});
	}	
</script>
<style sytle="text/html">
#auth-info{position:absolute;right:0px;top:0px;border:1px solid #DDDDDD;border-top:none;background:#EFEFEF;width:420px;min-height:200px;float:left;z-index:4;display:none;padding:10px 10px;}
#auth-info li{margin-bottom:10px;}
#auth-loading{background:url("{$RESOURCE_URL}loading.gif") left no-repeat;width:50px;height:50px;}
</style>
<div id="auth-info">
	<div id="auth-loading"></div>
</div>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']}style="display:none"{/if}>
   <a class="blue mr10" href="./run.php?mid={$_INPUT['mid']}&a=form{$_ext_link}" target="nodeFrame">
		<span class="left"></span>
		<span class="middle"><em class="add">新增交易</em></span>
		<span class="right"></span>
	</a>
	<!--
	<a class="blue mr10" id="auth-check" onclick="hg_check_auth();">
		<span class="left"></span>
		<span class="middle"><em class="set">查看授权</em></span>
		<span class="right"></span>
	</a>
	-->
</div>
<div class="content clear">
	<div class="f">
		<div class="right v_list_show">
			<div class="search_a" id="info_list_search">
			    <span class="serach-btn"></span>
				<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
					<div class="select-search">
						{code}
							$attr_status=array(
								'class' => 'colonm down_list data_time',
								'show' => 'status_show',
								'width' =>104,
								'state' =>0,
							);                       
						{/code}
						{template:form/search_source,status,$_INPUT['status'],$_configs['status'],$attr_status}	
				
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
			<form action="" method="post" name="listform" style="position: relative;">
				<!-- 标题 -->
               <ul class="common-list public-list-head">
                    <li class="common-list-head clear">
                        <div class="common-list-left" style="width:30px;">
                            <div class="common-list-item" onclick="hg_switch_order('road_list');"  title="排序模式切换/ALT+R"><a class="common-list-paixu"></a></div>
                        </div>
                        <div class="common-list-right">
                        	<div class="common-list-item open-close news-fabu wd80">来源</div>
                        	<div class="common-list-item open-close news-fabu wd80">类型</div>
                            <div class="circle-zt common-list-item open-close wd60">操作</div>
                            <div class="common-list-item open-close wd60">状态</div>
                            <div class="common-list-item wd100">添加人/添加时间</div>
                        </div>
                        <div class="common-list-biaoti ">
					        <div class="common-list-item">标题</div>
				        </div>
                    </li>
                </ul>				
				<ul class="common-list public-list" id="road_list">
					{if $list_data && is_array($list_data)}
						{foreach $list_data as $k => $v}
							<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['orderid']}">
							    <div class="common-list-left" style="width:30px;">
							        <div class="common-list-item">
							            <div class="common-list-cell">
							                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"/></a>
							            </div>
							        </div>
							    </div>
							    <div class="common-list-right">
									{code}
										$plog_img = '';										
										if($v['glog'])
										{
												$plog_img = $v['glog']['host'] . $v['glog']['dir'] . '40x30/' . $v['glog']['filepath'] . $v['glog']['filename'];												
										}
									{/code}	
									<!--2013.07.17 -->
									<div class="common-list-item circle-ms wd80">
							            <div class="common-list-cell">
 						                {code}
		    								if($v['user_id']>0)
											{
											    $formis="编辑添加";
											}
											else
											{
											    $formis="其他";
											}
										{/code}
							                    <span>
												{$formis}
							                    </span>
							            </div>
							        </div>
							        <!--2013.07.17 -->
									<!--2013.07.12 -->
							        <!--2013.07.12 -->						    
								    <div class="common-list-item circle-ms wd80">
							            <div class="common-list-cell">
							                    <span style="color:{$v['color']};">{$v['group_name']}</span>
							            </div>
							        </div>
							        <div class="common-list-item circle-bj wd60">
							            <div class="common-list-cell" style="width:48px;">
							                    <a title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><em class="b2" style="background-position: -60px -24px;width:16px;height:16px;margin:10px 0 0 0 ;"></em></a>
							                    <a title="删除" href="javascript:hg_road_delete({$v['id']});"><em class="b3" style="background-position: -64px -118px;width:16px;height:16px;margin:10px 10px 0 0 ;"></em></a>
							            </div>
							        </div>
									{code}
										switch($v['state'])
										{
											case 0:
												$v['status'] = '未审核';
												break;
											case 1:
												$v['status'] = '已审核';
												break;
											case 2:
												$v['status'] = '已打回';
												break;
											default:
												$v['status'] = '未审核';
												break;
										}
									{/code}							        
							        <div class="common-list-item circle-zt wd60">
							            <div class="common-list-cell">
							                   <div class="common-switch-status"><span  id="statusLabelOf{$v['id']}" _id="{$v['id']}" _state="{$v['state']}" style="color:{$list_setting['status_color'][$v['status']]};">{$v['status']}</span></div>
							            </div>
							        </div>
							        <div class="common-list-item wd100">
							            <div class="common-list-cell">
							                <span class="common-user">{$v['uname']}</span>
			   								<span class="common-time">{$v['create_time']}</span>
							            </div>
							        </div>
							    </div>
							    <div class="common-list-biaoti" style="cursor:pointer;">
							    	<div class="common-list-item biaoti-transition">
								        <div class="common-list-cell">
											 {code}
												$log_img = '';
												if($v['pic'][0])
												{
													if($v['local_img'])
													{
														$log_img = $v['pic'][0]['host'] . $v['pic'][0]['dir'] . '40x30/' . $v['pic'][0]['filepath'] . $v['pic'][0]['filename'];	
													}
													else
													{
														$log_img = $v['pic'][0]['host'] . $v['pic'][0]['dir'] . $v['picsize']['thumbnail'] . $v['pic'][0]['filepath'] . $v['pic'][0]['filename'];									
													}					
												}
											 {/code}	
								        	 {if $log_img}<img src="{$log_img}" style="width:40px;height:30px;margin-right:10px;"/>{/if}
								        	 <span style="{if $v['address']}display: inline-block;vertical-align: middle;{/if}">
								        	 <span id="title_{$v['id']}"  class="common-list-overflow" style="max-width:350px;{if $v['address']}display:block;{/if}"><a title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
								        	 <span class="m2o-common-title">{$v['address']}</span></a></span>
								        	 </span>						      
								        </div> 
							        </div>         
							    </div>	
							</li>
						{/foreach}
					{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
						<script>hg_error_html(status_list,1);</script>
					{/if}
				</ul>
				
				
				<ul class="common-list public-list">
					<li class="common-list-bottom clear">
						<div class="common-list-left">
							<input type="checkbox" name="checkall" value="infolist" title="全选" rowtag="LI" /> 
							 <a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'audit', '审核', 1, 'id', '&audit=1', 'ajax', 'hg_change_status');" name="audit">审核</a>
			         		 <a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'audit', '打回', 1, 'id', '&audit=0', 'ajax', 'hg_change_status');" name="back">打回</a>
			         		 <a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" name="delete">删除</a>
						</div>
						{$pagelink}
					</li>
				</ul>
				
				<div class="edit_show">
					<span class="edit_m" id="arrow_show" style="position:absolute;"></span>
					<div id="edit_show"></div>
				</div>
			</form>
		</div>
		
		</div>
		
	</div>
</div>
   <div id="infotip"  class="ordertip"></div>
   <div id="getimgtip"  class="ordertip"></div>
</body>
<script type="text/javascript">
</script>
{template:foot}