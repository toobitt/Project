{template:head}
{code}
	$attrs_for_edit = array(
		'is_push',
	);
	$org = $organization[0];
	$organization = array();
	if ($org && is_array($org))
	{
		foreach( $org as $key=>$val)
		{
			$organization[$val['id']] = $val['name'];
		}
	}
	$personal_auth = $personal_auth[0];
{/code}
{template:list/common_list}
{js:seek_help/seek_list}
<script>
var seekhelpStatus = {code}echo json_encode( $_configs['seekhelp_status'] ){/code};
var statusColor = ['#8fa8c6', '#17b202', '#f8a6a6', '#51677b'];
//未审核，已审核，已打回，已关注 
</script>
<script type="text/javascript">
	$(function(){
		$('#record-edit').on('click','.push',function(event){
			var id = $(this).data('id'),
				status = $(this).attr('_status');
			console.log(status);
			push(id,status);
			event.preventDefault();
			return false;
		});
		$('#record-edit').on('click','.audit',function(event){
			var id = $(this).data('id'),
				status = $(this).attr('_status');
			console.log(status);
			change_status(id,status);
			event.preventDefault();
			return false;
		});
	});
	function change_status(id, status)
	{
		return;
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
/*		var con = '';
		var audit = $('#record-edit').find('.audit');
		if(obj.status == 1)
		{
			con = '已审核';
			for(var i = 0;i<obj.id.length;i++)
			{
				$('#audit_'+obj.id[i]).css('color', 'green');
				$('#audit_'+obj.id[i]).text(con);
				$('#audit_'+obj.id[i]).attr('onclick','change_status('+obj.id[i]+','+obj.status+')');
			}
			audit.html('打回').attr('_status','1');
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
			audit.html('审核').attr('_status','2');   
		} */
		
		var status = parseInt(obj.status);
		for(var i = 0;i<obj.id.length;i++)
		{
			$('#audit_'+obj.id[i]).css('color', statusColor[ status ]);
			$('#audit_'+obj.id[i]).text( seekhelpStatus[ status ] );
		}
	}
	function push(id, status)
	{
		var url;
		if (status == 0)
		{
			url = "run.php?mid=" + gMid + "&a=push&id=" + id + "&status=1";
		}else{
			url = "run.php?mid=" + gMid + "&a=push&id=" + id + "&status=0";
		}
		hg_ajax_post(url);
	}
	function hg_push_back(json)
	{
		var obj = eval("("+json+")");
		var con = '';
		var push = $('#record-edit').find('.push');
		if(obj.status == 1)
		{
			con = '已推送';
			for(var i = 0;i<obj.id.length;i++)
			{
				$('#push_'+obj.id[i]).css('color', 'green');
				$('#push_'+obj.id[i]).text(con);
				$('#push_'+obj.id[i]).attr('onclick','push('+obj.id[i]+','+obj.status+')');
			}
			push.html('撤销推送').attr('_status','1');
		}
		if(obj.status == 0)
		{
			con = '未推送';
			for(var i = 0;i<obj.id.length;i++)
			{
				$('#push_'+obj.id[i]).css('color', '#8FA8C6');
				$('#push_'+obj.id[i]).text(con);
				$('#push_'+obj.id[i]).attr('onclick','push('+obj.id[i]+','+obj.status+')');
			}
			push.html('推送').attr('_status','0');   
		}
	}
</script>
<div id="hg_page_menu" class="head_op" style="display: none;">
	<a href="run.php?mid={$_INPUT['mid']}&a=form&infrm=1" class="button_6" target="formwin">新增互助</a>
	<a href="./run.php?a=relate_module_show&app_uniq=seekhelp&mod_uniq=seekhelp_comment&mod_a=show&_id={$_INPUT['_id']}&infrm=1" class="button_6" target="nodeFrame" {if !$_INPUT['_id']} style="display:none"{/if}>查看评论</a>
</div>

<!-- 搜索 -->
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
			$_INPUT['seekhelp_time'] = isset($_INPUT['seekhelp_time']) ? $_INPUT['seekhelp_time'] : 1;
			
			$status_css = array(
			'class' => 'transcoding down_list',
			'show' => 'sort_audit',
			'width' => 104,
			'state' => 0,
			);
			$default_audit = -1;
			$_configs['seekhelp_status'][$default_audit] = '所有状态';
			$_INPUT['status'] = isset($_INPUT['status']) ? $_INPUT['status'] : -1;
			
			$push_css = array(
			'class' => 'transcoding down_list',
			'show' => 'push_status',
			'width' => 104,
			'state' => 0,
			);
			$default_push = -1;
			$_configs['seekhelp_push'][$default_push] = '推送状态';
			$_INPUT['is_push'] = isset($_INPUT['is_push']) ? $_INPUT['is_push'] : -1;
			
			$reply_css = array(
			'class' => 'transcoding down_list',
			'show' => 'reply_status',
			'width' => 104,
			'state' => 0,
			);
			$default_reply = -1;
			$_configs['seekhelp_reply'][$default_reply] = '金牌回复';
			$_INPUT['is_reply'] = isset($_INPUT['is_reply']) ? $_INPUT['is_reply'] : -1;
			
			$organization_css = array(
			'class' => 'transcoding down_list',
			'show' => 'organization',
			'width' => 104,
			'state' => 0,
			);
			$default_account = -1;
			$organization[$default_account] = '所有机构';
			$_INPUT['account_id'] = isset($_INPUT['account_id']) ? $_INPUT['account_id'] : -1;
			{/code}
			{template:form/search_source,seekhelp_time,$_INPUT['seekhelp_time'],$_configs['date_search'],$time_css}
			{if $personal_auth['is_complete'] || in_array('audit',$personal_auth['action'])}
			{template:form/search_source,status,$_INPUT['status'],$_configs['seekhelp_status'],$status_css}
			{template:form/search_source,is_push,$_INPUT['is_push'],$_configs['seekhelp_push'],$push_css}
			{template:form/search_source,is_reply,$_INPUT['is_reply'],$_configs['seekhelp_reply'],$reply_css}
			{/if}
			{if $personal_auth['is_complete']}
			{template:form/search_source,account_id,$_INPUT['account_id'],$organization,$organization_css}
			{/if}
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
{code}
	//hg_pre($list);
{/code}
<style>
.wwd100{width:103px;}
</style>


<!-- 记录列表 -->
<div class="common-list-content" style="min-height:auto;min-width:auto;">
{if !$list}
	<p id="emptyTip" style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
	<script>hg_error_html('#emptyTip',1);</script>
{else}
	<form action="" method="post">
		<!-- 标题 -->
		<ul class="common-list">
			<li class="common-list-head public-list-head clear">
			    <div class="common-list-left">
			        <div class="paixu common-list-item">
			        	<a class="common-list-paixu" style="cursor:pointer;"  onclick="hg_switch_order('seekhelp_list');"></a>
			        </div>
			    </div>
			    <div class="common-list-right">
			        <div class="seekhelp-tszt common-list-item open-close wwd100">推送状态</div>
			        <div class="seekhelp-qzdx common-list-item open-close wwd100">求助对象</div>
			        <div class="seekhelp-fl common-list-item open-close wwd100">分类</div>
			        <div class="seekhelp-fl common-list-item open-close wwd100">金牌回复</div>
			        <div class="seekhelp-zc common-list-item open-close wwd100">状态</div>
			        <div class="seekhelp-sj common-list-item open-close wd150">添加人/时间</div>
			    </div>
			    <div class="common-list-biaoti">
					<div class="common-list-item">求助内容</div>
				</div>
			</li>
		</ul>
        <ul class="common-list public-list hg_sortable_list" id="seekhelp_list" data-order_name="order_id" data-table_name="seekhelp">
		{foreach $list as $k => $v}
			{template:unit/seekhelp_list}
		{/foreach}
		</ul>
		<ul class="common-list">
		    <li class="common-list-bottom clear">
			   <div class="common-list-left">
			   		{if $personal_auth['is_complete'] || in_array('delete', $personal_auth['action'])}
			      	<input type="checkbox" name="checkall" value="infolist" title="全选" rowtag="LI"/>
			      	<a onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" name="batdelete">删除</a>
			      	{/if}
			      	{if $personal_auth['is_complete'] || in_array('audit', $personal_auth['action'])}
			      	<a onclick="return hg_ajax_batchpost(this, 'audit', '审核', 1, 'id', '&status=1', 'ajax');" name="batdelete">审核</a>
			      	<a onclick="return hg_ajax_batchpost(this, 'audit', '打回', 1, 'id', '&status=2', 'ajax');" name="batdelete">打回</a>
		       		<a onclick="return hg_ajax_batchpost(this, 'push', '推至首页', 1, 'id', '&status=1', 'ajax');" name="batdelete">推至首页</a>
		       		<a onclick="return hg_ajax_batchpost(this, 'push', '撤销推送', 1, 'id', '&status=0', 'ajax');" name="batdelete">撤销推送</a>
		       		{/if}
		       </div>
		       {$pagelink}
		    </li>
		</ul>
	</form>
</div>
{/if}		
{template:unit/record_edit}
<!-- 排序模式打开后显示，排序状态的 -->
<div id="infotip"  class="ordertip"></div>
{template:foot}
<style>
.change-status{position:relative;}
.dropdown-menu{position:absolute;background: rgba(117,188,215,0.90);color: #fff;left:0;width:80px;top:40px;z-index:2;text-indent:15px;}
.dropdown-menu.up{top:auto;bottom:40px;}
.dropdown-menu li{height:34px;line-height:34px;}
.dropdown-menu li:hover{background: #51677b;}
.dropdown-menu li:not(:last-child){border-bottom: 1px solid #5faac0;}
.dropdown-menu li:first-child{border-radius:3px 3px 0px 0px;}
.dropdown-menu li:last-child{border-radius:0px 0px 3px 3px;}
.dropdown-menu .caret{position:absolute;left:14px;top:-12px;border:6px solid transparent;border-bottom-color:#75bcd7;}
.dropdown-menu.up .caret{border-bottom-color:transparent;border-top-color:#75bcd7;top:auto;bottom:-12px;}
</style>
<script type="text/x-jquery-tmpl" id="seekhelp-list-drop">
<div class="dropdown-menu status-menu {{if up}}up{{/if}}">
	{{if !up}}
	<span class="caret"></span>
	{{/if}}
	<ul>
		{{each seekhelpStatus}}
		<li _status="{{= $index}}" {{if currentText==$value}}style="display:none;"{{/if}}>{{= $value}}</li>
		{{/each}}
	</ul>
	{{if up}}
	<span class="caret"></span>
	{{/if}}
</div>
</script>