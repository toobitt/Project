<?php 
/* $Id: email_log_list.php 12137 2012-09-17 09:42:22Z lijiaying $ */
?>
{template:head}
{css:vod_style}
{css:edit_video_list}
{css:2013/button}
{code}
$list = $ticket_list;
$status_key = 'status';
{/code}
{template:list/common_list}
{css:common/common_list}
{css:ticked_list}
<style>

</style>
<script type="text/javascript">
	// function hg_audit_back(json)
	// {
		// var obj = eval("("+json+")");
		// var con = '';
		// if(obj.status == 1)
		// {
			// con = '已审核';
		// }
		// else if(obj.status == 2)
		// {
			// con = '已打回 ';    
		// }
		// for(var i = 0;i<obj.id.length;i++)
		// {
			// $('#audit_'+obj.id[i]).text(con);
		// }
		// if($('#edit_show'))
		// {
			// hg_close_opration_info();
		// }	
	// }
	function hg_sale_back(obj)
	{
		var sale = '';
		if(obj.state == 1)
		{
			sale = '设计/预售';
		}
		else if(obj.state == 2)
		{
			sale = '出售';    
		}
		else if(obj.state == 3)
		{
			sale = '结束';    
		}
		for(var i = 0;i<obj.id.length;i++)
		{	
			$('#sale_'+obj.id[i]).text(sale);
		}	
	}
	$(function(){
		$('#record-edit').on('click', '.ticket_sale', function(){
			var state = $(this).data('state'),
				id = $(this).data('id'),
				url = './run.php?mid=' + gMid + '&a=sale_state&id=' + id + '&state=' + state + '&ajax=1';
			$.getJSON(url,function( json ){
				if( json['callback'] ){
					eval( json['callback'] );
				}else{
					hg_sale_back( json[0] );
				}
			})
			return false;
		});
		$('#record-edit').on('click', '.options-publish', function(){
			var self = $(this);
			var id = self.data('id');
			var url = './run.php?mid=' + gMid + '&a=get_column&ajax=1';
			$.getJSON(url, {id : id}, function( data ){
				if( data['callback'] ){
					eval( data['callback'] );
				}else if( data && $.isArray(data) && data.length ){
					var column = data[0]['column'],
						select = data[0]['column_id_selected'];
					$('#add-publish-tpl').tmpl( column ).appendTo( $('.publish-area').find('ul').empty() );
					$('input.selectId').val( select );
					var aselect = select.split(','), PublishTitle = [];
					if( aselect.length ){
						$.each(aselect, function(k, v){
							var obj = $('.publish-area').find('li[_id=' + v + ']');
							obj.find('input').attr('checked', true);
							PublishTitle.push(obj.find('label').html());
						});
					}
					$('input.selectName').val( PublishTitle.join(',') );
					$('.publish-wrap').data('id', id).data('column_id', select).removeClass('pop-hide');
				}
			});
		});
		$('.common-list-content').on('click', '.publish-close', function(){
			$('.publish-wrap').addClass('pop-hide');
		});
		
		$('.common-list-content').on('click', '.publish-area input', function(){
			var publishId = [], publishTitle = [];
			var box = $(this).closest('.publish-wrap');
			box.find('li').each(function(k, v){
				var $this = $(this);
				if( $this.find('input').prop('checked')){
					publishId.push($this.attr('_id'));
					publishTitle.push( $this.find('label').html() );
				}
			});
			$('input.selectId').val( publishId.join(',') );
			$('input.selectName').val( publishTitle.join(',') );
		});
		
		$('.common-list-content').on('click', '.publish-save', function(){
		var self = $(this),
			box = $('.publish-wrap');
		var id = box.data('id'),
			column_id = $('input.selectId').val(),
			column_title = $('input.selectName').val();
		var url = './run.php?mid=' + gMid + '&a=publish&ajax=1';
		
		$.getJSON(url, {column_id : column_id, id : id },function( json ){
			if( json['callback'] ){
				eval( json['callback'] );
			}else{
				var obj = $('.common-list-content').find('.common-list-data[name="' + id + '"]');
				var Acolumn = column_title.split(','), strHtml = '';
				$.each(Acolumn, function(key, value){
					strHtml += '<span>' + value + '</span>';
				})
				obj.find('.ticket-fabu').find('span[id^="column_"]').html( strHtml );
				$('.publish-close').click();
				$('.record-edit-close').click();
			}
		});
	});
		
	})
</script>
{code}
	//print_r($ticket_list[0]);
{/code}
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']}style="display:none"{/if}>
   <a class="blue mr10"  href="./run.php?mid={$_INPUT['mid']}&a=form{$_ext_link}" target="nodeFrame">
		<span class="left"></span>
		<span class="middle"><em class="add">新增演出</em></span>
		<span class="right"></span>
	</a>
</div>
<div class="content clear">
	<div class="f">
		<div class="right v_list_show">
			<!-- 搜索 -->
			<div class="search_a" id="info_list_search">
			    <span class="serach-btn"></span>
				<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
					<div class="select-search">
						{code}
						$time_css = array(
							'class' => 'transcoding down_list',
							'show' => 'time_item',
							'width' => 104,	
							'state' => 1,/*0--正常数据选择列表，1--日期选择*/
						);
						$_INPUT['show_time'] = $_INPUT['show_time'] ? $_INPUT['show_time'] : 1;
						{/code}
						{template:form/search_source,show_time,$_INPUT['show_time'],$_configs['date_search'],$time_css}
						{template:form/search_weight}
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
			<div class="common-list-content">
			<form action="" method="post" name="listform" class="common-list-form">
				<!-- 标题 -->
				<ul class="common-list public-list-head"  id="list_head">
					<li class="common-list-head clear">
						<div class="common-list-left">
							<div class="common-list-item paixu open-close">
								<a onclick="hg_switch_order('ticket_list');"  title="排序模式切换/ALT+R" class="common-list-paixu"></a>
							</div>
						</div>
					<div class="common-list-right">
						<div class="common-list-item">场次管理</div>
						<div class="common-list-item wd60">状态</div>
						<div class="common-list-item wd60">分类</div>
						<div class="common-list-item ticket-fabu common-list-pub-overflow">发布至</div>
						<div class="common-list-item wd60">权重</div>
						<div class="common-list-item wd60">审核</div>
						<div class="common-list-item wd150">添加人/时间</div>
					</div>
					<div class="common-list-biaoti">
						<div class="common-list-item">名称</div>
					</div>
					</li>
				</ul>
				<ul class="common-list public-list  hg_sortable_list" data-order_name="order_id" id="ticket_list">
					{if $ticket_list}
						{foreach $list as $k => $v}
							{template:unit/ticket_list}
						{/foreach}
					{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
						<script>hg_error_html(status_list,1);</script>
					{/if}
				</ul>
				<div class="clear"></div>
				
				<ul class="common-list public-list">
					<li class="common-list-bottom clear">
					<div class="common-list-left">
						<input type="checkbox" name="checkall" value="infolist" title="全选" rowtag="LI" /> 
						<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '审核', 1, 'id', '&audit=1', 'ajax','hg_change_status');"    name="bataudit" >审核</a>
						<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '打回', 1, 'id', '&audit=0', 'ajax','hg_change_status');"   name="batgoback" >打回</a>
						<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>
					</div>
					{$pagelink}
					</li>
				</ul> 
				 <!--发布box-->
			    <div class="pop-box publish-wrap pop-hide">
					<div class="pop-title bus-title">发布至
					 <input type="button" class="pop-save-button publish-save" value="保存"/>	
					 <a class="pop-close-button2 publish-close"></a>
					</div>
					<div class="publish-area">
						<ul></ul>
						<input type="hidden" class="selectId" value=""/>
						<input type="hidden" class="selectName" value=""/>
					</div>
				</div>    	
			</form>
			</div>
		</div>
		</div>
		<div class="edit_show">
			<span class="edit_m" id="arrow_show"></span>
			<div id="edit_show"></div>
		</div>
	</div>

   <div id="infotip"  class="ordertip"></div>
   <div id="getimgtip"  class="ordertip"></div>
</body>

<script type="text/x-jquery-tmpl" id="add-publish-tpl">
	<li _id='${id}'><input type="checkbox" id="publish_${id}"/><label for="publish_${id}">${title}</label></li>
</script>


{template:unit/record_edit}
{template:foot}