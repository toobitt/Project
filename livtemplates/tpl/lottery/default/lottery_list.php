<?php 
/* $Id: group_list.php 9410 2012-05-22 07:43:34Z lijiaying $ */
?>
{template:head}
{css:2013/list}
{js:2013/list}
{js:2013/ajaxload_new}
{js:box_model/list_sort}
{js:lottery/lottery_list}
{code}
//print_r($list[0]['activ_status']);
{/code}
<style>
.m2o-bt:hover .common-title{padding-left:15px;}
.common-title{-webkit-transition: all 0.15s ease-in 0s;transition: all 0.15s ease-in 0s;}
.w80{width:80px;}
.w180{width:180px;}
.color{color: #8fa8c6;}
.common-list-pub{text-decoration: underline;color: #49a34b;}
.m2o-color-state{width: 8px;height: 8px;border-radius: 50%;display: block;float: left;margin: 5px;}
.m2o-btn-area a{width:auto!important;text-align: center!important;}
.first-area a{padding:0px 38px!important;}
.second-area a{padding:0px 52px!important;}
.first-area a:not(:last-child):before{left:104px;}
.first-area a:not(:last-child):after{left:103px;}
.second-area a:not(:last-child):before{left:161px;}
.second-area a:not(:last-child):after{left:160px;}
.first-area a:last-child:before{display:none;}
.first-area a:last-child:after{display:none;}
.second-area a:last-child:before{display:none;}
.second-area a:last-child:after{display:none;}
.m2o-link{position:relative;cursor:pointer;}
.link-box{background: #fff;border: 1px solid #ddd;position: absolute;top:25px;left:10px;padding: 10px;border-radius: 5px;z-index: 999;display:none;}
.link-box:before{position: absolute;content: '';border: 7px solid transparent;border-bottom-color: #d9dfe7;top:-15px;left:14px;}
.link-box:after{position: absolute;content: '';border: 7px solid transparent;border-bottom-color: #fff;top:-14px;left:14px;}
.link-box .clone{display: block;margin:0px 10px 0px 10px;}
.link-box a:hover{text-decoration:underline!important;}
.link-box object{position: absolute;width: 30px;right: 5px;top: 8px;opacity: 0.00001;}
</style>
<div style="display:none">
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		<a href="run.php?mid={$_INPUT['mid']}&a=form" target="formwin"><span class="add-font-pack button_6">新增抽奖</span></a>
	</div>
</div>
<div class="common-list-content" style="min-height:auto;min-width:auto;">
<div id="add_question"  class="single_upload">
					<div id="question_option_con">
					</div>
				</div>
	<form action="" method="post">
	 <div class="m2o-list">
			<!--排序模式打开后显示排序状态-->
			<div class="m2o-title m2o-flex m2o-flex-center">
		 	   <div id="infotip" class="ordertip">排序模式已关闭</div>
		       <div class="m2o-item m2o-paixu" title="排序">
		        	<a title="排序模式切换/ALT+R" class="common-list-paixu"></a>
		       </div>
           <div class="m2o-item m2o-flex-one m2o-bt" title="对象">标题</div>
           	 <div class="m2o-item m2o-state" title="分类">链接</div>
             <div class="m2o-item m2o-state" title="分类">分类</div>
             <div class="m2o-item m2o-state" title="类型">类型</div>
             <div class="m2o-item m2o-aduit w180" title="有效期">有效期</div>
             <div class="m2o-item m2o-aduit w80" title="状态">状态</div>
             <div class="m2o-item m2o-time" title="添加人/时间">添加人/时间</div>
        </div>
        <div class="m2o-each-list">
        	{if is_array($list) && count($list)>0}
				{foreach $list as $k => $v}	
		            {template:unit/lottery_list_list}
		        {/foreach}
			{else}
				<p class="common-list-empty">没有你要找的内容！</p>
			{/if}
        </div>
        <div class="m2o-bottom m2o-flex m2o-flex-center">
		  	 <div class="m2o-item m2o-paixu">
        		<input type="checkbox" name="checkall" class="checkAll" rowtag="m2o-item" title="全选"/>
    		</div>
    		<div class="m2o-item m2o-flex-one">
    		   <a class="batch-handle">审核</a>
    		   <a class="batch-handle">打回</a>
    		   <a class="batch-handle">删除</a>
    		</div>
    		<div id="page_size">{$pagelink}</div>
		</div>
    </div>
   </form>
 </div>
 <script>
	var data = $.globalListData = {code}echo $list ? json_encode($list) : '{}';{/code};
	function copySuccess(){
		//flash回调
		alert("已复制到粘贴板！");
	}
</script>
<script type="text/x-jquery-tmpl" id="m2o-option-tpl">
<div class="m2o-option" data-id="{{= id}}">
    <div class="m2o-option-inner m2o-flex">
        <div class="m2o-btns m2o-flex">
			<div class="m2o-btn-area first-area m2o-flex">
				<a href="./run.php?mid={$_INPUT['mid']}&a=form&id={{= id}}&infrm=1" target="formwin" need-back>编辑</a>
				<a class="option-audit">{{if status == 1}}打回{{else}}审核{{/if}}</a>
				<a class="option-delete">删除</a>
			</div>
			<div class="m2o-btn-area second-area m2o-flex">
				<a href="./run.php?mid={$_INPUT['mid']}&a=relate_module_show&app_uniq=lottery&mod_uniq=win_info&lottery_id={{= id}}&need_lottery=1&need_prize=1&infrm=1" target="mainwin" need-back>中奖信息</a>
				<a class="create_new_form">生成表单</a>
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

