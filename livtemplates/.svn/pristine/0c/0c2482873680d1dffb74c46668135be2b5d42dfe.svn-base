<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:interview}
{js:interview_pic}
{js:vod_opration}
{js:jquery-ui-1.8.16.custom.min}
{css:jquery.lightbox-0.5}
{js:jquery.lightbox-0.5}
{template:list/common_list}
<script type="text/javascript">
	function hg_check_auth()
	{
		if($("#auth-info").css("display") == 'none')
		{
			$("#auth-info").show();
			$.get("./run.php?mid=" + gMid + "&a=get_plat_name&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass,{key:''},
				function (data)	{
					//data = eval(data);
					//alert(data);
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
	function hg_get_auth(platid,type,sort)
	{
		var con_sort = $("#sort_"+platid).val();	
		if (con_sort==0)
		{
			alert('请选择分类');
		}else{
			$.get("./run.php?mid="+gMid+"&a=get_auth&kid="+gMid+"&platid="+platid+"&type="+type+"&con_sort="+con_sort+"&admin_id=" + gAdmin.admin_id +"&admin_pass="+gAdmin.admin_pass,{key:''},
					function(data) {
					var obj = eval('('+data+')');
					var url = obj[0].url;
					//window.open(url);
					location.href = url;
			});
		}
		
	}
	var push_queue_id = '';
	function push_queue(id)
	{
		push_queue_id = id;
		var url = "./run.php?mid="+gMid+"&a=push_queue&id="+id;
		hg_ajax_post(url);
		$("#queue_"+id).html("正在获取...");
	}
	function hg_queue_back(json)
	{
		var json_data = $.parseJSON(json);
		if (json_data.error==1)
		{
			$("#queue_"+push_queue_id).html("<font color='red'>"+json_data.msg+"</font>");
			var t = setTimeout("back('获取报料')",5000);
		}
		if(json_data.error==2)
		{
			$("#queue_"+push_queue_id).html(json_data.msg);
			var t = setTimeout("back('获取成功')",2000);
			var t = setTimeout("back('获取报料')",5000);
			
		}
		if(json_data.error==3)
		{
			$("#queue_"+push_queue_id).html(json_data.msg);
			var t = setTimeout("back('获取报料')",5000);
			
		}	
	}
	function back(str)
	{
		$("#queue_"+push_queue_id).html(str);
		clearTimeout(t);
	}
	function hg_request_auth(platid,type,con_sort)
	{
		$.get("./run.php?mid="+gMid+"&a=get_auth&kid="+gMid+"&platid="+platid+"&type="+type+"&con_sort="+con_sort+"&admin_id=" + gAdmin.admin_id +"&admin_pass="+gAdmin.admin_pass,{key:''},
				function(data) {
				var obj = eval('('+data+')');
				var url = obj[0].url;
				window.open(url);
		});
	}
	function hg_audit_back(id)
	{
		 var ids=id.split(",");
		 for(var i=0;i<ids.length;i++)
		 {
			$('#status_'+ids[i]).text('已审核');
		 }
	}
	function hg_back_back(id)
	{
		 var ids=id.split(",");
		 for(var i=0;i<ids.length;i++)
		 {
			$('#status_'+ids[i]).text('未审核');
		 }
	}
	function hg_reset_auth(id)
	{
		$.get("./run.php?mid="+gMid+"&a=reset_auth&kid="+gMid+"&id="+id+"&admin_id=" + gAdmin.admin_id +"&admin_pass="+gAdmin.admin_pass,{key:''},
				function(data) {
				var obj = eval('('+data+')');
				var url = obj[0].url;
				location.href = url;
		});
	}
	function hg_reset_auth_back(json)
	{
		alert(json);
	}
</script>
<style>
#auth-info{position:absolute;right:0px;top:0px;border:1px solid #DDDDDD;border-top:none;background:#EFEFEF;width:430px;min-height:200px;float:left;z-index:4;display:none;padding:10px 10px;}
#auth-info li{margin-bottom:10px;}
#auth-loading{background:url("{$RESOURCE_URL}loading.gif") left no-repeat;width:50px;height:50px;}
</style>
<div id="auth-info">
	<div id="auth-loading"></div>
</div>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	
	
	<div id="hg_page_menu" class="head_op_program">
		<span  class="button_6" style="font-weight:bold;" onclick="hg_check_auth();">添加帐户</span>
	</div>
	
	
	<div class="content clear">
 		<div class="f">
			<div class="right v_list_show">
			
				<div class="search_a" id="info_list_search">
	                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
	                    <div class="right_1">{code}
							$attr_date = array(
								'class' => 'down_list data_time',
								'show' => 'app_show',
								'width' => 104,/*列表宽度*/		
								'state' => 1, /*0--正常数据选择列表，1--日期选择*/
							);
							if(!$_INPUT['date_search'])
							{
								$_INPUT['date_search'] = 1;
							}
							{/code}
							{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
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
	
	                
	                
	                <div class="list_first clear"  id="list_head">
	                    <span class="left" style="width:120px">
	                    	<a class="lb" style="cursor:pointer;"   onclick="hg_switch_order('user_list');"  title="排序模式切换/ALT+R">
	                    		<em></em>
	                    		<a class="fl">头像</a>
	                    	</a>
	                    </span>
                        <span class="right"  style="width:770px">
                       		<a class="fb">编辑</a>
                       		<a class="fb">删除</a>
                       		<a class="fl">平台名称</a> 
                        	<a class="fl">微博类型</a>
                        	<a class="fl">状态</a>
                        	<a class="fl" style="width: 115px">授权过期时间</a>
                        	<a class="fl">操作</a>
                        	<a class="tjr">添加人/时间</a>    	
                       </span>
                        <span class="title overflow">
                        	 <a>用户名称</a>
                        </span>                        	                                 
	                </div>	                
	                


	                <form method="post" action="" name="pos_listform">
		                <ul class="list hg_sortable_list" data-order_name="order_id" id="user_list">
		                	{code}
		                		if(is_array($formdata) && !empty($formdata))
		                		{
		                			$contribute_user_list[0] = $formdata;
		                		}
		                	{/code}
							{if $contribute_user_list[0]}
			       			    {foreach $contribute_user_list[0] as $k => $v}		       			    
			                      {template:unit/contribute_user_list}
			                    {/foreach}
			  				{/if}
							<li style="height:0px;padding:0;" class="clear"></li>
		                </ul>
			            <div class="bottom clear">
			               <div class="left">
			                   <input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
						      	<a name="batdelete"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" style="cursor:pointer;">删除</a>					       
						      	<a name="bataudit"  onclick="return hg_ajax_batchpost(this, 'audit', '审核', 1, 'id', '', 'ajax');" style="cursor:pointer;">审核</a>					       
						      	<a name="batback"  onclick="return hg_ajax_batchpost(this, 'back', '打回', 1, 'id', '', 'ajax');" style="cursor:pointer;">打回</a>					       
						   </div>
			               {$pagelink}
			            </div>	
	    		    </form>
	    		    
	    		    
	    		   
				</div>
			</div>
		</div>
	<div id="infotip"  class="ordertip"></div>
</body>
{template:foot}