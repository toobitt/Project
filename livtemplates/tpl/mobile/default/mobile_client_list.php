<?php 
/* $Id: mobile_client_list.php 31225 2014-03-27 09:56:46Z chenmengjie $ */
?>
{template:head}
{css:contribute_style}
{css:vod_style}
{css:edit_video_list}
{js:vod_opration}
{js:jquery-ui-1.8.16.custom.min}
{js:contribute}
{css:common/common_list}
{css:contribute_list}
{js:common/common_list}
{js:jqueryfn/jquery.tmpl.min}
<script type="text/javascript">
$(function(){
	tablesort('contribute_list','content','order_id');
	$("#contribute_list").sortable('disable');
});

function hg_instantMessaging_callback(tpl) 
{
	$('#push_form').html(tpl).fadeIn();
}
function hg_doinstantMessaging_callback()
{
	$('#push_form').fadeOut();
}

var gId = 0;
function hg_show_opration_info_mobile(id,token,_type,_id)
{
	
	 if(gDragMode)
	 {
		   return;
	 }
	 var reg=new RegExp(" ","g"); //创建正则RegExp对象  
	 var stringObj = token;  
	 var newstr=stringObj.replace(reg,"_");
	 /*判断当前有没有打开，打开的话就关闭*/
	 if($('#vodplayer_'+id+'_'+newstr).length)
	 {
		 hg_close_opration_info();
		 return;
	 }
	 /*关闭之前保存选项卡的状态到cookie*/
	 hg_saveItemCookie();

	gId = id;

	var ajaxcallback = function(){
		var param_type = '';
		var param_sort = '';
		if(_type)
		{
			param_type = '&frame_type='+_type;
		}
		
		if(_id)
		{
			param_sort = '&frame_sort='+_id;
		}
		var url = "./run.php?mid="+gMid+"&a=show_opration&id="+id+"&device_token="+token+param_type+param_sort;
		hg_ajax_post(url);
	}

	;(function(){
		var h=$('body',window.parent.document).scrollTop();
		$('#edit_show').html('<img src="'+ RESOURCE_URL + 'loading2.gif' +'" style="width:50px;height:50px;"/>');
		click_title_show(h, ajaxcallback);
	})();
}
</script>
<script type="text/javascript">
$(function(){
	$('body')
	.on('click','.insInfo',function(){
		var flag = $(this).attr('_flag'),
			url = './run.php?mid='+ gMid + '&a=get_device_log&device_token=' + flag + '&offset=0',
			wrap = $('.ins-info #info-list'),
			totleInfo = [],
			flag = 0;
		$.getJSON(url,function( json ){
			$.each(json,function(k,v){
				var	info = {
						'create_time' : v['create_time'],
						'program_name' : v['program_name'],
						'ip' : v['ip']
					};
				totleInfo.push(info);
				flag = k;
			});
			wrap.empty();
			$('#ins-tpl').tmpl(totleInfo).prependTo(wrap);
			$('.ins-info .more').data('offset',5);
			$('.ins-info').slideDown();
			if( flag >= 4 ){
				$('.ins-info .more').show();
			}
		});
	})
	.on('click','.ins-info .more',function(){
		var offset = parseInt( $(this).data('offset') ) ,
			flag = $('.insInfo').attr('_flag'),
			url = './run.php?mid='+ gMid + '&a=get_device_log&device_token=' + flag + '&offset=' + offset ,
			totleInfo = [],
			flag = 0;
		$.getJSON(url,function( json ){
			$.each(json,function(k,v){
				var	info = {
						'create_time' : v['create_time'],
						'program_name' : v['program_name'],
						'ip' : v['ip']
					};
				totleInfo.push(info);
				console.log(k);
				flag = k;
			});
			$('#ins-tpl').tmpl(totleInfo).prependTo('#info-list');
			if( flag < 4 ){
				$('.ins-info .more').hide();
			}
		});
		offset += 5;
		$(this).data('offset',offset);
	});
});
</script>
<script type="text/x-jquery-tmpl" id="ins-tpl">
<li>
	<p><span>安装时间：</span>${create_time}</p>
	<p><span>程序名称：</span>${program_name}</p>
	<p><span>ip地址：</span>${ip}</p>
</li>
</script>
<style>
#info-list{max-height: 395px;overflow-y: auto;}
#info-list span{margin-right:5px;color:#7B7B7B;}
#info-list li{border-bottom: 1px dashed #ccc;padding: 5px 0;}
#info-list li:last-child{border-bottom:none;}
.ins-info .more{border-top: 1px solid #eee;padding: 10px;color: #828282;cursor: pointer;text-align:center;}
.biaoz .content .f{min-height:900px}
.edit_show{height:1000px;}
.send-info-wrap{cursor:move;position:absolute;top:81px;left:120px;width:450px;display:none;border:5px solid gray;border-radius:3px;text-align:center;background-color:#eee;padding:20px 0;}
.send-info-inner li{margin:5px 0;}
.item-title{display:inline-block;width:60px;color:;text-align:left;color:gray;}
</style>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	<div class="content clear">
 		<div class="f">
	    	<div class="right v_list_show">
	        	<div class="search_a" id="info_list_search">
	            	<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
	                	<div class="right_1">
							{code}	
								$time_css = array(
									'class' => 'transcoding down_list',
									'show' => 'time_item',
									'width' => 104,	
									'state' => 1,/*0--正常数据选择列表，1--日期选择*/
								);
								$_INPUT['mobile_client_time'] = $_INPUT['mobile_client_time'] ? $_INPUT['mobile_client_time'] : 1;
								
								$audit_css = array(
									'class' => 'transcoding down_list',
									'show' => 'status_show',
									'width' => 104,	
									'state' => 0,
								);
								
								$default_audit = -1;
								$_configs['client_state'][$default_audit] = '所有状态';
								$_INPUT['client_state'] = $_INPUT['client_state'] ? $_INPUT['client_state'] : -1;
								
								$debug_css = array(
									'class' => 'transcoding down_list',
									'show' => 'debug_s',
									'width' => 104,	
									'state' => 0,
								);
								$default_debug = -1;
								$_configs['debug'][$default_debug] = '所有版本';
								$_INPUT['debug'] = $_INPUT['debug'] ? $_INPUT['debug'] : -1;
								
								$system_css = array(
									'class' => 'transcoding down_list',
									'show' => 'system_s',
									'width' => 104,	
									'state' => 2, /*0--正常数据选择列表，1--日期选择,2--搜索选择*/
									'method' => 'search_system',
									'key' => 'system_val',
								);
								$default_system = -1;
								$appendSystem[0][$default_system] = '所有系统';
								$_INPUT['system'] = $_INPUT['system'] ? $_INPUT['system'] : -1;
								
								$type_css = array(
									'class' => 'transcoding down_list',
									'show' => 'type_s',
									'width' => 104,	
									'state' => 2, /*0--正常数据选择列表，1--日期选择,2--搜索选择*/
									'method' => 'search_type',
									'key' => 'type_val',
								);
								$default_type = -1;
								$appendTypes[0][$default_type] = '所有类型';
								$_INPUT['type'] = $_INPUT['type'] ? $_INPUT['type'] : -1;

								
								$client_css = array(
									'class' => 'transcoding down_list',
									'show' => 'client_s',
									'width' => 104,	
									'state' => 0,
								);
								$default_client = -1;
								$appendClient[0][$default_client] = '所有程序';
								$_INPUT['client'] = $_INPUT['client'] ? $_INPUT['client'] : -1;
								
								
								$app_css = array(
									'class' => 'transcoding down_list',
									'show' => 'app_s',
									'width' => 104,	
									'state' => 0,
								);
								$default_app = -1;
								$appendApps[0][$default_app] = '所有应用';
								$_INPUT['app'] = $_INPUT['app'] ? $_INPUT['app'] : -1;
							{/code}
							{template:form/search_source,app,$_INPUT['app'],$appendApps[0],$app_css}
							{template:form/search_source,type,$_INPUT['type'],$appendTypes[0],$type_css}
							{template:form/search_source,system,$_INPUT['system'],$appendSystem[0],$system_css}
							{template:form/search_source,client,$_INPUT['client'],$appendClient[0],$client_css}
							{template:form/search_source,debug,$_INPUT['debug'],$_configs['debug'],$debug_css}						
							{template:form/search_source,client_state,$_INPUT['client_state'],$_configs['client_state'],$audit_css}
							{template:form/search_source,mobile_client_time,$_INPUT['mobile_client_time'],$_configs['date_search'],$time_css}
							<input type="hidden" name="a" value="show" />
							<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
							<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
							<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
							<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
	                	</div>
	                    <div class="right_2" style="width: 150px">
	                    	<div class="button_search">
								<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
	                        </div>
							{template:form/search_input,k,$_INPUT['k']}                        
	                    </div>
	               	</form>
	            </div>
	            <form method="post" action="" name="pos_listform">
	               <!-- 标题 -->
                    <ul class="common-list public-list-head">
                        <li class="common-list-head clear">
                            <div class="common-list-left">
                                <div class="contribute-paixu common-list-item"><a class="lb" style="cursor:pointer;"   onclick="hg_switch_order('contribute_list');"  title="排序模式切换/ALT+R"><em></em></a></div>
                            </div>
                            <div class="common-list-right">
                                
                              
                               
                                <div class="contribute-fl common-list-item open-close wd80">终端类型</div>
                                <div class="contribute-fb common-list-item open-close wd100">系统</div>
                                <div class="contribute-fl common-list-item open-close wd100">程序名称</div>
                                
                                <div class="contribute-khd common-list-item open-close wd80">友盟</div>
                                <div class="contribute-fl common-list-item open-close wd100">推荐人</div>
                                <!-- 
                                <div class="contribute-fb common-list-item open-close wd100">手机号码</div>
                                <div class="contribute-fl common-list-item open-close wd100">iccid</div>
                                -->
                                <div class="contribute-fl common-list-item open-close wd50">debug</div>
                                <div class="contribute-zt common-list-item open-close wd50">状态</div>
                                <div class="contribute-blr common-list-item open-close wd120">用户/时间</div>
                            </div>
                            <div class="common-list-biaoti">
						        <div class="common-list-item open-close contribute-title">终端标识</div>
					        </div>
                        </li>
                    </ul>
		        	<ul class="common-list public-list" id="contribute_list">
						{if($mobile_client_list)}
			       			{foreach $mobile_client_list as $k => $v} 
			                	{template:unit/client_list}
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
			            	<a onclick="return hg_ajax_batchpost(this, 'instantMessaging', '推送即时消息', 0, 'device_token','', 'ajax');">推送即时消息</a>
							<a onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'device_token','', 'ajax');">删除</a>
						</div>
			              {$pagelink}
			          </li>
			       </ul>
	    		</form>
	    		<div class="send-info-wrap" id="push_form"></div>
	    		<div class="edit_show">
					<span class="edit_m" id="arrow_show"></span>
				<div id="edit_show"></div>
				</div>
	    	</div>
		</div>
	</div>
	<div id="infotip"  class="ordertip"></div>
</body>
<script>
$(function(){
	$('body').on('click','.common-list-biaoti span',function(){
		var oTop = $(this).offset().top -2 ;
		$('#arrow_show').css('top',oTop+'px');
	});
});
</script>
{template:foot}