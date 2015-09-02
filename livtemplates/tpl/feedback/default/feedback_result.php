<?php 
/* $Id: group_list.php 9410 2012-05-22 07:43:34Z lijiaying $ */
?>
{template:head}
{css:2013/list}
{css:feedback_result}
{js:vod_opration}
{js:2013/list}
{js:2013/ajaxload_new}
{js:box_model/list_sort}
{js:common/common_list}
{js:feedback/feedback_result}
{code}
    if (!isset($_INPUT['process']))
    {
        $_INPUT['process'] = -1;
    }
	$list = $list[0];
{/code}
{code}
//print_r( $list );
{/code}
<div class="common-list-content" style="min-height:auto;min-width:auto;">
<div class="search_a" id="info_list_search">
                 <!-- <span class="serach-btn"></span> --> 
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
                    <div class="select-search">
						{code}
							$attr_source = array(
								'class' => 'transcoding down_list',
								'show' => 'transcoding_show',
								'width' => 104,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
							);
							$_configs['process'][-1] = '全部状态';
						{/code}
						{template:form/search_source,process,$_INPUT['process'],$_configs['process'],$attr_source}
						<input type="hidden" name="a" value="relate_module_show" />
						<input type="hidden" name="app_uniq" value="{$_INPUT['app_uniq']}" />
						<input type="hidden" name="mod_uniq" value="{$_INPUT['mod_uniq']}" />
						<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
						<input type="hidden" name="fid" value="{$_INPUT['fid']}" />
						<div class="text-search">
                    	<div class="button_search">
							<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
                        </div>
						{template:form/search_input,k,$_INPUT['k']}                        
                    </div>
                    </div>
                   
                    </form>
                </div>
      <div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		 <a class="blue mr10" onclick="downloadFile(this);" href="javascript:###" _href="download.php?a=download_feedback&fid={$_INPUT['fid']}&process={$_INPUT['process']}&access_token={$_user['token']}" target="_self">
			<span class="left"></span>
			{if $list['list']}
			<span class="middle"><em class="excel">导出表单</em></span>
			{/if}
			<span class="right"></span>
		</a>
	</div>
<div id="add_question"  class="single_upload">
					<div id="question_option_con">
					</div>
				</div>
	<form action="" method="post">
	 <div class="m2o-list feedback-result-list">
			<!--排序模式打开后显示排序状态-->
			<div class="m2o-title m2o-flex m2o-flex-center">
		 	   <div id="infotip" class="ordertip">排序模式已关闭</div>
		       <div class="m2o-item m2o-paixu" title="排序">
		        	<a title="排序模式切换/ALT+R" class="common-list-paixu"></a>
		       </div>
            <div class="m2o-item m2o-flex-one m2o-bt" title="已回收表单名称">已回收表单名称</div>
            {if $_configs['App_im']}<div class="m2o-item m2o-process replay w140" title="回复状态"> 回复状态</div>{/if}
            <div class="m2o-item m2o-process w140" title="处理状态"> 处理状态</div>
            <div class="m2o-item m2o-num w140" title="发布至">回收途径</div>
            <div class="m2o-item m2o-time" title="添加人/时间">回收时间</div>
        </div>
        <div class="m2o-each-list">
        	{if is_array($list['list']) && count($list['list'])>0}
				{foreach $list['list'] as $k => $v}	
		            {template:unit/resultlist}
		        {/foreach}
			{else}
				<p class="common-list-empty">没有你要找的内容！</p>
			{/if}
        </div>
        <div class="m2o-bottom m2o-flex m2o-flex-center">
		  	 <div class="m2o-item m2o-paixu">
        		<input type="checkbox" name="checkall" class="checkAll" rowtag="m2o-item" title="全选"/>
    		</div>
    		<div class="m2o-item m2o-flex-one m2o-allprocess">
    		   <a class="proce" onclick="return hg_change_process(this,'待处理','0');">待处理</a>
    		   <a class="proce" onclick="return hg_change_process(this,'已处理','1');">已处理</a>
    		   <a class="proce" onclick="return hg_change_process(this,'未通过','2');">未通过</a>
    		   <a class="batch-handle">删除</a>
    		</div>
    		<div id="page_size">{$pagelink}</div>
		</div>
    </div>
   </form>
  
 </div>
 <script type="text/javascript">
function downloadFile(obj)
{
	window.location.href = $(obj).attr('_href');
}
 </script>
 <script>
	var data = $.globalListData = {code}echo $list['list'] ? json_encode($list['list']) : '{}';{/code};
    $.extend($.geach || ($.geach = {}), {
        data : function(id){
            var info;
            $.each(data, function(i, n){
               if(n['id'] == id){
                   info = {
                       id : n['id']
                   }
                   return false;
               }
            });
            return info;
        }
    });
    $('.m2o-each').geach();
	$('.m2o-list').glist();
</script>
<script>
function hg_change_process(obj,name,para) {
	var ids = $(obj).closest('form')
	.find('input:checked:not([name="checkall"])')
	.map(function() { return this.value; }).get().join(','),
	msg;

	if (!ids) {
		msg = '请选择要' + name + '的记录', name + '提醒';
		jAlert ? jAlert(msg, name + '提醒').position(obj) : alert(msg);
		return false;
		}
	var doRequest = function() {
		var data = {};
		    data.id = ids;
		    data.process = para;
		process_text = ['待处理','已处理','未通过'];
		process_color = ['#8ea8c8','#17b202','#f8a6a6'];  
		var p = $(obj).closest('form').find('.m2o-each.selected'),
		    pro = p.find(".m2o-process span");
		var url = './run.php?mid='+gMid+'&a=process'+'&ajax=1';
		$.globalAjax( p, function(){
			return $.post( url,data, function( ret ){
			if( ret['callback'] ){
					eval( ret['callback'] );
					return;
				}
			var ret = ret[0],
			    process = ret.process,
			    color = process_color[process],
			    text = process_text[process];
			pro.attr('_process', process);
			pro.css({'color':color});
			if(process == 0) pro.text('待处理');
			if(process == 1) pro.text('已处理');
			if(process == 2) pro.text('未通过');
			},'json');
		});
	};
	msg = '您确认批量' + name + '选中记录吗？';
	hg_safe_confirm(msg, name, doRequest, obj);
	return false;	
}

jQuery(function() {
	$('.sendCode').live('click', function() {
		var url = $(this).attr('_url');
		$.getJSON(url, function(data) {
			if (typeof data[0] == 'undefined') {
				alert('发送失败');
			}else if (data[0].code == 0) {
				alert(data[0].msg);
			}
		});
	});
});
</script>
<script type="text/x-jquery-tmpl" id="replay-tpl">
 <div class="replay-box">
   		<div class="replay-head m2o-flex">
   			<img src="{{= img}}" />
   			<span class="user-name">{{= username}}</span>
   			<h3>标题：{{= title}}</h3>
   			<span class="replay-close">x</span>
   		</div>
   		<div class="replay-body">
   			<ul class="list-box">
				{{if msg}}
				{{each msg}}
   					<li class="replay-list {{if $value.utype == 'admin'}}server-replay {{else}}user-replay{{/if}} clear">{{= $value.message}}</li>
				{{/each}}
				{{/if}}
   			</ul>
   		</div>
   		<div class="replay-footer">
   			<textarea class="replay-msg" placeholder="在此输入回复信息..."></textarea>
   			<span class="replay-btn" _type="{{if msg}}1{{else}}0{{/if}}">发送</span>
   		</div>
 </div>
</script>

<script type="text/x-jquery-tmpl" id="m2o-option-tpl">
<div class="m2o-option" data-id="{{= id}}">
    <div class="m2o-option-inner m2o-flex">
        <div class="m2o-btns m2o-flex">
			<div class="m2o-btn-area m2o-flex">
				<a href="./run.php?mid={$_INPUT['mid']}&a=form&id={{= id}}&fid={$_INPUT['fid']}&infrm=1" target="formwin" need-back>详情</a>
				<a class="option-delete">删除</a>
				{if $_INPUT['fid'] == 1}
				<a _url="./run.php?mid={$_INPUT['mid']}&a=send&id={{= id}}&ajax=1" class="sendCode">发送邀请码</a>
				{/if}
				<a href="./run.php?mid={$_INPUT['mid']}&a=preview&id={{= id}}&fid={$_INPUT['fid']}&infrm=1" target="_blank" go-blank >预览</a>
			</div>
			<div class="m2o-option-line"></div>
        </div>
    </div>
	<div class="m2o-option-confirm">
			<p>确定要删除该内容吗？</p>
			<div class="m2o-option-line"></div>
			<div class="m2o-option-confim-btns">
				<a class="confim-sure">确定</a>
				<a class="confim-cancel cancel">取消</a>
			</div>
	</div>
	<div class="m2o-option-close"></div>
</div>
</script>
{template:foot}

