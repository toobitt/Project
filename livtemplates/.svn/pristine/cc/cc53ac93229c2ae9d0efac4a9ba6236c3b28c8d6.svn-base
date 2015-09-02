{template:head}
{js:jqueryfn/jquery.tmpl.min}
{js:pop/base_pop}
{js:pop/pop_list}
{code}
	if($id && $_INPUT['a']=='release')
	{
		$optext="发布";
		$a="release_mes";
	}
	else if($id)
	{
		$optext="更新";
		$a="update";
	}
	else
	{
		$optext="添加";
		$a="add_advice";
	}
	
	$client = $formdata['client'];
	$type = $formdata['device_type'];
	$system = $formdata['device_os'];
{/code}
<script type="text/javascript">
function hg_checkall()
{	
	$("input[name^='device']").attr("checked","checked");	
}
jQuery(function ($) {
	var searchUrl = 'run.php?mid=' + gMid + '&a=get_mobile_client_info',
		dataType = 'json',
		msgBox = $('#client-info-list'),
		msgData = {
			set: function ( key, value ) {
				this[key] = value;
				return this;
			}
		},
		defaultTypes = {code}echo json_encode(explode(',', $type));{/code},
		defaultSystem = {code}echo json_encode(explode(',', $system));{/code};
	
	var conditionInputs = [$('#client'), $('#type'), $('#system'), $('#device_create_time'), $('#device_update_time'), $('#app_id')],
		msgInputs = conditionInputs.concat( $('input[name=send_time]') );

	/*$(window).on( 'click' , function( event ){
		var box = $(this).closest( '.ui-multiselect-menu' ),
			k = box.find( '.search-k' ).val(),
			list = box.find( 'ul' ),
			method = $(this).data( 'method' );
		event.stopPropagation();
	}, '.search-button' );*/
	
	function getParams () {
		var params = {};
		$.each(conditionInputs, function ( i, el ) {
			if ( el.val() ) params[ el[0].name ] = el.val();
		});
		return params;
	}	 
	function searchClientInfo( fn ) {
		var data = getParams();
		$.ajax({
			url: searchUrl,
			data: data,
			dataType: 'json',
			success: fn,
			error: function ( xhr, state ) {
			}
		});	
	}
	function handleSuccess ( data ) {
		if ( !data || typeof data == 'string' ) {
			msgBox.empty().html( '<p>没有满足的终端!</p>' );
			return;
		}
		msgData.total = data.total;
		render().appendTo( msgBox.empty() );
	}
	function getMsgFromInput( input ) {
		var msg = input.prev().find('span:last').text().replace( /,\s/g, '或' );
		return '选择' == msg.substr(0, 2) ? '' : msg;
	}
	function getMsgFromAppId( input ) {
		return $('#display_app_shows_').text();
	}
	function render () {
		msgData
		.set( 'client', getMsgFromInput( msgInputs[0] ))
		.set( 'type', getMsgFromInput( msgInputs[1] ))
		.set( 'system', getMsgFromInput( msgInputs[2] ))
		.set( 'create', msgInputs[3].val() )
		.set( 'update', msgInputs[4].val() )
		.set( 'appid', getMsgFromAppId( msgInputs[5] ))
		.set( 'sendTime', msgInputs[6].val() );
			
		return $( '<p>此消息将' + 
				(msgData.sendTime ? '于：' + msgData.sendTime + ',</br>' : '') +
				'发送给</br>' + 
				('使用应用：' + msgData.appid + '</br>') +
				(msgData.client ? '安装了：' + msgData.client+'</br>' : '') +
				(msgData.type ? ' 设备为：' + msgData.type+'<br>' : '') +
				(msgData.system ? '系统为：' + msgData.system + '</br>' : '') + 
				(msgData.create ? '创建时间在：' + msgData.create + '之后</br>' : '') +
				(msgData.update ? '登陆时间在：' + msgData.update + '之后</br>' : '') +
				'所有的终端(共' + msgData.total + '台)。' +
			'</p>' );
	}

	/*应用选择*/
	var oldAppid = $('#app_id').val();
	$('#app_shows_').on('click', 'li a', function () {
		var appid = $(this).attr('attrid');
		if (oldAppid != appid) {
			$.get("run.php", {
				mid: gMid,
				a: "get_types_system",
				app_id: appid
			}, function (data) {
				resetSelect(data);
				
				searchClientInfo( handleSuccess );
			}, 'json');
		};
		oldAppid = appid;
	});

	function getOptions(data, values, arr) {
		var html = '', k, attr, id, name;
		for (k in data) {
			id = data[k].id;
			name = data[k].name;
			attr = $.inArray(id, values) != -1 ? 'selected="selected"' : '';
			$.inArray(id, values) != -1 && arr.push(id);
			html += '<option value="' + id + '" ' + attr + '>' + name + '</option>';
		}
		return html;
	}
	function resetSelect(data, first) {
		/*多选框*/
		var selects = $('.select'), types = [], system = [];
		if (data) {
			first || selects.multiselect('destroy');
			if (data[0]) {
				selects.eq(1).html( getOptions( data[0].types, defaultTypes, types ) );
				selects.eq(2).html( getOptions( data[0].system, defaultSystem, system ) );
				conditionInputs[1].val( types.join(',') );
				conditionInputs[2].val( system.join(',') );
			} else {
				selects.eq(1).html('<option value="">暂无数据</option>');
				selects.eq(2).html('<option value="">暂无数据</option>');
			}
		}
		selects.each(function (i, n) {
			var select = $(this),
				input = conditionInputs[i],
				oldValue,
				info = {},
				method = select.data( 'method' );
			info.method = method;
			select.multiselect({
				checkAllText: '全选',
				uncheckAllText: '取消全选',
				noneSelectedText: select.data( 'title' ),
				selectedList: select.data( 'length' ),
				//minWidth: 500,
				beforeopen: function () {
					oldValue = input.val();
				},
				open : function(){
					var multiselect = $('.ui-multiselect-menu');
					multiselect.prepend( $( '#tmpl' ).tmpl( info )  );
					multiselect.find( '.search-button' ).on( 'click' , function( event ){
						var self = $(event.currentTarget);
						var box = self.closest( '.ui-multiselect-menu' ),
							k = box.find( '.search-k' ).val(),
							list = box.find( '.ui-multiselect-checkboxes' ),
							method = self.data( 'method' );
						var url = './run.php?mid=' + gMid + '&a=' + method;
						$.get( url , { type_val : k } ,function( data ){
							 list.html( data )
						} );
					} );
				},
				close: function () {
					var newValue = !select.val() ? '' : select.val().join( ',' );
					var multiselect = $('.ui-multiselect-menu');
					multiselect.find( '.search-range' ).remove();
					if ( newValue == oldValue ) return;
					input.val( newValue );
					searchClientInfo( handleSuccess );
				}
			});
		});
	}
	
	/*时间选择*/
	$( '#device_create_time,#device_update_time,#send_time' ).each(function () {
		var oldDate;
		$(this).datetimepicker({
			showSecond: true,
			timeFormat: 'hh:mm:ss',
			beforeShow: function ( input, ui ) {
				oldDate = input.value;
			},
			onClose: function ( data ) {
				if ( oldDate == data ) return;
				this == $('#send_time')[0] ? render().appendTo( msgBox.empty() ) : searchClientInfo( handleSuccess );
			}
		});
	});

	$.get("run.php", {
		mid: gMid,
		a: "get_types_system",
		app_id: oldAppid
	}, function (data) {
		resetSelect(data, true);
		
		searchClientInfo( handleSuccess );
	}, 'json');

	/*请求一次，初始化*/
	searchClientInfo( handleSuccess );	
});
</script>
{css:ad_style}
{js:ad}
{js:jquery.multiselect.min}
{js:mobile/char_count}
<style>
.search-range{height:30px;position:relative;}
.search-button{cursor:pointer;position:absolute;width:20px;height:20px;right:8px;top:3px;background: url({$RESOURCE_URL}bg-all.png)  -111px -45px no-repeat;}
.search-k{width:210px;height:20px;}
.more-z-index .down_list {z-index:10001;}
.down_list {z-index:10000;}
.date-picker{width:226px;}
.ui-datepicker{z-index:999999!important;}
.error{}
.ui-multiselect { padding:2px 0 2px 4px; text-align:left }
.ui-multiselect span.ui-icon { float:right }
.ui-multiselect-single .ui-multiselect-checkboxes input { position:absolute !important; top: auto !important; left:-9999px; }
.ui-multiselect-single .ui-multiselect-checkboxes label { padding:5px !important }

.ui-multiselect-header { margin-bottom:3px; padding:3px 0 3px 4px }
.ui-multiselect-header ul { font-size:0.9em }
.ui-multiselect-header ul li { float:left; padding:0 10px 0 0 }
.ui-multiselect-header a { text-decoration:none }
.ui-multiselect-header a:hover { text-decoration:underline }
.ui-multiselect-header span.ui-icon { float:left }
.ui-multiselect-header li.ui-multiselect-close { float:right; text-align:right; padding-right:0 }

.ui-multiselect-menu { display:none; padding:3px; position:absolute; z-index:10000; text-align: left }
.ui-multiselect-menu:nth-child(1){padding-top:30px;}
.ui-multiselect-checkboxes { position:relative /* fixes bug in IE6/7 */; overflow-y:auto }
.ui-multiselect-checkboxes label { cursor:default; display:block; border:1px solid transparent; padding:3px 1px }
.ui-multiselect-checkboxes label input { height:auto; margin-right:5px; }
.ui-multiselect-checkboxes li { clear:both; font-size:0.9em; padding-right:3px }
.ui-multiselect-checkboxes li.ui-multiselect-optgroup-label { text-align:center; font-weight:bold; border-bottom:1px solid }
.ui-multiselect-checkboxes li.ui-multiselect-optgroup-label a { display:block; padding:3px; margin:1px 0; text-decoration:none }

/* remove label borders in IE6 because IE6 does not support transparency */
* html .ui-multiselect-checkboxes label { border:none }
.sel-con{display:none;position:absolute;background:url({$RESOURCE_URL}add_btn.png) no-repeat center;width:30px;height:26px;cursor:pointer;}
</style>

<script type="text/javascript">
$(function(){
	$('.char-count').charCount({maxLen : 50});
})

//获取杂志当前期数
function get_link_module(link_module){
	$('#link_module_2').val(link_module);
};
</script>
<style>
.count-info{color:#808080;float: right;margin-right: 20px;}
.count-info .count{font-family: Constantia, Georgia;font-size: 22px;}
.item-name{line-height:24px;color:#7d7d7d;display:inline-block;width:70px;text-align:right;}
</style>

<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear app-info-wrap">
<div class="ad_middle">
<form class="ad_form h_l" action="" method="post" enctype="multipart/form-data"  id="content_form">
<h2>{$optext}消息</h2>
<script>

</script>
<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">选择应用：</span>
			{code}
			$app_source = array(
				'class' => 'down_list i',
				'show' => 'app_shows_',
				'width' => 233,/*列表宽度*/		
				'state' => 0, /*0--正常数据选择列表，1--日期选择*/
			);
			
			$app_id = $formdata['appid'];
		
			if($appendApp)
			{
				foreach($appendApp as $k => $v)
				{
					$arr[$v['appid']] = $v['appname'];
				}
				$default = $app_id ? $app_id : $appendApp[0]['appid'];
			}
			{/code}
			{template:form/search_source,app_id,$default,$arr,$app_source}
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">发送时间：</span><input type="text" readonly="readonly" class="date-picker"  _time="true" name='send_time' value="{$formdata['send_time']}" id="send_time" />
		</div>
	</li>
	<!-- 
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">离线时间：</span><input type="text" name='expiry_time' value="{$formdata['expiry_time']}" /><font class="important">不填默认不保存</font>
		</div>
	</li>
	 -->
	<li class="i">
		<div class="form_ul_div clear more-z-index">
			<span class="title">链接模块：</span>
			{code}
			$item_source = array(
				'class' => 'down_list i sel-link-module',
				'show' => 'item_shows_',
				'width' => 100,/*列表宽度*/		
				'state' => 0, /*0--正常数据选择列表，1--日期选择*/
				'is_sub'=>1,
				'onclick'=>"get_link_module(this.getAttribute('attrid'))",
			);
			$group_id = $formdata['link_module'];
			$default = $group_id ? $group_id : -1;
			$gname[$default] = '选择模块';
			foreach($appendModule AS $k =>$v)
			{
				$gname[$v['module_id']] = $v['name'];
			}
			{/code}
			{template:form/search_source,link_module,$default,$gname,$item_source}
			<div>
				<span class="item-name">模块标识：</span>
				<input type="text" id="link_module_2" value="{$formdata['link_module']}" name="link_module" size="10"/>
				<span class="item-name">内容id：</span>
				<input type="text"  name="content_id" value="{$formdata['content_id']}" size="10"/>
				<span class="sel-con"></span>
			</div>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">消息内容：</span><textarea name="message" class="char-count">{$formdata["message"]}</textarea>
			<!--  <font class="important">消息最大长度50字左右</font> -->
			<div class="count-info" style="display: none;">
				<span class="tip">还可以输入</span>
				<span class="count">50</span>
				个字
			</div>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">数目：</span>
			<input type="text"  name="amount" value="{$formdata['amount']}" size="10"/>
		</div>
	</li>
	
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">客户端：</span>
			<select class="select" multiple="multiple" data-method="search_client_notice" data-length="{code}echo count($appendClient[0]);{/code}" data-title="选择客户端">
			{code} $tmpArray = explode(',', $client); {/code}
			{foreach $appendClient[0] as $k => $v}
				<option value="{$k}" {if in_array($k, $tmpArray)}selected="selected"{/if}>{$v}</option>
			{/foreach}
			</select>
			<input type="hidden" name="client" value="{$client}" id="client" />
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">设<span style="opacity:0;">设</span>备：</span>
				<select class="select" multiple="multiple" data-method="search_type_notice" data-length="{code}echo count($appendTypes[0]);{/code}" data-title="选择设备">
				{code} $tmpArray = explode(',', $type); {/code}
				{foreach $appendTypes[0] as $k => $v}
					<option value="{$k}" {if in_array($k, $tmpArray)}selected="selected"{/if}>{$v}</option>
				{/foreach}
				</select>
				<input type="hidden" name="type" value="{$type}" id="type" />
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">系<span style="opacity:0;">系</span>统：</span>
			<select class="select" multiple="multiple" data-method="search_system_notice" data-length="{code}echo count($appendSystem[0]);{/code}" data-title="选择系统">
			{code} $tmpArray = explode(',', $system); {/code}
			{foreach $appendSystem[0] as $k => $v}
				<option value="{$k}" {if in_array($k, $tmpArray)}selected="selected"{/if}>{$v}</option>
			{/foreach}
			</select>
			<input type="hidden" name="system" value="{$system}" id="system" />
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">选择版本：</span>
			{code}
			$debug_source = array(
				'class' => 'down_list i',
				'show' => 'debug_shows_',
				'width' => 100,/*列表宽度*/		
				'state' => 0, /*0--正常数据选择列表，1--日期选择*/
			);
			$_configs['debug'][-1] = '选择版本';
			$default = isset($formdata['debug']) ? $formdata['debug'] : -1; 
			{/code}
			{template:form/search_source,debug,$default,$_configs['debug'],$debug_source}
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">安装时间：</span>
			<input type="text" class="date-picker" name='device_create_time' value="{$formdata['device_create_time']}" id="device_create_time" _time=true/>
		</div>
	</li>
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">登录时间：</span>
			<input type="text" class="date-picker" name='device_update_time' value="{$formdata['device_update_time']}" id="device_update_time" _time=true/>
		</div>
	</li>
	
<!--
<li class="i">
		{code}
			$audit_css = array(
				'class' =>'transcoding down_list',
				'show' => 'audit_item',
				'width' => 100,
				'state' => 0,
				'is_sub' => 1
			);
			$formdata['sound'] = $formdata['sound']?$formdata['sound']:-1;
		{/code}
		<div class="form_ul_div clear">
			<span class="title">声音：</span>
			{template:form/search_source,sound,$formdata['sound'],$_configs['sound'],$audit_css}
		</div>
	</li>
{if($a=="add_advice")}
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">客户端：</span>
	</div>
	<table style="margin-left:70px;">
	{if $appendDevice[0]}
		{foreach $appendDevice[0] as $k=>$v}
			 <tr><td>{$v['device_token']}</td><td style="padding-left:20px;"><input name="device[]" value="{$v['device_token']}" type="checkbox"></td></tr>
		{/foreach}
	{/if}
	</table>
</li>

<li class="i">
	<div class="form_ul_div clear">
		<span class="title">全选：</span><input type="checkbox"  onclick="hg_checkall();" name="checkall" id="chk_all" value="infolist" title="全选" rowtag="LI" />
	</div>
</li>
{/if}
-->
	
</ul>
<input type="hidden" value="{$formdata['id']}" id="id" name="id" />
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}消息" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="pop"></div>
<script type="text/javascript">
$(function(){
	$('.app-info-wrap').on('click','.sel-link-module li',function(){
		var val = $('#link_module').val();
		$('.sel-con').css('display', val != -1 ? 'inline-block' : 'none' ).attr('_module',val);
	});
	$('.sel-con').click(function(){
		var classname = 'pubLib-pop-box';
		$('.'+classname).remove();
		var _this = $(this),
			aim = _this.closest('li').find('input[name="content_id"]');
		$.pop( {
			title : '添加内容',
			className : classname,
			widget : 'pubLib',
			listUrl : './run.php?mid='+gMid+'&a=get_publish_content&link_module='+ _this.attr('_module'),
			clickCall : function( event, info, widget ){
				var data = info[0],
					id = data.id;
				aim.val(id);
				$('.pop-close').click();
			}
		} );
	});
});
</script>
<script type="text/x-jquery-tmpl" id="tmpl">
<div class="search-range">
<input type="text" class="search-k"  />
<span class="search-button" data-method="${method}"></span>
</div>
</script>
<div class="right_version">
	<h2 style="display:none;"><a href="run.php?mid={$_INPUT['mid']}">返回前一页</a></h2>
	<div id="client-info-list"></div>
</div>
</div>
{template:foot}