<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{code}
$list = $mobile_api_list[0];
$appendSort = $appendSort[0];

{/code}

{css:vod_style}
{css:mark_style}
{css:common/common_list}
{css:jsonview/jquery.jsonview}
{js:common/common_list}
{js:2013/ajaxload_new}
{js:jsonview/jquery.jsonview}
<style>
.handler-icon{display:inline-block;background:#fdd01b;color:white;padding:0 8px;line-height:24px;border-radius:4px;}
.makefile{background:#1bbc9b;}
.tips{position:fixed;border:4px solid #6ba4eb;width:400px;height:80px;top:200px;left:50%;line-height:80px;text-align:center;font-size:18px;margin-left:-200px;opacity:0;z-index:-10;background:white;transition:all ease-in .4s;}

.common-list-ajax-pub-pointer{font-size:0;line-height:0;height:24px;width:12px;background:url('../../../../.././../livtemplates/tpl/lib/images/vod_fb.png') no-repeat;position:absolute;left:-20px;top;0;z-index:100000;}
.common-list-ajax-pub{transition:top .5s;-webkit-transition:top .5s;}
.common-list-ajax-pub{display:block;width: 700px;position:absolute;left:50%;top:-440px;z-index:999999;}
.common-list-pub-title{line-height:50px;background:#6EA5E8;font-size:20px;color:#fff;}
.common-list-pub-title p{margin:0 10px;border-bottom:1px solid #9ac0ef;}
.common-list-ajax-pub > span, .common-list-pub-close{position:absolute;right:15px;top:18px;background:url("../../../../.././../livtemplates/tpl/lib/images/column_publish/close.png") no-repeat;width:14px;height:14px;cursor:pointer;}
.common-list-pub-title > div{opacity:1;position:absolute;color:#fff;font-size:12px;left:215px;top:0;}
.common-list-pub-title > div > p{float:left;max-width:350px;}
.common-list-pub-title > div > div p{margin-bottom:10px;}
.common-list-pub-title > div > div{opacity:0;position:absolute;top:10px;color:#6ea5e8;line-height:10px;left:0;border:3px solid rgba(0, 0, 0, .3);padding:10px 0 5px;background:#fff;}
.common-list-pub-title > div:hover > div{z-index:1;opacity:1;}
.common-list-pub-title > div:hover > p, .common-list-pub-title > div:hover span{opacity:0;}
.common-list-pub-title span, .common-list-pub-title p, .common-list-pub-title div{-webkit-transition:opacity .3s;}
.common-list-ajax-pub .publish-box{height:350px;background:#fff;overflow: auto;}
.common-list-ajax-pub .common-list-pub-body{border-right:10px solid #6EA5E8;border-left:10px solid #6EA5E8;border-bottom:10px solid #6EA5E8}


</style>
<script type="text/javascript">
function downloadFile(obj)
{
	var ids = $(obj).closest('form')
	.find('input:checked:not([name="checkall"])')
	.map(function() { return this.value; }).get().join(','),
msg;
	window.location.href = $(obj).attr('_href') + '&id=' + ids;
}
function build_api_file_callback(data)
{
	var data = JSON.parse(data);
	
	if(data == 'success')
	{
		var words = '生成成功';
	}
	else
	{
		var words = '生成失败';
	}
	var tip = $('.tips');
	tip.text(words).css({'opacity':1,'z-index':100001});
	setTimeout(function(){
		tip.css({'opacity':0,'z-index':-10});
	},1600);
}

$(function(){
	$('.preview').on('click' , function( event ){
		var self = $( event.currentTarget ),
			parent = self.closest('.common-list-data'),
			id = parent.attr('_id'),
			title = parent.find('.m2o-common-title').text(),
			url = './run.php?mid=' + gMid + '&a=data_preview&id='+ id;
		$('.common-list-pub-title').find('p').text( title );
		$.globalAjax( self, function(){
			return $.getJSON( url,function( data ){
				$(".publish-box").JSONView( data , {collapsed: true, nl2br: true});
				$('.common-list-ajax-pub').css('top' , '0px');
			});
		} );
	})
})

</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program">
	<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" target="formwin" class="button_6" style="font-weight:bold;">添加文件</a>
</div>
<div class="content clear">
	<div class="f">
          <div class="right v_list_show">
                <div class="search_a" id="info_list_search">
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
                    <div class="right_1">
						{code}
							$attr_group = array(
								'class' => 'transcoding down_list',
								'show' => 'node_type_show',
								'width' => 104,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
							);
							$appendSort[-1] = '全部分组';
							$group_default = $_INPUT['sortid'] ? $_INPUT['sortid'] : -1;


						{/code}
						{code}
							$attr_app = array(
								'class' => 'transcoding down_list',
								'show'  => 'select_ap',
								'width' => 104,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
								'onclick' => 'change_module();'
							);
							$apps = $apps[0];
							$apps['-1'] = "所有应用";
							
							$bundle =  $_INPUT['bundle'];
							$bundle = $bundle ? $bundle : -1;
						{/code}
					
						{template:form/search_source,sortid,$group_default,$appendSort,$attr_group}
						{template:form/search_source,bundle,$bundle,$apps,$attr_app}
						
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
                <form method="post" action="" name="listform">
                     <ul class="common-list" id="list_head">
                        <li class="common-list-head clear public-list-head">
                            <div class="common-list-left">
                                <div class="common-list-item paixu"><a class="common-list-paixu" onclick="hg_switch_order('vodlist');"  title="排序模式切换/ALT+R"></a></div>
                            </div>
                            <div class="common-list-right"> 
                                
                                <div class="common-list-item wd150">功能描述</div>
                                <div class="common-list-item">删除</div>
                                <div class="common-list-item wd150">所属分组</div>
                                <div class="common-list-item">文件配置</div>
                                <div class="common-list-item">字段映射</div>
                                <div class="common-list-item">生成文件</div>
                                <div class="common-list-item">查看文件</div>
                                <div class="common-list-item">复制</div>
                                <div class="common-list-item">预览</div>
                            </div>
                            <div class="common-list-biaoti">
						        <div class="common-list-item">文件名称</div>
					        </div>
                        </li>
                     </ul>
                     <div class="tips"></div>
               		 <ul class="common-list public-list" id="vodlist">
					  	{if is_array($list) && count($list)>0}
							{foreach $list as $k => $v}	
							{code}
							$v['brief'] = $v['brief'] ? $v['brief'] : $v['brif'];
							{/code}
		                      {template:unit/api_list}
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
	                     <a style="cursor:pointer;"  onclick="downloadFile(this);" href="javascript:###" _href="./run.php?mid={$_INPUT['mid']}&a=download" >批量导出</a>
	                     <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'build_api_file',  '生成文件', 1, 'id', '', 'ajax','');"    name="bataudit" >批量生成</a>
				         <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>
				      </div>
	               {$pagelink}
	               </li>
	             </ul>
    		</form>
 	</div>
</div>
</div>
<!-- 预览模板 -->
<div id="vodpub" class="common-list-ajax-pub" style="margin-left: -350px;">
	<div class="common-list-pub-title">
		<p>预览</p>
	</div>
	<div id="vodpub_body" class="common-list-pub-body">
	    <div class="publish-box">
	    </div>
	</div>
	<span onclick="hg_vodpub_hide();"></span>
</div>
</body>
{template:foot}